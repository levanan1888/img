<?php

namespace App\Http\Controllers;

use App\Models\ConversionLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // 1. Check env credentials
        $adminUsername = env('ADMIN_USERNAME', 'admin');
        $adminPassword = env('ADMIN_PASSWORD', 'admin');

        if ($username === $adminUsername && $password === $adminPassword) {
            $request->session()->regenerate();
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully!');
        }

        // 2. Check database users table
        $credentials = [
            'email' => $username,
            'password' => $password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully!');
        }

        return redirect()->back()
            ->withInput($request->only('username'))
            ->withErrors(['login_error' => 'Invalid admin credentials.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        session()->forget('admin_logged_in');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully!');
    }

    public function dashboard(): View
    {
        $totalConversions = ConversionLog::count();
        $successCount = ConversionLog::where('status', 'success')->count();
        $failedCount = ConversionLog::where('status', 'failed')->count();
        
        $successRate = $totalConversions > 0 ? round(($successCount / $totalConversions) * 100, 1) : 100.0;
        
        $totalBytesProcessed = ConversionLog::where('status', 'success')->sum('file_size');
        $totalDataProcessed = $this->formatBytes($totalBytesProcessed);
        
        $avgExecutionTime = ConversionLog::where('status', 'success')->avg('execution_time') ?? 0;
        $avgExecutionTime = round($avgExecutionTime, 3);

        // Fetch 7-day conversion statistics
        $chartData = [];
        $maxVal = 1; // prevent division by zero
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $displayDate = now()->subDays($i)->format('d/M');
            
            $success = ConversionLog::where('status', 'success')
                ->whereDate('created_at', $date)
                ->count();
                
            $total = ConversionLog::whereDate('created_at', $date)
                ->count();
                
            if ($total > $maxVal) {
                $maxVal = $total;
            }
            
            $chartData[] = [
                'date' => $displayDate,
                'total' => $total,
                'success' => $success,
            ];
        }

        // Build SVG coordinates (1000x200 viewbox)
        $totalCoords = [];
        $successCoords = [];
        foreach ($chartData as $index => $data) {
            $x = $index * 166.6;
            // Scale Y so that maxVal is at Y=20 and 0 is at Y=180
            $yTotal = 180 - ($data['total'] / $maxVal * 150);
            $ySuccess = 180 - ($data['success'] / $maxVal * 150);
            
            $totalCoords[] = ['x' => $x, 'y' => $yTotal, 'val' => $data['total']];
            $successCoords[] = ['x' => $x, 'y' => $ySuccess, 'val' => $data['success']];
        }
        
        $totalPointsStr = implode(' ', array_map(fn($c) => "{$c['x']},{$c['y']}", $totalCoords));
        $successPointsStr = implode(' ', array_map(fn($c) => "{$c['x']},{$c['y']}", $successCoords));
        
        $totalAreaStr = "0,180 " . $totalPointsStr . " 1000,180";
        $successAreaStr = "0,180 " . $successPointsStr . " 1000,180";

        // Count current temp files in working directory
        $tempFilesPath = storage_path('app/conversions');
        $tempFilesCount = 0;
        $tempFilesSize = 0;
        if (is_dir($tempFilesPath)) {
            $files = glob($tempFilesPath . '/*');
            $tempFilesCount = count($files);
            foreach ($files as $file) {
                if (is_file($file)) {
                    $tempFilesSize += filesize($file);
                }
            }
        }
        $formattedTempSize = $this->formatBytes($tempFilesSize);

        return view('admin.dashboard', compact(
            'totalConversions',
            'successCount',
            'failedCount',
            'successRate',
            'totalDataProcessed',
            'avgExecutionTime',
            'tempFilesCount',
            'formattedTempSize',
            'chartData',
            'totalPointsStr',
            'successPointsStr',
            'totalAreaStr',
            'successAreaStr',
            'totalCoords',
            'successCoords'
        ));
    }

    public function users(): View
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function createUser(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect()->back()->with('success', 'User registered successfully!');
    }

    public function deleteUser($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User account deleted successfully!');
    }

    public function logs(): View
    {
        $logs = ConversionLog::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.logs', compact('logs'));
    }

    public function deleteLog($id): RedirectResponse
    {
        $log = ConversionLog::findOrFail($id);
        $log->delete();
        return redirect()->back()->with('success', 'Conversion log deleted successfully!');
    }

    public function purgeTemp(): RedirectResponse
    {
        $tempFilesPath = storage_path('app/conversions');
        $deletedCount = 0;
        $bytesSaved = 0;

        if (is_dir($tempFilesPath)) {
            $files = glob($tempFilesPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    $bytesSaved += filesize($file);
                    if (@unlink($file)) {
                        $deletedCount++;
                    }
                }
            }
        }

        $savedStr = $deletedCount > 0 ? "Saved " . $this->formatBytes($bytesSaved) . "." : "";
        return redirect()->back()->with('success', "Purged $deletedCount temp files successfully. $savedStr");
    }

    private function formatBytes(int $bytes, int $decimals = 2): string
    {
        if ($bytes <= 0) return '0 Bytes';
        $k = 1024;
        $dm = $decimals < 0 ? 0 : $decimals;
        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes) / log($k));
        return number_format($bytes / pow($k, $i), $dm) . ' ' . $sizes[$i];
    }
}

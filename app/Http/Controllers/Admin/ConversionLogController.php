<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversionLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConversionLogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $sourceFormat = $request->input('source_format');
        $targetFormat = $request->input('target_format');

        $query = ConversionLog::orderBy('created_at', 'desc');

        if ($search) {
            $query->where('file_name', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($sourceFormat) {
            $query->where('source_format', $sourceFormat);
        }

        if ($targetFormat) {
            $query->where('target_format', $targetFormat);
        }

        $logs = $query->paginate(15)->withQueryString();
        
        $sourceFormats = ['jpg', 'jpeg', 'png', 'webp', 'bmp'];
        $targetFormats = ['png', 'webp', 'jpg'];

        return view('admin.conversions.index', compact('logs', 'sourceFormats', 'targetFormats'));
    }

    public function destroy($id): RedirectResponse
    {
        $log = ConversionLog::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'Đã xóa nhật ký chuyển đổi thành công.');
    }

    public function clearAll(): RedirectResponse
    {
        ConversionLog::truncate();
        return redirect()->back()->with('success', 'Đã dọn dẹp sạch toàn bộ nhật ký chuyển đổi.');
    }

    public function exportCsv(): StreamedResponse
    {
        $fileName = 'conversion_logs_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, [
                'Tên Tệp Tin', 
                'Dung Lượng (Bytes)', 
                'Định Dạng Nguồn', 
                'Định Dạng Đích', 
                'Trạng Thái', 
                'Thời Gian Chạy (s)', 
                'Thông Báo Lỗi', 
                'Địa Chỉ IP',
                'Thời Gian Tạo'
            ]);

            $logs = ConversionLog::orderBy('created_at', 'desc')->get();
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->file_name,
                    $log->file_size,
                    $log->source_format,
                    $log->target_format,
                    $log->status,
                    $log->execution_time,
                    $log->error_message ?: '',
                    $log->ip_address,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

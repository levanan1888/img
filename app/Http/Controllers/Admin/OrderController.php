<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusTimeline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $paymentStatus = $request->input('payment_status');
        $status = $request->input('status');

        $query = Order::with('user', 'latestStatus')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('order_number', 'like', "%{$search}%");
        }

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($status) {
            $query->whereHas('latestStatus', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id): View
    {
        $order = Order::with('user', 'items.product', 'timeline.modifier')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'comment' => 'nullable|string|max:255',
            'payment_status' => 'required|in:pending,paid,refunded,failed',
        ]);

        // Only insert timeline log if status has changed
        $currentStatus = $order->latestStatus ? $order->latestStatus->status : 'pending';
        if ($currentStatus !== $request->status) {
            OrderStatusTimeline::create([
                'order_id' => $order->id,
                'status' => $request->status,
                'comment' => $request->comment ?: "Order status updated to " . ucfirst($request->status),
                'changed_by' => auth()->id()
            ]);
        }

        $order->update([
            'payment_status' => $request->payment_status
        ]);

        return redirect()->back()->with('success', 'Order status and details updated successfully!');
    }

    public function invoice($id): View
    {
        $order = Order::with('user', 'items.product')->findOrFail($id);
        return view('admin.orders.invoice', compact('order'));
    }

    public function exportCsv(): StreamedResponse
    {
        $fileName = 'orders_export_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for correct character display in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, [
                'Order Number', 
                'Customer Name', 
                'Customer Email', 
                'Total Amount', 
                'Payment Status', 
                'Payment Method', 
                'Shipping Address', 
                'Date Created'
            ]);

            $orders = Order::with('user', 'latestStatus')->orderBy('created_at', 'desc')->get();
            
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user ? $order->user->name : 'N/A',
                    $order->user ? $order->user->email : 'N/A',
                    $order->total_amount,
                    $order->payment_status,
                    $order->payment_method,
                    $order->shipping_address,
                    $order->created_at->format('Y-m-d H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

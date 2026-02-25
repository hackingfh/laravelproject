<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }

    public function index(Request $request)
    {
        Log::info('Admin orders list', ['admin_id' => $request->user()->id]);
        $q = Order::query()->with('user')->withCount('items');
        if ($s = $request->string('status')->toString()) {
            $q->where('status', $s);
        }
        if ($from = $request->string('date_from')->toString()) {
            $q->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->string('date_to')->toString()) {
            $q->whereDate('created_at', '<=', $to);
        }
        if ($client = $request->string('client')->toString()) {
            $q->whereHas('user', fn ($u) => $u->where('email', 'like', '%'.$client.'%'));
        }
        $orders = $q->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($orders);
    }

    public function show(int $id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        $history = [
            ['status' => 'pending', 'date' => $order->created_at],
            ['status' => $order->status, 'date' => $order->updated_at],
        ];

        return response()->json(compact('order', 'history'));
    }

    public function changeStatus(int $id, Request $request)
    {
        $order = Order::findOrFail($id);
        $old = $order->status;
        $order->update(['status' => $request->string('status')->toString()]);
        Log::info('Order status changed', ['order_id' => $order->id, 'old' => $old, 'new' => $order->status]);
        Mail::raw("Votre commande {$order->order_number} est maintenant {$order->status}", function ($m) use ($order) {
            $m->to($order->user->email)->subject('Mise à jour de commande');
        });

        return response()->json(['message' => 'Statut mis à jour', 'order' => $order]);
    }

    public function refund(int $id, Request $request)
    {
        $order = Order::findOrFail($id);
        $amount = (float) $request->input('amount', $order->total);
        Log::info('Order refund requested', ['order_id' => $order->id, 'amount' => $amount]);
        $order->update(['payment_status' => 'refunded']);

        return response()->json(['message' => 'Remboursement effectué', 'order' => $order, 'amount' => $amount]);
    }

    public function exportCsv(Request $request)
    {
        $rows = Order::select(['id', 'order_number', 'user_id', 'total', 'status', 'created_at'])->get();
        $csv = implode(',', ['id', 'order_number', 'user_id', 'total', 'status', 'created_at'])."\n";
        foreach ($rows as $r) {
            $csv .= "{$r->id},{$r->order_number},{$r->user_id},{$r->total},{$r->status},{$r->created_at}\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'orders.csv', ['Content-Type' => 'text/csv']);
    }

    public function invoicePdf(int $id)
    {
        return response()->json(['message' => 'Invoice PDF requires dompdf integration'], 501);
    }
}

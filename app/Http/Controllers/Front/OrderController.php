<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orders
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $filters = [
            'status' => $request->string('status')->toString(),
            'date_from' => $request->string('date_from')->toString(),
            'date_to' => $request->string('date_to')->toString(),
            'amount_min' => $request->input('amount_min'),
            'amount_max' => $request->input('amount_max'),
        ];
        $list = $this->orders->listForUser(Auth::user(), $filters, 10);

        return response()->json($list);
    }

    public function show(int $id)
    {
        $order = $this->orders->findByIdForUser(Auth::user(), $id);
        abort_if(! $order, 404);
        $tracking = [
            'carrier' => 'DHL',
            'status' => 'in_transit',
            'eta' => now()->addDays(2)->toDateString(),
        ];
        $invoiceUrl = '/orders/'.$order->id.'/invoice.pdf';
        $canReorder = true;

        return response()->json(compact('order', 'tracking', 'invoiceUrl', 'canReorder'));
    }
}

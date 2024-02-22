<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ApiResponser;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order->with('products');
    }

    /**
     * Display a listing of the customer's orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'success' => true,
            'message' => 'Lấy thành công danh sách đơn hàng.',
        ];
        
        $user = Auth::user();

        if ($user->isAdministrator()) {
            $orders = $this->order->paginate(10);
        } else {
            $orders = $this->order->where('customer_id', $user->customer->id)->paginate(10);
        }
        return (new OrderCollection($orders))->additional($data)->response();
    }

    /**
     * Display the specified orders.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order = $this->order->findOrFail($order->id);
        $message = 'Lấy thành công thông tin đơn hàng.';

        return $this->successReponse(new OrderResource($order), $message);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $order = $this->order->findOrFail($order->id);
        $order->update($request->validated());
        $message = 'Cập nhật trạng thái đơn hàng thành công.';

        return $this->successReponse(new OrderResource($order), $message);
    }

    public function cancelOrder(Order $order)
    {
        $this->authorize('cancel_order', $order);

        $order = $this->order->findOrFail($order->id);
        $order->status = OrderStatusEnum::Cancelled;
        $order->save();

        $message = 'Hủy đơn hàng thành công.';

        return $this->successReponse(new OrderResource($order), $message);
    }

    public function confirmReceivedOrder(Order $order)
    {
        $this->authorize('cancel_order', $order);

        $order = $this->order->findOrFail($order->id);
        $order->status = OrderStatusEnum::Received;
        $order->save();

        return $this->successReponse(new OrderResource($order), '');
    }
}

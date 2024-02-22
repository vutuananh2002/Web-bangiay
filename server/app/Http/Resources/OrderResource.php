<?php

namespace App\Http\Resources;

use App\Enums\OrderStatusEnum;
use App\Http\Resources\Admin\CustomerResource;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $isAuth = Auth::check();
        $isAdmin = $isAuth ? Auth::user()->isAdministrator() : false;
        $customer = $this->whenLoaded('customer');
        $products = $this->whenLoaded('products');
        $status = $this->status ?? 0;
        $created_at = $this->created_at->format('d/m/Y');
        $updated_at = $this->updated_at->format('d/m/Y');

        return [
            'id' => $this->id,
            'customer' => $this->when($isAuth && $isAdmin, new CustomerResource($customer)),
            'products' => OrderProductResource::collection($products),
            'receiver_name' => $this->receiver_name,
            'receiver_phone_number' => $this->receiver_phone_number,
            'receiver_address' => $this->receiver_address,
            'total_price' => number_format($this->total_price, 0, '', ','),
            'status' => OrderStatusEnum::getDescription($status),
            'transaction_id' => $this->transaction_id,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductToCartRequest;
use App\Http\Requests\CheckoutOrderRequest;
use App\Http\Requests\RemoveProductFromCartRequest;
use App\Http\Requests\VerifyCartKeyRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Notifications\OrderSuccessfully;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    use ApiResponser;

    protected $cart;
    protected $product;
    protected $order;

    public function __construct(Cart $cart, Product $product, Order $order)
    {
        $this->cart = $cart->with(['products']);
        $this->product = $product;
        $this->order = $order;
    }

    /**
     * Store a newly created cart in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Cart::class);

        $user = Auth::user();
        $customerID = $user->customer->id;

        $cart = $this->cart->create([
            'key' => Str::uuid(),
            'customer_id' => $customerID,
        ]);

        $message = 'Tạo giỏ hàng thành công.';

        return $this->successCreatedResponse(new CartResource($cart->load('products')), $message);
    }

    /**
     * Display the specified cart.
     *
     * @param \App\Models\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart, VerifyCartKeyRequest $request)
    {
        $this->authorize('view', $cart);

        $validated = $request->validated();
        $user = Auth::user();
        $customerID = $user->customer->id;
        $cartKey = $validated['key'];
        
        if ($cart->key === $cartKey) {
            $cart = $this->cart->whereIDCustomerIDAndCartKey($cart->id, $customerID, $validated['key'])->first();
            $message = 'Lấy thông tin giỏ hàng thành công.';

            return $this->successReponse(new CartResource($cart), $message);
        }

        $message = 'Key giỏ hàng mà bạn cung cấp, không khớp với key giỏ hàng này.';

        return $this->badRequestResponse($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart, VerifyCartKeyRequest $request)
    {
        $this->authorize('delete', $cart);

        $validated = $request->validated();
        $user = Auth::user();
        $customerID = $user->customer->id;
        $cartKey = $validated['key'];
        
        if (!($cart->key === $cartKey)) return $this->badRequestResponse('Key giỏ hàng mà bạn cung cấp, không khớp với key giỏ hàng này.');

        $cart = $this->cart->whereIdCustomerIdAndCartKey($cart->id, $customerID, $cartKey)->delete();
        $message = 'Xóa giỏ hàng thành công.';

        return $this->successReponse(null, $message);
    }

    /**
     * Add product to the given cart.
     * 
     * @param \App\Models\Cart $cart
     * @param \App\Http\Requests\AddProductToCartRequest $request
     * @return \Illuminate\Http\Response
     */
    public function addProduct(Cart $cart, AddProductToCartRequest $request)
    {
        $this->authorize('add_product_to_cart', $cart);

        $validated = $request->validated();
        $user = Auth::user();
        $customerID = $user->customer->id;

        $cartKey = $validated['key'];
        $product_id = $validated['product_id'];
        $quantity = $validated['quantity'];
        $color_id = $validated['color_id'];
        $size_id = $validated['size_id'];
        $type_id = $validated['type_id'];
        
        if (!($cart->key === $cartKey)) return $this->badRequestResponse('Key giỏ hàng mà bạn cung cấp, không khớp với key giỏ hàng này.');

        // Check if the product already in the cart, if true update product's quantity, if not create a new one.
        $cartProduct = $cart->products()->wherePivot('product_id', $product_id)->get();
        
        $productInCart = $cartProduct->count();
        
        if ($productInCart) {
            $cart->products()->updateExistingPivot($product_id, [
                'quantity' => $quantity,
                'color_id' => $color_id,
                'size_id' => $size_id,
                'type_id' => $type_id,
            ]);
        } else {
            $cart->products()->attach($product_id, [
                'quantity' => $quantity,
                'color_id' => $color_id,
                'size_id' => $size_id,
                'type_id' => $type_id,
            ]);
        }

        $cart = $this->cart->whereIdCustomerIdAndCartKey($cart->id, $customerID, $cartKey)->first();
        $message = 'Thêm sản phẩm vào giỏ hàng thành công.';

        return $this->successReponse(new CartResource($cart), $message);
    }

    /**
     * Remove product from the cart.
     * 
     * @param \App\Models\Cart $cart
     * @param \App\Http\Requests\RemoveProductFromCartRequest $request
     * @return \Illuminate\Http\Response
     */
    public function removeProduct(Cart $cart, RemoveProductFromCartRequest $request)
    {
        $this->authorize('remove_product_from_cart', $cart);

        $validated = $request->validated();
        $cartKey = $validated['key'];
        $products = $validated['products'];

        if (!($cart->key === $cartKey)) return $this->badRequestResponse('Key giỏ hàng mà bạn cung cấp, không khớp với key giỏ hàng này.');

        $cart->products()->detach($products);
        $message = 'Xóa sản phẩm khỏi giỏ hàng thành công.';

        return $this->successReponse(new CartResource($cart), $message);
    }

    /**
     * Checkout the order.
     * 
     * @param \App\Models\Cart $cart
     * @param \App\Http\Requests\CheckoutOrderRequest $request
     * @return \Illuminate\Http\Request
     */
    public function checkout(Cart $cart, CheckoutOrderRequest $request)
    {
        $this->authorize('checkout', $cart);

        $validated = $request->validated();
    
        $user = Auth::user();
        $customerID = $user->customer->id;
        $cartKey = $validated['key'];
        
        if (!($cart->key === $cartKey)) return $this->badRequestResponse('Key giỏ hàng mà bạn cung cấp, không khớp với key giỏ hàng này.');

        $receiver_name = $validated['receiver_name'];
        $receiver_phone_number = $validated['receiver_phone_number'];
        $receiver_address = $validated['receiver_address'];
        $totalPrice = 0;
        $orderProduct = [];

        // Get information of products in the customer's cart.
        $productInCart = $cart->products;
        $productInCart = $productInCart->pluck('pivot');
        $productIDs = $productInCart->pluck('product_id');
        $products = $this->product->whereIn('id', $productIDs)->get(['id', 'stock', 'price']);

        // Use chunk method to improve sql performance when customer order bulk products.
        foreach ($products->chunk($productInCart->count()) as $chunk) {
            $cases = [];
            $params = [];
            $ids = [];

            foreach ($chunk as $index => $product) {
                $stock = $product->stock;
                $price = $product->price;
                $orderQuantity = $productInCart[$index]->quantity;
                $orderColor = $productInCart[$index]->color_id;
                $orderSize = $productInCart[$index]->size_id;
                $orderType = $productInCart[$index]->type_id;

                if ($orderQuantity > $stock) return $this->badRequestResponse('Số lượng sản phẩm mà bạn đặt nhiều hơn số lượng sản phẩm chúng tôi có!');

                // Caculate the total price and stock.
                $totalPrice += $price * $orderQuantity;
                $stock -= $orderQuantity;
                $orderProduct[$product->id] = [
                    'quantity' => $orderQuantity,
                    'color_id' => $orderColor,
                    'size_id' => $orderSize,
                    'type_id' => $orderType,
                ];

                // CASE statements.
                $cases[] = "WHEN {$product->id} then ?";
                $params[] = $stock;
                $ids[] = $product->id;
            }

            $ids = implode(',', $ids);
            $cases = implode(' ', $cases);

            if (!empty($ids)) {
                DB::update("UPDATE products SET `stock` = CASE `id` {$cases} END WHERE `id` in ({$ids})", $params);
            }
        }

        // You can use a payment gateway for processing and validation here :)).
        $paymentGatewayResponse = true;
        $transactionID = now()->toDateString() . Str::random(10);
        $orderData = [
            'customer_id' => $customerID,
            'receiver_name' => $receiver_name,
            'receiver_phone_number' => $receiver_phone_number,
            'receiver_address' => $receiver_address,
            'total_price' => $totalPrice,
            'transaction_id' => $transactionID,
        ];

        if ($paymentGatewayResponse) {
            $order = $this->order->create($orderData);
            $order->products()->attach($orderProduct);
            $orderDetails = $order->load(['products']);
            $cart->delete();

            // Send email to customer.
            if ($order) {
                $user->notify(new OrderSuccessfully($orderDetails));
            }

            $message = 'Đặt hàng thành công.';

            return $this->successReponse(new OrderResource($orderDetails), $message);
        }
    }
}

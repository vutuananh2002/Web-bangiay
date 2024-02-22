<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\Admin\CustomerCollection;
use App\Http\Resources\Admin\CustomerResource;
use App\Models\Customer;
use App\Traits\ApiResponser;
use App\Traits\CookieAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    use ApiResponser, CookieAuthentication;

    protected $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer->with(['user']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Customer::class);

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $data = [
            'success' => true,
            'message' => 'Lấy danh sách khách hàng thành công.',
        ];

        $customers = $this->customer->paginate(10);

        return (new CustomerCollection($customers))->additional($data)->response();
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Customer $customer)
    {
        $this->authorize('view', $customer);

        $user = Auth::user();

        $customer = $this->customer->findOrFail($customer->id);

        $responseData = new CustomerResource($customer);
        $message = 'Lấy thông tin khách hàng thành công.';

        return $this->successReponse($responseData, $message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Customer\UpdateCustomerRequest  $request
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $user = Auth::user();

        if ($user->isAdministrator()) {
            $customer = $this->customer->findOrFail($customer->id);
        } else {
            $customer = $this->customer->where('user_id', $user->id);
        }

        $customer->update($request->validated());

        $responseData = null;
        $message = 'Cập nhật thông tin khách hàng thành công.';

        return $this->successReponse($responseData, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Customer $customer)
    {
        $this->authorize('delete', $customer);

        $user = Auth::user();

        if ($user->isAdministrator()) {
            $customer = $this->customer->findOrFail($customer->id);
        } else {
            $customer = $this->customer->where('user_id', $user->id)->first();
        }

        $customer->user->tokens()->delete();
        $customer->user->delete();
        $customer->deleteOrFail();

        $responseData = null;
        $message = 'Xóa thông tin khách hàng thành công.';

        return $this->successReponse($responseData, $message);
    }
}

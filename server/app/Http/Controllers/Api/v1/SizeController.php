<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreSizeRequest;
use App\Http\Requests\Product\UpdateSizeRequest;
use App\Http\Resources\Product\SizeCollection;
use App\Http\Resources\Product\SizeResource;
use App\Models\Size;
use App\Traits\ApiResponser;

class SizeController extends Controller
{
    use ApiResponser;

    protected $size;

    public function __construct(Size $size)
    {
        $this->size = $size;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes = $this->size->all();
        $message = 'Lấy thành công danh sách size sản phẩm.';

        return $this->successReponse(SizeResource::collection($sizes), $message);
    }

    public function withPagination()
    {
        $data = [
            'success' => true,
            'message' => 'Lấy danh sách size sản phẩm thành công.',
        ];

        $sizes = $this->size->withCount(['products' => function ($query) {
            $query->withFilters();
        }])->paginate(10);

        return (new SizeCollection($sizes))->additional($data)->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Product\StoreSizeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSizeRequest $request)
    {
        $this->authorize('create', Size::class);

        $size = $this->size->create($request->validated());

        $message = 'Thêm thành công size sản phẩm.';

        return $this->successCreatedResponse(new SizeResource($size), $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
        $size = new SizeResource($this->size->findOrFail($size->id));
        $message = 'Lấy thành công thông tin size sản phẩm.';

        return $this->successReponse($size, $message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Product\UpdateSizeRequest  $request
     * @param  \App\Models\Size
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSizeRequest $request, Size $size)
    {
        $this->authorize('update', $size);

        $size = $this->size->findOrFail($size->id);
        $size->update($request->validated());

        $message = 'Cập nhật thành công thông tin size sản phẩm.';

        return $this->successReponse(new SizeResource($size), $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Size
     * @return \Illuminate\Http\Response
     */
    public function destroy(Size $size)
    {
        $this->authorize('delete', $size);

        $this->size->findOrFail($size->id)->deleteOrFail();

        $responseData = null;
        $message = 'Xóa thành công size sản phẩm.';

        return $this->successReponse($responseData, $message);
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreColorRequest;
use App\Http\Requests\Product\UpdateColorRequest;
use App\Http\Resources\Product\ColorCollection;
use App\Http\Resources\Product\ColorResource;
use App\Models\Color;
use App\Traits\ApiResponser;

class ColorController extends Controller
{
    use ApiResponser;

    protected $color;

    public function __construct(Color $color)
    {
        $this->color = $color;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = $this->color->all();
        $message = 'Lấy danh sách màu sản phẩm thành công.';

        return $this->successReponse(ColorResource::collection($colors), $message);
    }

    public function withPagination()
    {
        $data = [
            'success' => true,
            'message' => 'Lấy danh sách màu sản phẩm thành công.',
        ];

        $colors = $this->color->withCount(['products' => function ($query) {
            $query->withFilters();
        }])->paginate(10);

        return (new ColorCollection($colors))->additional($data)->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Product\StoreColorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreColorRequest $request)
    {
        $this->authorize('create', Color::class);

        $color = $this->color->create($request->validated());

        $message = 'Thêm màu sản phẩm thành công.';

        return $this->successCreatedResponse(new ColorResource($color), $message);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Color $color
     * @return \Illuminate\Http\Response
     */
    public function show(Color $color)
    {
        $color = new ColorResource($this->color->findOrFail($color->id));
        $message = 'Lấy thông tin màu sản phẩm thành công.';

        return $this->successReponse($color, $message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Product\UpdateColorRequest  $request
     * @param  \App\Models\Color $color
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateColorRequest $request, Color $color)
    {
        $this->authorize('update', $color);

        $color = $this->color->findOrFail($color->id);
        $color->update($request->validated());

        $message = 'Cập nhật màu sắc sản phẩm thành công.';

        return $this->successReponse(new ColorResource($color), $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Color $color
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color $color)
    {
        $this->authorize('delete', $color);

        $this->color->findOrFail($color->id)->deleteOrFail();

        $responseData = null;
        $message = 'Xóa màu sắc sản phẩm thành công.';

        return $this->successReponse($responseData, $message);
    }

    public function search($name) {
        $data = [
            'status' => 'success',
            'message' => 'Lấy thành công danh sách màu sắc có tên: '.$name,
        ];

        $colors = $this->color->where('color', 'like', '%'.$name.'%')->paginate(10);

        return (new ColorCollection($colors))->additional($data)->response();
    }
}

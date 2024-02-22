<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Traits\ApiResponser;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponser;
    
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }
    /**
     * Display a listing of the category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = $this->category->search()->withCount(['products' => function ($query) {
            $query->withFilters();
        }])->get();

        $message = 'Lấy danh sách danh mục sản phẩm thành công.';

        return $this->successReponse(CategoryResource::collection($categories), $message);
    }

    public function withPagination(Request $request)
    {
        $data = [
            'status' => 'success',
            'message' => 'Lấy thành công toàn bộ danh mục sản phẩm'
        ];

        $categories = $this->category->search()->withCount(['products' => function ($query) {
            $query->withFilters();
        }])->paginate(10)->appends($request->query());

        return (new CategoryCollection($categories))->additional($data)->response();
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \App\Http\Requests\Category\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        $this->category->create($request->validated());

        $responseData = null;
        $message = 'Thêm danh mục sản phẩm thành công.';

        return $this->successCreatedResponse($responseData, $message);
    }

    /**
     * Display the specified category.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category = new CategoryResource($this->category->findOrFail($category->id));
        $message = 'Lấy thành công danh mục sản phẩm.';

        return $this->successReponse($category, $message);
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \App\Http\Requests\Category\UpdateCategoryRequest  $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        $category = $this->category->findOrFail($category->id);
        $category->update($request->validated());

        $message = 'Cập nhật thành công danh mục sản phẩm.';

        return $this->successReponse(new CategoryResource($category), $message);
    }

    /**
     * Remove the specified category from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $this->category->findOrFail($category->id)->deleteOrFail();

        $responseData = null;
        $message = 'Xóa thành công danh mục sản phẩm.';

        return $this->successReponse($responseData, $message);
    }

    public function search($name) {
        $data = [
            'status' => 'success',
            'message' => 'Lấy thành công danh mục sản phẩm có tên là: '.$name,
        ];

        $categories = $this->category->where('name', 'like', '%'.$name.'%')->paginate(10);

        return (new CategoryCollection($categories))->additional($data)->response();
    }
}

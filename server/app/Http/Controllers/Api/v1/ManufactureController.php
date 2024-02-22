<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manufacture\StoreLogoRequest;
use App\Http\Requests\Manufacture\StoreManufactureRequest;
use App\Http\Requests\Manufacture\UpdateManufactureRequest;
use App\Http\Resources\Image\ImageResource;
use App\Http\Resources\Manufacture\ManufactureCollection;
use App\Http\Resources\Manufacture\ManufactureResource;
use App\Models\Manufacture;
use App\Traits\ApiResponser;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ManufactureController extends Controller
{
    use ApiResponser;
    
    protected $manufacture;
    protected $imageService;

    public function __construct(Manufacture $manufacture, ImageService $imageService)
    {
        $this->manufacture = $manufacture;
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the manufacture.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $manufactures = $this->manufacture->search($request)->get();
        $message = 'Lấy thành công danh sách nhà sản xuất.';

        return $this->successReponse(ManufactureResource::collection($manufactures), $message);
    }

    public function withPagination(Request $request)
    {
        $data = [
            'status' => 'success',
            'message' => 'Lấy thành công danh sách nhà sản xuất.',
        ];

        $manufactures = $this->manufacture->search()->withCount(['products' => function ($query) {
            $query->withFilters();
        }])->paginate(10)->appends($request->query());

        return (new ManufactureCollection($manufactures))->additional($data)->response();
    }

    /**
     * Store a newly created manufacture in storage.
     *
     * @param  \App\Http\Requests\Manufacture\StoreManufactureRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreManufactureRequest $request)
    {
        $this->authorize('create', Manufacture::class);

        $validated = $request->validated();
        $manufacture = $this->manufacture->create($request->safe()->except('logo'));
        $logo = $this->imageService->handleUploadedImage($validated['logo'], 'manufacture', $validated['slug']);
        $manufacture->image()->create($logo);

        $message = 'Thêm nhà sản xuất thành công.';

        return $this->successCreatedResponse(new ManufactureResource($manufacture), $message);
    }

    /**
     * Display the specified manufacture.
     *
     * @param  \App\Models\Manufacture $manufacture
     * @return \Illuminate\Http\Response
     */
    public function show(Manufacture $manufacture)
    {
        $manufacture = $this->manufacture->findOrFail($manufacture->id);
        $message = 'Lấy thông tin nhà sản xuất thành công.';

        return $this->successReponse(new ManufactureResource($manufacture), $message);
    }

    /**
     * Update the specified manufacture in storage.
     *
     * @param  \App\Http\Requests\Manufacture\UpdateManufactureRequest  $request
     * @param  \App\Models\Manufacture $manufacture
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateManufactureRequest $request, Manufacture $manufacture)
    {
        $this->authorize('update', $manufacture);

        $manufacture = $this->manufacture->findOrFail($manufacture->id);
        $manufacture->update($request->validated());

        $message = 'Cập nhật nhà sản xuất thành công.';

        return $this->successReponse(new ManufactureResource($manufacture), $message);
    }

    /**
     * Remove the specified manufacture from storage.
     *
     * @param  \App\Models\Manufacture $manufacture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manufacture $manufacture)
    {
        $this->authorize('delete', $manufacture);

        $this->manufacture->findOrFail($manufacture->id)->deleteOrFail();

        $responseData = null;
        $message = 'Xóa nhà sản xuất thành công.';

        return $this->successReponse($responseData, $message);
    }

    public function addLogo(StoreLogoRequest $request, Manufacture $manufacture) {
        $this->authorize('manageLogo', $manufacture);

        $validated = $request->validated();
        $manufacture = $this->manufacture->findOrFail($manufacture->id);
        $logo = $this->imageService->handleUploadedImage($validated['logo'], 'manufacture', $manufacture->slug);
        $manufacture->image()->delete();
        $logo = $manufacture->image()->create($logo);

        $message = 'Thêm logo nhà sản xuất thành công.';

        return $this->successCreatedResponse(new ImageResource($logo), $message);
    }

    public function deleteLogo(Manufacture $manufacture) {
        $this->authorize('manageLogo', $manufacture);
        
        $manufacture = $this->manufacture->findOrFail($manufacture->id);
        $manufacture->image()->delete();

        $responseData = null;
        $message = 'Xóa logo nhà sản xuất thành công.';

        return $this->successReponse($responseData, $message);
    }
}

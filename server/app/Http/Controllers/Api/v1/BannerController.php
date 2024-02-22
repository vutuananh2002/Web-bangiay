<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Services\ImageService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use ApiResponser;

    protected $banner;
    protected $imageService;

    public function __construct(Banner $banner, ImageService $imageService)
    {
        $this->banner = $banner;
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the banner.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = $this->banner->all();
        $message = 'Lấy danh sách banner thành công.';

        return $this->successReponse(BannerResource::collection($banners), $message);
    }

    /**
     * Store a newly created banner in storage.
     *
     * @param  \App\Http\Requests\StoreBannerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBannerRequest $request)
    {
        $this->authorize('create', Banner::class);

        $validated = $request->validated();
        $image = $this->imageService->handleUploadedImage($validated['image'], 'banners', $validated['title']);
        $validated['image'] = $image['url'];
        $banner = $this->banner->create($validated);

        $message = 'Tạo banner mới thành công.';

        return $this->successCreatedResponse(new BannerResource($banner), $message);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Banner $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        $this->authorize('view', $banner);

        $banner = $this->banner->findOrFail($banner->id);
        $message = 'Lấy thông tin banner thành công.';

        return $this->successReponse(new BannerResource($banner), $message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBannerRequest  $request
     * @param  \App\Models\Banner
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $this->authorize('update', $banner);

        $validated = $request->validated();
        $banner = $this->banner->findOrFail($banner->id);
        $image = $this->imageService->handleUploadedImage($validated['image'], 'banners', $validated['title']);
        $validated['image'] = $image['url'];
        $banner->update($validated);

        $message = 'Cập nhật banner thành công.';

        return $this->successReponse(new BannerResource($banner), $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        $this->authorize('delete', $banner);

        $this->banner->findOrFail($banner->id)->deleteOrFail();

        return $this->successReponse(null, 'Xóa banner thành công.');
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Http\Resources\ReviewCollection;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponser;

    protected $review;

    public function __construct(Review $review)
    {
        $this->review = $review->with(['product', 'customer']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'success' => true,
            'message' => 'Lấy thành công danh sách đánh giá sản phẩm.',
        ];

        $reviews = $this->review->paginate(10);

        return (new ReviewCollection($reviews))->additional($data)->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Review\StoreReviewRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReviewRequest $request)
    {
        $this->authorize('create', Review::class);

        $review = $this->review->create($request->validated());

        $message = 'Cảm ơn bạn đã đánh giá.';

        return $this->successCreatedResponse(new ReviewResource($review), $message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Review\UpdateReviewRequest  $request
     * @param  \App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);

        $this->review->findOrFail($review->id)->updateOrFail($request->validated());

        $responseData = null;
        $message = 'Cập nhật đánh giá thành công.';

        return $this->successReponse($responseData, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $this->review->findOrFail($review->id)->deleteOrFail();

        $responseData = null;
        $message = 'Xóa đánh giá thành công.';

        return $this->successReponse($responseData, $message);
    }

    public function deleteAll() {
        $this->authorize('delete', Review::class);

        $this->review->truncate();

        $responseData = null;
        $message = 'Xóa thành công toàn bộ đánh giá.';

        return $this->successReponse($responseData, $message);
    }

    public function deleteMany(Request $request) {
        $this->authorize('delete', Review::class);

        $ids = $request->ids;
        $reviews = $this->review->whereIn('id', $ids);
        $noReviews = $reviews->get()->count();
        $reviews->delete();

        $responseData = null;
        $message = 'Xóa thành công ' . $noReviews . ' đánh giá.';

        return $this->successReponse($responseData, $message);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
{
    private $reviewService;
    public function __construct(ReviewService $reviewService){
        $this->reviewService = $reviewService;
    }

    public function index()
    {
        if (auth()->user()->can('review-view'))
        {
            $reviews = Review::with('user','product','productTranslation','productTranslationEnglish')->get();

            if (request()->ajax())
            {
                return datatables()->of($reviews)
                ->setRowId(function ($row){
                    return $row->id;
                })
                ->addColumn('product_name', function ($row)
                {
                    return $row->productTranslation->product_name ?? $row->productTranslationEnglish->product_name ?? null ;
                })
                ->addColumn('reviewer_name', function ($row)
                {
                    return $row->user->first_name.' '.$row->user->last_name ?? null ;
                })
                ->addColumn('action', function ($row)
                {
                    $actionBtn = "";
                    if (auth()->user()->can('review-edit')){
                        $actionBtn .= '<button type="button" title="Edit" class="edit btn btn-info btn-sm" title="Edit" data-id="'.$row->id.'"><i class="dripicons-pencil"></i></button>
                        &nbsp; ';
                    }

                    if (auth()->user()->can('review-action')){
                        $actionBtn .= '<button type="button" title="Delete" class="delete btn btn-danger btn-sm ml-2" data-id="'.$row->id.'"><i class="dripicons-trash"></i></button>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
            }
            return view('admin.pages.review.index');
        }
        return abort('403', __('You are not authorized'));
    }

    public function edit(Request $request)
    {
        if (request()->ajax()) {
           $review = Review::find($request->rowId);
           return response()->json(['review'=>$review]);
        }
    }

    public function update(Request $request)
    {
        if (auth()->user()->can('review-edit')){
            if (request()->ajax()) {
                $review = Review::find($request->review_id);
                $review->rating = $request->rating;
                $review->comment = $request->comment;
                $review->status = $request->status;
                $review->update();


                $product_review = Review::where('product_id',$review->product_id)
                            ->where('status','approved')
                            ->select(DB::raw('count(*) as product_count, sum(rating) as product_rating'))
                            ->first();
                $product_avg_rating = $product_review->product_rating / $product_review->product_count;
                $product_avg_rating = number_format((float)$product_avg_rating, 2, '.', '');

                $product = Product::find($review->product_id);
                $product->avg_rating = $product_avg_rating;
                $product->update();

                return response()->json(['success'=>'Updated Successfully']);
            }
        }
        return abort('403', __('You are not authorized'));
    }


    public function delete(Request $request){
        return $this->reviewService->destroy($request->id);
    }
}

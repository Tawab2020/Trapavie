<?php

namespace App\Http\Controllers\Admin\Retreat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Venue\VenueDataCategoryRequest;
use App\Http\Requests\RetreatCategoryRequest;
use App\Http\Requests\RetreatRequest;
use App\Models\Retreat;
use App\Models\RetreatCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RetreatController extends Controller
{

    public function index(Request  $request)
    {
        $cat = RetreatCategory::leftJoin("retreats",'category_id','retreat_categories.id')
            ->select("retreat_categories.title as category_title",'retreat_categories.id as cat_id','retreats.*');
        if ($request->has('status') and $request->get('status')){
            $cat = $cat->where('retreats.status',$request->get('status'));
        }

        if($request->has('date') and $request->get("date")){
            $cat = $cat->whereDate("retreats.created_at",$request->get('date'));
        }

        return response()->json($cat->orderBy("category_id",'asc')->get());
    }
    public function bareCategories(){
        return response()->json(RetreatCategory::get(["id",'title']));
    }

    public function storeCategory(RetreatCategoryRequest $request){
        $data = ["title"=>$request->get('title')];
        if ($request->hasFile('icon')){
            $data['icon'] = $request->file('icon')->store('public');
        }
        return response()->json(RetreatCategory::create($data));
    }

    public function changeStatus(Request $request,$id){
        $r = Retreat::find($id);
        $r->update(['status'=>$request->get('status')]);
        return response()->json($r->refresh);
    }

    public function showCategory($id)
    {
        $cate = RetreatCategory::find($id);
        return response()->json($cate);
    }

    public function updateCategory(RetreatCategoryRequest $request, $id)
    {
        $cat = RetreatCategory::find($id);
        $data = ['title' => $request->get('title')];
        if ($request->hasFile('icon')) {
            if (Storage::exists($cat->icon)) {
                Storage::delete($cat->icon);
            }
            $data['icon'] = $request->file('icon')->store('public');

        }
        $cat->update($data);
        $cat->refresh();
        return response()->json($cat);
    }

    public function store(RetreatRequest $request)
    {
        $cat = RetreatCategory::find($request->category_id);
        $path = null;
        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('public');
        }
        return response()->json($cat->retreats()->create(['title' => $request->get('title'), 'path' => $path]));
    }


    public function show($id)
    {
        $retreat = Retreat::find($id);
        return response()->json($retreat);
    }


    public function updateRetreat(RetreatCategoryRequest $request, $id)
    {
        $retreat = Retreat::find($id);
        $data = [
            'title' => $request->get('title'),
            'category_id'=>$request->get('category_id')
        ];
        if ($request->hasFile('icon')) {
            if (Storage::exists($retreat->icon)) {
                Storage::delete($retreat->icon);
            }
            $data['icon'] = $request->file('icon')->store('public');
        }

        $retreat->update($data);
        $retreat->refresh();
        return response()->json($retreat);
    }


    public function destroy($id)
    {
        $ret = Retreat::find($id);
        if(Storage::exists($ret->icon)){
            Storage::delete($ret->icon);
        }

        return response()->json($ret->delete());
    }
}

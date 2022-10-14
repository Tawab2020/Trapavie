<?php

namespace App\Http\Controllers\Admin\Venue;

use App\Http\Controllers\Controller;
use App\Models\Venue\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    private $mvenue;
    public function __construct(Venue $venue)
    {
        $this->mvenue= $venue;
    }

    public function index(Request $request)
    {
        $status = $request->has("status") and $request->get('status')? $request->get('status'): 0;
        $venues =  $this->mvenue;
        if ($request->has('country') and $request->get('country')){
            $venues = $venues->where("country","LIKE","%".$request->get("country")."%");
        }

        if($request->has('property_type_id') and $request->get('property_type_id')){
            $venues = $venues->where('property_type_id',$request->get('property_type_id'));
        }
        if($request->has('price') and $request->get('price')){
            $venues = $venues->where('price_per_night',$request->get('price'));
        }
        if($status){
            $venues = $venues->where('status',$status);
        }


        return response()->json($venues->orderBy('updated_at', 'DESC')->with(['bookings'=>function($q){
            return $q->get();
        }])->paginate(10));
    }

    public function pendingVenues()
    {
        $venues = Venue::where('status', 1)->orderBy('updated_at', 'DESC')->paginate(10);
        return response()->json($venues, 200);
    }

    public function liveVenues()
    {
        $venues = Venue::where('status', 2)->orderBy('updated_at', 'DESC')->paginate(10);
        return response()->json($venues, 200);
    }

    public function rejectedVenues()
    {
        $venues = Venue::where('status', 3)->orderBy('updated_at', 'DESC')->paginate(10);
        return response()->json($venues, 200);
    }

    public function approve(Venue $venue)
    {
        $venue->status = 2;
        $venue->save();
        return response()->json(["message" => "Venue was published succesfully"], 200);
    }
    public function reject(Venue $venue)
    {
        $venue->status = 3;
        $venue->save();
        return response()->json(["message" => "Venue was rejected succesfully"], 200);
    }
    public function unApprove(Venue $venue)
    {
        $venue->status = 1;
        $venue->save();
        return response()->json(["message" => "Venue was unapproved succesfully"], 200);
    }

    public function fetchBookings($id){
        $venue = $this->mvenue->find($id);
        return response()->json($venue->load('bookings'));
    }
}

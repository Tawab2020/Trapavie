<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $mUser;

    public function __construct(User $user)
    {
        $this->mUser = $user;
    }

    public function index(Request $request)
    {
        $user = $this->mUser->leftJoin("user_details", 'users.id', 'user_id')->select("users.*", 'user_details.country',
            'user_details.verified_at', 'user_details.id_document', 'user_details.bio', 'user_details.dob',
            'user_details.street', 'user_details.apt', 'user_details.city', 'user_details.state', 'user_details.zip', 'user_details.currency');
        if ($request->has('type') and $request->get('type')) {
            $user = $user->where('type', $request->get('type'));
        }

        if ($request->has('fname') and $request->get('fname')) {
            $user = $user->where("fname", "LIKE", '%' . $request->get('fname') . "%");
        }

        if ($request->has("lname") and $request->get("lname")) {
            $user = $user->where("lname", "LIKE", '%' . $request->get('lname') . "%");
        }

        if ($request->has('country') and $request->get("country")) {
            $user = $user->where('country', "LIKE", "%" . $request->get('country') . "%");
        }

        if ($request->has('to') && $request->get('to')) {
            $user = $user->whereDate("users.created_at", "<=", $request->get('to'));

        }

        if ($request->has('from') && $request->get('from')) {
            $user = $user->whereDate("users.created_at", ">=", $request->get('from'));
        }


        return response()->json($user->paginate(10));
    }


    public function blockUnblock($id)
    {
        $user = $this->mUser->find($id);
        $isBlock = 1;
        if ($user->is_block) {
            $isBlock = 0;
        }
        $user->update(['is_block' => $isBlock]);
        return response()->json($user);
    }


    public function verifyId($id)
    {
        $user = $this->mUser->find($id);

        $date = Carbon::now();
        $user->userDetail()->update(['verified_at' => $date]);
        return response()->json($date);
    }


}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamMemberRequest;
use App\Models\TeamMember;
use Illuminate\http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::all();
        return response()->json($teamMembers, 200);
    }

    public function store(TeamMemberRequest $request)
    {
        $teamMember_data = $request->validated();
        $teamMember_data["image"] = null;
        $teamMember = TeamMember::create($teamMember_data);
        return response()->json($teamMember, 200);
    }

    public function show(TeamMember $teamMember)
    {
        return response()->json($teamMember, 200);
    }

    public function update(TeamMemberRequest $request, TeamMember $teamMember)
    {
        $teamMember_data = $request->validated();
        $teamMember->update($teamMember_data);
        return response()->json($teamMember, 200);
    }

    public function destroy(TeamMember $teamMember)
    {
        $teamMember->delete();
        return response()->json("Team Member Deleted", 200);
    }

    public function uploadImage(Request $request, TeamMember $teamMember)
    {
        if (request()->hasFile('image')) {
            $pre_image = TeamMember::find($teamMember->id)->image;
            if ($pre_image) {
                Storage::delete('public/team/' . $pre_image);
            }

            $image_path = request()->file('image')->store('public/team');
            $teamMember->update([
                'image' => basename($image_path)
            ]);

            return response()->json($teamMember, 200);
        } else {
            return response()->json(['result' => 'failed'], 201);
        }
    }
}

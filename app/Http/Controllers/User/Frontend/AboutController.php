<?php

namespace App\Http\Controllers\User\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\FaqCat;
use App\Models\TeamMember;

class AboutController extends Controller
{
    public function index()
    {
        $faqCats = FaqCat::with(['parent', 'children.faqs'])->get();
        $teamMembers = TeamMember::all();
        return response()->json(['faqCats' => $faqCats, 'teamMembers' => $teamMembers], 200);
    }
}

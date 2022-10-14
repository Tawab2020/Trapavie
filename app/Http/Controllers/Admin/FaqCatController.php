<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqCatRequest;
use App\Models\FaqCat;

class FaqCatController extends Controller
{
    public function index()
    {
        $faqCats = FaqCat::with(['parent', 'children.faqs'])->get();
        return response()->json($faqCats, 200);
    }

    public function store(FaqCatRequest $request)
    {
        $faqCat_data = $request->validated();
        $faqCat = FaqCat::create($faqCat_data);
        return response()->json($faqCat, 200);
    }

    public function show(FaqCat $faqCat)
    {
        $faqCat->load('faqs');
        return response()->json($faqCat, 200);
    }

    public function update(FaqCatRequest $request, FaqCat $faqCat)
    {
        $faqCat_data = $request->validated();
        $faqCat->update($faqCat_data);
        return response()->json($faqCat, 200);
    }

    public function destroy(FaqCat $faqCat)
    {
        // delete questions too when done
        $children = FaqCat::where('parent_id', $faqCat->id)->get();
        foreach ($children as $child) {
            // Delete all the questions
            $child->load('faqs');
            foreach ($child->faqs as $faq) {
                $faq->delete();
            }
            $child->delete();
        }
        $faqCat->delete();
        return response()->json([
            'message' => 'FAQ Category, and children Deleted Successfully.'
        ], 200);
    }
}

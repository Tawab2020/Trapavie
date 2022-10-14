<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::all();
        return response()->json($faqs, 200);
    }

    public function store(FaqRequest $request)
    {
        $faq_data = $request->validated();
        $faq = Faq::create($faq_data);
        return response()->json($faq, 200);
    }

    public function show(Faq $faq)
    {
        return response()->json($faq, 200);
    }

    public function update(FaqRequest $request, Faq $faq)
    {
        $faq_data = $request->validated();
        $faq->update($faq_data);
        return response()->json($faq, 200);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json([
            'message' => 'FAQ Was Deleted Successfully.'
        ], 200);
    }
}

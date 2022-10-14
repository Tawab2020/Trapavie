<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Models\Language;
use Illuminate\Support\Facades\Storage;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::all();
        return response()->json($languages, 200);
    }

    public function store(LanguageRequest $request)
    {
        $language_data = $request->validated();
        $language_data["icon"] = null;
        $language = Language::create($language_data);
        // Make the translation file and folder
        $folder = base_path() . '/resources/lang/' . $language->code;
        if (!is_dir($folder)) {
            mkdir($folder);
        }
        $path = base_path() . '/resources/lang/' . $language->code . '/text.php';
        $content = "<?php\n\nreturn [\n\"home\" => \"Home $language->code\",];";
        file_put_contents($path, $content);
        return response()->json($language, 200);
    }

    public function show(Language $language)
    {
        return response()->json($language, 200);
    }

    public function update(LanguageRequest $request, Language $language)
    {
        $language_data = $request->validated();
        $language->update($language_data);
        return response()->json($language, 200);
    }

    public function deactivate(Language $language)
    {
        $language->update(['is_active' => 0]);
        $language->save();
        return response()->json("Language Deactivated", 200);
    }

    public function activate(Language $language)
    {
        $language->update(['is_active' => 1]);
        $language->save();
        return response()->json("Language Activated", 200);
    }

    public function uploadIcon(Language $language)
    {
        if (request()->hasFile('icon')) {
            $pre_icon = $language->icon;
            if ($pre_icon) {
                Storage::delete('public/language/' . $pre_icon);
            }

            $icon_path = request()->file('icon')->store('public/languages');
            $language->update([
                'icon' => basename($icon_path)
            ]);

            return response()->json($language, 200);
        } else {
            return response()->json(['result' => 'failed'], 201);
        }
    }
}

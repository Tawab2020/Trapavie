<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class TranslationController extends Controller
{
    private $lang = '';
    private $file = "text"; //the file name in each lang folder
    private $key;
    private $value;
    private $path;
    private $arrayLang = array();


    // Load translations
    public function loadTranslations($lang)
    {
        // $en = trans();
        $en = trans('text', [], 'en');
        $text = trans('text', [], $lang);
        return response()->json([
            'en' => $en,
            'language' => $text
        ], 200);
    }


    //------------------------------------------------------------------------------
    // Add or modify lang files content
    //------------------------------------------------------------------------------

    public function changeLangFileContent($lang, Request $request)
    {
        $translations = $request->all();
        $this->read($lang);
        foreach ($translations as $key => $value) {
            $this->arrayLang[$key] =  $value;
            $this->save($key, $value);
        }
    }

    //------------------------------------------------------------------------------
    // Delete from lang files
    //------------------------------------------------------------------------------

    // private function deleteLangFileContent()
    // {
    //     $this->read();
    //     unset($this->arrayLang[$this->key]);
    //     $this->save();
    // }

    //------------------------------------------------------------------------------
    // Read lang file content
    //------------------------------------------------------------------------------

    private function read($lang)
    {
        $this->lang = $lang;
        $this->path = base_path() . '/resources/lang/' . $this->lang . '/' . $this->file . '.php';
        $this->arrayLang = Lang::get($this->file, [], $lang);

        if (gettype($this->arrayLang) == 'string') $this->arrayLang = array();
    }

    //------------------------------------------------------------------------------
    // Save lang file content
    //------------------------------------------------------------------------------

    private function save($key, $value)
    {
        $content = "<?php\n\nreturn [\n";

        foreach ($this->arrayLang as $key => $value) {
            $value = str_replace('"', '\"', $value);
            $content .= "\t\"" . $key . '" => "' . $value . "\",\n";
        }

        $content .= "];";

        file_put_contents($this->path, $content);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class TeamMember extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $translatable = ['name', 'title', 'bio'];


    public function deleteImage()
    {
        if ($this->image) {
            Storage::delete("public/team/" . $this->image);
        }
    }
    public function delete()
    {
        $this->deleteImage();
        parent::delete();
    }
}

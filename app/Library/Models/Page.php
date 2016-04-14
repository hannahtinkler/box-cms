<?php

namespace App\Library\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public $guarded = [];
    
    public function chapter()
    {
        return $this->belongsTo('App\Library\Models\Chapter');
    }
    
    public function bookmarks()
    {
        return $this->hasOne('App\Library\Models\Bookmark');
    }
}

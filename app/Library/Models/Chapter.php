<?php

namespace App\Library\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    public $guarded = [];
    
    public function pages()
    {
        return $this->hasMany('App\Library\Models\Page')->orderBy('order');
    }
    
    public function bookmarks()
    {
        return $this->hasOne('App\Library\Models\Bookmark');
    }
}

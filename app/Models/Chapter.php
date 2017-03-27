<?php

namespace App\Models;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

use App\Repositories\ChapterRepository;

class Chapter extends Model
{
    use ElasticquentTrait;
    
    public $guarded = [];
    private $repository;
    protected $mappingProperties = array(
        'title' => [
          'type' => 'string',
          "analyzer" => "standard",
        ],
        'description' => [
          'type' => 'string',
          "analyzer" => "standard",
        ]
    );

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->repository = new ChapterRepository($this);
    }

    public function __get($name)
    {
        $repository = new ChapterRepository($this);

        if (method_exists($repository, $name)) {
            return $repository->$name();
        }

        return parent::__get($name);
    }
    
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    
    public function pages()
    {
        return $this->hasMany('App\Models\Page')->where('approved', 1)->orderBy('title')->orderBy('order');
    }
    
    public function bookmark()
    {
        return $this->hasOne('App\Models\Bookmark');
    }
    
    public function approvedPages()
    {
        return $this->hasMany('App\Models\Page')->where('approved', true)->orderBy('order');
    }
    
    public function scopeLargestOrderValue($query, $categoryId)
    {
        return $query->where('category_id', $categoryId)->orderBy('order', 'desc')->first();
    }
}

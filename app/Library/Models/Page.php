<?php

namespace App\Library\Models;

use App\Library\Interfaces\SearchableModel;
use App\Library\Repositories\PageRepository;
use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;

class Page extends Model implements SearchableModel
{
    use ElasticquentTrait;

    public $guarded = [];
    private $repository;
    protected $mappingProperties = array(
        'title' => [
          'type' => 'string',
          "analyzer" => "standard",
        ],
        'content' => [
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
        $this->repository = new PageRepository($this);
    }

    // public function getTypeName()
    // {
    //     return 'page';
    // }
    
    public function chapter()
    {
        return $this->belongsTo('App\Library\Models\Chapter');
    }
    
    public function bookmarks()
    {
        return $this->hasOne('App\Library\Models\Bookmark');
    }
    
    public function searchResultString()
    {
        return $this->repository->searchResultString($this);
    }
    
    public function searchResultUrl()
    {
        return $this->repository->searchResultUrl($this);
    }
}

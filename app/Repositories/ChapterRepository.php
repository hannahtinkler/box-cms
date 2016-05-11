<?php

namespace App\Repositories;

use App\Interfaces\SearchableRepository;
use App\Models\Chapter;

class ChapterRepository implements SearchableRepository
{
    public function getSearchResults($term)
    {
        $query = [
            "bool" => [
                "should" => [
                    [ "wildcard" => [ "_all" => "*$term*"]],
                    [ "match" => [ "_all" => "$term" ]]
                ]
            ]
        ];

        return Chapter::searchByQuery($query);
    }

    public function searchResultString($result)
    {
        return 'Chapter: ' . $result->title . ' - ' . substr($result->description, 0, 60) . '...';
    }

    public function searchResultUrl($result)
    {
        return '/p/' . $result->category->slug . '/' . $result->slug;
    }

    public function searchResultIcon($result)
    {
        return '<i class="fa fa-folder-open-o"></i>';
    }
}
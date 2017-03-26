<?php

namespace App\Services;

use App;
use App\Models\User;

use cogpowered\FineDiff\Diff;
use cogpowered\FineDiff\Granularity\Sentence;

class CurationService
{
    public function getPageDiff($original, $new)
    {
        $granularity =  new Sentence;
        $differ = new Diff($granularity);

        $converter = App::make('unsafe-markdown');

        $diff = [
            'category' => $differ->render($original->chapter->category->title, $new->chapter->category->title),
            'chapter' => $differ->render($original->chapter->title, $new->chapter->title),
            'title' => $differ->render($original->title, $new->title),
            'description' => $differ->render($original->description, $new->description),
            'content' => $converter->convertToHtml($differ->render($original->content, $new->content)),
        ];

        return $diff;
    }
}
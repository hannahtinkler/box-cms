<?php

namespace App\Repositories;

use App\Models\Page;
use App\Models\Badge;
use App\Models\FeedEvent;

class FeedEventRepository
{
    /**
     * @var FeedEvent
     */
    private $feedEvent;

    /**
     * @param FeedEvent $feedEvent
     */
    public function __construct(FeedEvent $feedEvent)
    {
        $this->feedEvent = $feedEvent;
    }

    public function text()
    {
        $type = $this->feedEvent->type->name;

        switch ($type) {
            case 'Page Added':
                $page = Page::find($this->feedEvent->resource_id);
                $text = sprintf(
                    $this->feedEvent->type->text,
                    '<a href="/u/' . $this->feedEvent->user->slug .'">' . $this->feedEvent->user->name . '</a>',
                    '<a href="/p/' . $page->chapter->category->slug .'/' . $page->chapter->slug . '">' . $page->chapter->title . '</a>',
                    '<a href="/p/' . $page->chapter->category->slug .'/' . $page->chapter->slug . '/' . $page->slug . '">' . $page->title . '</a>'
                );
                break;

            case 'Badge Earned':
                $badge = Badge::find($this->feedEvent->resource_id);
                $text = sprintf(
                    $this->feedEvent->type->text,
                    '<a href="/u/' . $this->feedEvent->user->slug .'">' . $this->feedEvent->user->name . '</a>',
                    '<a href="/u/' . $this->feedEvent->user->slug .'/badges">' . $badge->name . '</a>'
                );
                break;

            default:
                throw new Exception("FeedEventType '$type' does not exist");
        }

        return $text;
    }

    public function image()
    {
        $type = $this->feedEvent->type->name;

        switch ($type) {
            case 'Page Added':
                $image = '<i class="feed-icon fa fa-file"></i>';
                break;

            case 'Badge Earned':
                $badge = Badge::find($this->feedEvent->resource_id);
                $image = '<img src="' . $badge->image . '" />';
                break;

            default:
                throw new Exception("FeedEventType '$type' does not exist");
        }

        return $image;
    }

    public function resourceExists()
    {
        $type = $this->feedEvent->type->name;

        switch ($type) {
            case 'Page Added':
                $resource = Page::find($this->feedEvent->resource_id);
                break;

            case 'Badge Earned':
                $resource = Badge::find($this->feedEvent->resource_id);
                break;

            default:
                throw new Exception("FeedEventType '$type' does not exist");
        }

        return is_object($resource);
    }
}

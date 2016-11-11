<?php

use App\Models\Page;
use App\Models\User;
use App\Models\PageTag;
use App\Models\Chapter;
use App\Models\Bookmark;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Tests that a call to the chapter relationship returns the chapter that
     * this page belongs to
     *
     * @return void
     */
    public function testChapterRelationshipReturnsChapter()
    {
        $page = factory(Page::class)->create();
        $this->assertTrue($page->chapter instanceof Chapter);
    }

    /**
     * Tests that a call to the bookmark relationship returns the bookmark that
     * this page belongs to
     *
     * @return void
     */
    public function testBookmarksRelationshipReturnsBookmark()
    {
        $user = $this->createAndLoginAUser();
        $page = factory(Page::class)->create();
        factory(Bookmark::class)->create(['page_id' => $page->id, 'user_id' => $user->id]);

        $this->assertTrue($page->bookmark instanceof Bookmark);
    }

    /**
     * Tests that a call to the creator relationship returns the user that
     * this page belongs to
     *
     * @return void
     */
    public function testCreatorRelationshipReturnsCreator()
    {
        $page = factory(Page::class)->create();
        $this->assertTrue($page->creator instanceof User);
    }

    /**
     * Tests that a call to the pageTags relationship returns a colleaction of
     * PageTags
     *
     * @return void
     */
    public function testPageTagsRelationshipReturnsPageTags()
    {
        $page = factory(Page::class)->create();
        factory(PageTag::class, 3)->create(['page_id' => $page->id]);

        $this->assertTrue($page->pageTags->first() instanceof PageTag);
    }
    
    /**
     * Tests that a query implementing the 'lastestUpdated' scope returns the
     * most recently updated pages first
     *
     * @return void
     */
    public function testTheLatestUpdatedQueryScope()
    {
        $timestamps = [
            'created_at' => '2020-05-01 12:43:23',
            'updated_at' => '2020-05-01 12:43:23',
            'deleted_at' => null,
            'approved' => true
        ];

        $page1 = factory(Page::class)->create($timestamps);
        $page2 = factory(Page::class)->create(
            array_merge($timestamps, ['updated_at' => '2021-05-01 12:43:23'])
        );
        $page3 = factory(Page::class)->create($timestamps);
        
        $expected = Page::find($page2->id)->toArray();
        $actual = Page::latestUpdated()->first()->toArray();

        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests that a query implementing the 'findBySlug' scope returns a record
     * with the given slug
     *
     * @return void
     */
    public function testTheFindBySlugQueryScope()
    {
        $page = factory(Page::class)->create();
        
        $expected = Page::find($page->id)->toArray();
        $actual = Page::findBySlug($page->slug)->toArray();

        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests that a query implementing the 'largestOrderValue' scope returns
     * the highest order value in the page table for a given chapter
     *
     * @return void
     */
    public function testTheLargestOrderValueQueryScope()
    {
        $chapter = factory(Chapter::class)->create();

        $overrides = [
            'chapter_id' => $chapter->id,
            'created_at' => '2020-05-01 12:43:23',
            'updated_at' => '2020-05-01 12:43:23',
            'deleted_at' => null
        ];

        $page1 = factory(Page::class)->create(array_merge($overrides, ['order' => 5]));
        $page2 = factory(Page::class)->create(array_merge($overrides, ['order' => 9999]));
        $page3 = factory(Page::class)->create(array_merge($overrides, ['order' => 15]));
        
        $expected = Page::find($page2->id)->toArray();
        $actual = Page::largestOrderValue($chapter->id)->toArray();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that a call to the method that returns the search result string
     * for a given record works as expected
     *
     * @return void
     */
    public function testSearchResultStringIsCorrect()
    {
        $page = factory(Page::class)->create();

        $expected = 'Page: ' . $page->title;
        $actual = $page->searchResultString();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that a call to the method that returns the search result url
     * for a given record works as expected
     *
     * @return void
     */
    public function testSearchResultUrlIsCorrect()
    {
        $page = factory(Page::class)->create();

        $expected = '/p/' . $page->chapter->category->slug . '/' . $page->chapter->slug . '/' . $page->slug;
        $actual = $page->searchResultUrl();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that a call to the method that returns the search result icon
     * for a given record works as expected
     *
     * @return void
     */
    public function testSearchResultIconIsCorrect()
    {
        $page = factory(Page::class)->create();

        $expected = '<i class="fa fa-file-o"></i>';
        $actual = $page->searchResultIcon();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that a call to the method that returns the search result string
     * for a given record works as expected
     *
     * @return void
     */
    public function createAndLoginAUser($overrides = [])
    {
        $user = factory(User::class)->create($overrides);
        Auth::login($user);

        return $user;
    }
}

<?php

namespace Tests\Acceptance\Controllers;

use TestCase;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var User
     */
    public $user;

    /**
     * Elasticsearch takes a while to index new items; hence the sleep :(
     * @return void
     */
    public function testItCanAccessSearchApiCall()
    {
        $this->logInAsUser();

        $page = factory('App\Models\Page')->create(['title' => 'Hello']);

        $page->addToIndex();

        sleep(1);

        $this->get('/search/' . substr($page->title, 0, 10))
            ->seeJson(["title" => 'Hello'])
            ->assertResponseStatus(200);
    }

    /**
     * @return void
     */
    public function testItCanAccessSearchResultsPage()
    {
        $this->logInAsUser();

        $this->get('/search/blah/results')
            ->see("Search Results")
            ->assertResponseStatus(200);
    }

    /**
     * @param array $overrides
     * @return void
     */
    public function logInAsUser($overrides = [])
    {
        $this->user = factory('App\Models\User')->create($overrides);
        $this->be($this->user);
    }
}

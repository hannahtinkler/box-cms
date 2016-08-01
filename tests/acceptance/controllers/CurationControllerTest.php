<?php

use App\Models\Page;
use App\Models\SuggestedEdit;
use App\Services\ModelServices\PageModelService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CurationControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The current user being worked on behalf of in the test
     * @var object User
     */
    public $user;

    /**
     * Test that a request to the route that shows a user the 'Pages Awaiting
     * Curation' Page shows the 'Show Curation' page and returns a 200
     * response code (OK)
     *
     * @return void
     */
    public function testItCanAccessPagesPendingCurationPage()
    {
        $this->logInAsUser();

        $this->get('/curation/new')
            ->see('Curation')
            ->assertResponseStatus(200);
    }

    /**
     * Test that a request to the route that approves a page awaiting curation
     * works and returns a 200 response code (OK)
     *
     * @return void
     */
    public function testItCanApproveAPagePendingCuration()
    {
        $this->logInAsUser();

        $page = factory(App\Models\Page::class)->create();

        $this->get('/curation/new/approve/' . $page->id)
            ->assertResponseStatus(302);

        $lookup = Page::find($page->id);

        $this->assertEquals(1, $lookup->approved);
    }

    public function testItCanRejectAPagePendingCuration()
    {
        $this->logInAsUser();

        $page = factory(App\Models\Page::class)->create();

        $this->get('/curation/new/reject/' . $page->id)
            ->assertResponseStatus(302);

        $lookup = Page::find($page->id);

        $this->assertEquals(0, $lookup->approved);
    }

    /**
     * Test that a request to the route that shows a user the 'Pages Awaiting
     * Curation' Page shows the 'Show Curation' page and returns a 200
     * response code (OK)
     *
     * @return void
     */
    public function testItCanAccessSuggestedEditsPendingCurationPage()
    {
        $this->logInAsUser();

        $this->get('/curation/edits')
            ->see('Curation')
            ->assertResponseStatus(200);
    }

    /**
     * Test that a request to the route that approves an edit awaiting curation
     * works and returns a 200 response code (OK)
     *
     * @return void
     */
    public function testItCanApproveASuggestedEditPendingCuration()
    {
        $this->logInAsUser();

        $page = factory(App\Models\SuggestedEdit::class)->create();

        $this->get('/curation/edits/approve/' . $page->id)
            ->assertResponseStatus(302);

        $lookup = SuggestedEdit::find($page->id);

        $this->assertEquals(1, $lookup->approved);
    }

    /**
     * Test that a request to the route that rejects an edit awaiting curation
     * works and returns a 200 response code (OK)
     *
     * @return void
     */
    public function testItCanRejectASuggestedEditPendingCuration()
    {
        $this->logInAsUser();

        $page = factory(App\Models\SuggestedEdit::class)->create();

        $this->get('/curation/edits/reject/' . $page->id)
            ->assertResponseStatus(302);

        $lookup = SuggestedEdit::find($page->id);

        $this->assertEquals(0, $lookup->approved);
    }

    /**
     * Test that a request to the route that returns a diff of the original page
     * and the edit returns the required html and a 200 response (OK)
     *
     * @return void
     */
    public function testItCanViewDiffForSuggestedEdit()
    {
        $this->logInAsUser();

        $page = factory(App\Models\SuggestedEdit::class)->create();

        $this->get('/curation/viewdiff/' . $page->id)
            ->see('<ins>')
            ->see('<del>')
            ->assertResponseStatus(200);
    }

    /**
     * Logs in a new user so that we can path successfully though
     * authentication
     *
     * @return void
     */
    public function logInAsUser($overrides = [])
    {
        $this->user = factory(App\Models\User::class)->create($overrides);
        $this->be($this->user);
    }
}
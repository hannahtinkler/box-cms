<?php

use App\Models\Page;
use App\Models\PageDraft;
use App\Services\ControllerServices\PageDraftControllerService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PageDraftControllerServiceTest extends TestCase
{
    /**
     * The current user being worked on behalf of in the test
     * @var object User
     */
    private $user;

    /**
     * An instance of the PageDraftControllerService class under test
     * @var object
     */
    private $controllerService;

    /**
     * An array of fields which should be used for comparison purposes when
     * using assertEquals()
     *
     * @var array
     */
    public $comparableFields = array(
        'chapter_id',
        'title',
        'description',
        'content',
        'created_by'
    );

    /**
     * Runs the parent setUp operations and then creates and new user.
     * Instantiates an instance of the PageDraftControllerService class under test
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->user = factory(App\Models\User::class)->create();

        $prophet = new Prophecy\Prophet;
        $prophecy = $prophet->prophesize('Illuminate\Http\Request');
        $prophecy->user()->willReturn($this->user);

        $this->controllerService = new PageDraftControllerService($prophecy->reveal());
    }

    /**
     * Tests that a call to the method which stores drafts works when it is
     * passed all the data possible from the creation form
     *
     * @return void
     */
    public function testItCanStoreAPageDraftWithAllData()
    {
        $chapter = factory(App\Models\Chapter::class)->create();
        $requestData = [
            '_token' => $this->faker->randomNumber(5),
            'category_id' => $chapter->category->id,
            'chapter_id' => $chapter->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'content' => $this->faker->text,
            'last_draft_id' => null
        ];

        $expected = $requestData;
        $expected['created_by'] = $this->user->id;

        $actual = $this->controllerService->savePageDraft($requestData)->toArray();

        $this->assertEquals(
            $this->comparableFields($expected),
            $this->comparableFields($actual)
        );
    }

    /**
     * Tests that a call to the method which stores drafts works when it is
     * passed no data from the creation from
     *
     * @return void
     */
    public function testItCanStoreAPageDraftWithNoData()
    {
        $chapter = factory(App\Models\Chapter::class)->create();
        $requestData = [
            '_token' => $this->faker->randomNumber(5)
        ];

        $expected = array_merge($requestData, [
            'category_id' => null,
            'chapter_id' => null,
            'title' => null,
            'description' => null,
            'content' => null,
            'created_by' => $this->user->id
        ]);

        $actual = $this->controllerService->savePageDraft($requestData)->toArray();

        $this->assertEquals(
            $this->comparableFields($expected),
            $this->comparableFields($actual)
        );
    }

    /**
     * Tests that a call to the method which updates drafts works when it is
     * passed all the available data from the editing form
     *
     * @return void
     */
    public function testItCanUpdateAPageDraftWithAllData()
    {
        $draft = factory(App\Models\PageDraft::class)->create();

        $chapter = factory(App\Models\Chapter::class)->create();
        $requestData = [
            '_token' => $this->faker->randomNumber(5),
            'category_id' => $chapter->category->id,
            'chapter_id' => $chapter->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'content' => $this->faker->text,
            'last_draft_id' => $draft->id
        ];

        $expected = $requestData;
        $expected['created_by'] = $draft->created_by;

        $actual = $this->controllerService->updatePageDraft($draft, $requestData)->toArray();

        $this->assertEquals(
            $this->comparableFields($expected),
            $this->comparableFields($actual)
        );
    }

    /**
     * Tests that a call to the method which updates drafts works when it is
     * passed no data from the editing form
     *
     * @return void
     */
    public function testItCanUpdateAPageDraftWithNoData()
    {
        $draft = factory(App\Models\PageDraft::class)->create();

        $chapter = factory(App\Models\Chapter::class)->create();
        $requestData = [
            '_token' => $this->faker->randomNumber(5),
        ];

        $expected = array_merge($requestData, [
            'category_id' => null,
            'chapter_id' => null,
            'title' => null,
            'description' => null,
            'content' => null,
            'created_by' => $draft->created_by
        ]);

        $actual = $this->controllerService->updatePageDraft($draft, $requestData)->toArray();

        $this->assertEquals(
            $this->comparableFields($expected),
            $this->comparableFields($actual)
        );
    }

    /**
     * Tests that a call to the method which deleted drafts successfully
     * deletes a draft
     *
     * @return void
     */
    public function testItCanDeleteAPageDraft()
    {
        $draft = factory(App\Models\PageDraft::class)->create();

        $this->controllerService->deletePageDraft($draft->id);

        $lookup = PageDraft::find($draft->id);

        $this->assertNull($lookup);
    }

    /**
     * Tests that a call to the method which gets drafts for a user returns
     * the expected drafts
     *
     * @return void
     */
    public function testItCanGetDraftsForUser()
    {
        $draft = factory(App\Models\PageDraft::class)->create(['created_by' => $this->user->id]);

        $expected = [$draft->toArray()];

        $actual = $this->controllerService->getDraftsForUser()->toArray();

        $this->assertEquals(
            $this->comparableFields($expected),
            $this->comparableFields($actual)
        );
    }
}

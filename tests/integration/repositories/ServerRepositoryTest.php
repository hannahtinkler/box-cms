<?php

use App\Models\Server;
use App\Repositories\ServerRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServerRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The current server being used in the test
     * @var object Server
     */
    public $server;

    /**
     * Tests that a call to the method which retrieves the text string the
     * search form uses returns as expected
     *
     * @return void
     */
    public function testSearchResultStringIsCorrect()
    {
        $repository = $this->getServerRepository();

        $expected = 'Server: ' . $this->server->name . ' / ' . $this->server->nickname . ' - ' . $this->server->location . ' ' . ' (' . $this->server->node_type . ')';
        $actual = $repository->searchResultString();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that a call to the method which retrieves the URL string the
     * search form uses returns as expected
     *
     * @return void
     */
    public function testSearchResultUrlIsCorrect()
    {
        $repository = $this->getServerRepository();

        $expected = '/p/mayden/servers/server-details/' . $this->server->id;
        $actual = $repository->searchResultUrl();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that a call to the method which retrieves the icon html the
     * search form uses returns as expected
     *
     * @return void
     */
    public function testSearchResultIconIsCorrect()
    {
        $repository = $this->getServerRepository();

        $expected = '<i class="fa fa-server"></i>';
        $actual = $repository->searchResultIcon();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Create instance of ServerRepository class
     *
     * @return void
     */
    private function getServerRepository()
    {
        $this->server = factory(Server::class)->create();
        return new ServerRepository($this->server);
    }
}

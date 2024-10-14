<?php

namespace Amranidev\Laracombee\Tests;

use Carbon\Carbon;

class LaracombeeTest extends TestCase
{
    public $recombeeResponse = 'ok';

    public $userId = 1;

    public $itemId = 1;

    public $timestamp;

    /**
     * laracombee instance.
     *
     * @var \Amranidev\Laracombee\Laracombee
     */
    private $laracombee;

    public function setUp(): void
    {
        parent::setUp();
        $this->laracombee = new \Amranidev\Laracombee\Laracombee();
        $this->timestamp = Carbon::now()->toIso8601String();
    }

    public function testAddItemProperty()
    {
        $request = $this->laracombee->addItemProperty('productName', 'string');

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, $this->recombeeResponse);
    }

    public function testAddUserProperty()
    {
        $request = $this->laracombee->addUserProperty('firstName', 'string');

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, $this->recombeeResponse);
    }

    public function testAddUser()
    {
        $userProperties = ['firstName' => 'Jhon Doe'];

        $request = $this->laracombee->setUserValues($this->userId, $userProperties);

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, 'ok');
    }

    public function testGetUserValues()
    {
        $request = $this->laracombee->getUserValues($this->userId);

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertIsArray($response);
    }

    public function testAddItem()
    {
        $itemProperties = ['productName' => 'My product'];

        $request = $this->laracombee->setItemValues($this->itemId, $itemProperties);

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, 'ok');
    }

    public function testGetItemValues()
    {
        $request = $this->laracombee->getItemValues($this->itemId);

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertIsArray($response);
    }

    public function testAddDetailView()
    {
        $options = ['duration' => 15, 'cascadeCreate' => true];

        $request = $this->laracombee->addDetailView($this->userId, $this->itemId, $options);

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, 'ok');
    }

    public function testAddandDeletePurchase()
    {
        $time = (float) Carbon::now()->timestamp . '.0';
        $options = [
            'timestamp'     => $time,
            'cascadeCreate' => true,
            'amount'        => 5,
            'price'         => 15,
            'profit'        => 20,
        ];

        $request = $this->laracombee->addPurchase($this->userId, $this->itemId, $options);

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, 'ok');
    }

    public function testAddRating()
    {
        $options = [
            'cascadeCreate' => true,
        ];

        // rating shoud be a real number betweed  -1.0 < x < 1.0
        $rating = 0.8;

        $request = $this->laracombee->addRating($this->userId, $this->itemId, (float) $rating, $options);

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, 'ok');
    }

    public function testAddCardAddition()
    {
        $options = [
            'cascadeCreate' => true,
            'amount'        => 5,
            'price'         => 50,
        ];

        $request = $this->laracombee->addCartAddition($this->userId, $this->itemId, $options);

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, 'ok');
    }

    public function testAddBookmark()
    {
        $options = [
            'cascadeCreate' => true,
        ];

        $request = $this->laracombee->addBookmark($this->userId, $this->itemId, $options);

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, 'ok');
    }

    public function testRecommendItemsToUser()
    {
        $filter = [];

        $response = $this->laracombee->recommendItemsToUser($this->userId, 1, $filter)->wait();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('recomms', $response);
        $this->assertArrayHasKey('recommId', $response);
    }

    public function testListUserDetailViews()
    {
        $details = $this->laracombee->listUserDetailViews($this->userId);

        $response = $this->laracombee->send($details)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $details);
        $this->assertIsArray($response);
    }

    public function testListItemDetailViews()
    {
        $details = $this->laracombee->listItemDetailViews($this->itemId);

        $response = $this->laracombee->send($details)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $details);
        $this->assertIsArray($response);
    }

    public function testListItems()
    {
        $options = [
            'filter'             => '',
            'count'              => 5,
            'offset'             => 0,
            'returnProperties'   => true,
            'includedProperties' => ['productName'],
        ];

        $request = $this->laracombee->listItems($options);

        $response = $this->laracombee->send($request)->wait();

        $this->assertIsArray($response);

        foreach ($response as $item) {
            $this->assertArrayHasKey('productName', $item);
            $this->assertArrayHasKey('itemId', $item);
        }
    }

    public function testGetItemPropertyInfo()
    {
        $request = $this->laracombee->getItemPropertyInfo('productName');

        $response = $this->laracombee->send($request)->wait();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('type', $response);
        $this->assertEquals('productName', $response['name']);
        $this->assertEquals('string', $response['type']);
    }

    public function testListUsers()
    {
        $options = [
            'filter'             => '',
            'count'              => 5,
            'offset'             => 0,
            'returnProperties'   => true,
            'includedProperties' => ['firstName'],
        ];

        $request = $this->laracombee->listUsers($options);

        $response = $this->laracombee->send($request)->wait();

        $this->assertIsArray($response);
        $this->assertEquals(1, count($response));
        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);

        foreach ($response as $user) {
            $this->assertArrayHasKey('firstName', $user);
            $this->assertArrayHasKey('userId', $user);
        }
    }

    public function testListItemRatings()
    {
        $request = $this->laracombee->listItemRatings($this->itemId);
        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertIsArray($response);
    }

    public function testListUserRatings()
    {
        $request = $this->laracombee->listUserRatings($this->userId);
        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertIsArray($response);
    }

    public function testAddSeries()
    {
        $request = $this->laracombee->addSeries('laracombee-series');

        $response = $this->laracombee->send($request)->wait();

        $this->assertEquals($response, $this->recombeeResponse);
    }

    public function testInsertToSeries()
    {
        $request = $this->laracombee->insertToSeries('laracombee-series', 'item', (string) $this->itemId, 200);

        $response = $this->laracombee->send($request)->wait();

        $this->assertEquals($response, 'ok');
    }

    public function testListSeries()
    {
        $request = $this->laracombee->listSeries();
        $response = $this->laracombee->send($request)->wait();

        $this->assertIsArray($response);
    }

    public function testListSeriesItems()
    {
        $request = $this->laracombee->listSeriesItems('laracombee-series');
        $response = $this->laracombee->send($request)->wait();

        $this->assertIsArray($response);
    }

    public function testRemoveFromSeries()
    {
        $request = $this->laracombee->removeFromSeries('laracombee-series', 'item', $this->itemId, 200);
        $response = $this->laracombee->send($request)->wait();

        $this->assertEquals($response, $this->recombeeResponse);
    }

    public function testDeleteSeries()
    {
        $request = $this->laracombee->deleteSeries('laracombee-series');
        $response = $this->laracombee->send($request)->wait();
        $this->assertEquals($response, $this->recombeeResponse);
    }

    public function testSetViewPortion()
    {
        $request = $this->laracombee->setViewPortion($this->userId, $this->itemId, 0.22, []);
        $response = $this->laracombee->send($request)->wait();
        $this->assertEquals($response, 'ok');
    }

    public function testDeleteViewPortion()
    {
        $request = $this->laracombee->deleteViewPortion($this->userId, $this->itemId, []);
        $response = $this->laracombee->send($request)->wait();
        $this->assertEquals($response, $this->recombeeResponse);
    }

    // public function testDeleteRating()
    // {
    //     $options = [];

    //     $request = $this->laracombee->deleteRating($this->userId, $this->itemId, $options);

    //     $response = $this->laracombee->send($request)->wait();

    //     $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
    //     $this->assertEquals($response, $this->recombeeResponse);
    // }

    // public function testDeleteCardAddition()
    // {
    //     $options = [];

    //     $request = $this->laracombee->deleteCartAddition($this->userId, $this->itemId, $options);

    //     $response = $this->laracombee->send($request)->wait();

    //     $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
    //     $this->assertEquals($response, $this->recombeeResponse);
    // }

    // public function testDeleteBookmark()
    // {
    //     $options = [];

    //     $request = $this->laracombee->deleteBookmark($this->userId, $this->itemId, $options);

    //     $response = $this->laracombee->send($request)->wait();

    //     $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
    //     $this->assertEquals($response, $this->recombeeResponse);
    // }

    // public function testDeleteDetailView()
    // {
    //     $options = [];

    //     $request = $this->laracombee->deleteDetailView($this->userId, $this->itemId, $options);

    //     $response = $this->laracombee->send($request)->wait();

    //     $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
    //     $this->assertEquals($response, $this->recombeeResponse);
    // }

    public function testDeleteUser()
    {
        $request = $this->laracombee->deleteUser($this->userId);

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, $this->recombeeResponse);
    }

    public function testDeleteItem()
    {
        $request = $this->laracombee->deleteItem($this->itemId);

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, $this->recombeeResponse);
    }

    public function testDeleteUserProperty()
    {
        $request = $this->laracombee->deleteUserProperty('firstName');

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, $this->recombeeResponse);
    }

    public function testDeleteItemProperty()
    {
        $request = $this->laracombee->deleteItemProperty('productName');

        $response = $this->laracombee->send($request)->wait();

        $this->assertInstanceOf(\Recombee\RecommApi\Requests\Request::class, $request);
        $this->assertEquals($response, $this->recombeeResponse);
    }

    public function testResetDatabase()
    {
        $request = $this->laracombee->resetDatabase();

        $response = $this->laracombee->send($request)->wait();

        $this->assertEquals($response, $this->recombeeResponse);
    }
}

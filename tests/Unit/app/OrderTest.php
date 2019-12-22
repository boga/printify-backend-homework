<?php

namespace Tests\Unit\app;

use App\Order;
use App\OrderItem;
use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use Tests\TestCase;


class OrderTest extends TestCase
{
    public function testGetExceptionWhenNoItems()
    {
        $mock = Mockery::mock("App\Order[load]");
        $mock->shouldReceive("load")->once()
            ->andSet("items", collect([]));

        $this->expectException("Exception");
        $cost = $mock->getCost();
    }

    public function testGetCostWhenItemsExists()
    {
        $p1 = new Product();
        $p1->price = 1;
        $p2 = new Product();
        $p2->price = 2;
        $oi1 = new OrderItem();
        $oi1->product = $p1;
        $oi1->quantity = 3;
        $oi2 = new OrderItem();
        $oi2->product = $p2;
        $oi2->quantity = 5;

        $mock = Mockery::mock("App\Order[load]");
        $mock->shouldReceive("load")->once()
            ->andSet("items", collect([$oi1, $oi2]));

        $cost = $mock->getCost();

        $this->assertEquals(13, $cost);
    }

    public function testFillCountryWithExistingOne()
    {
        $mock = Mockery::mock("App\Helpers\IP");
        $mock->shouldNotReceive("getCountry");

        $order = new Order();
        $order->country = "UK";
        $order->fillCountry();

        $this->assertEquals("UK", $order->country);
    }

}

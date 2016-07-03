<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions; // <= trait
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Laravel 5');
    }

    public function testProductsList()
    {
        $products = factory(App\Product::class, 3)->create();

        $this->get(route('api.products.index'))
             ->assertResponseOk();

        array_map(function($product) {
            $this->seeJson($product->jsonSerialize());
        }, $products->all());
    }

    public function testProductDescriptionList()
    {
        $product = factory(\App\Product::class)->create();
        $product->descriptions()->saveMany(factory(\App\Description::class, 3)->make());

        $this->get(route('api.products.descriptions.index', ['products' => $product->id]));

        array_map(function($product) {
            $this->seeJson($product->jsonSerialize());
        }, $product->descriptions->all());
    }
}

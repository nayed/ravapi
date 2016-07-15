<?php

use App\Product;
use App\Description;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions; // <= trait
    
    protected $jsonHeaders = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
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
        $products = factory(Product::class, 3)->create();

        $this->get(route('api.products.index'))
             ->assertResponseOk();

        array_map(function($product) {
            $this->seeJson($product->jsonSerialize());
        }, $products->all());
    }

    public function testProductDescriptionList()
    {
        $product = factory(Product::class)->create();
        $product->descriptions()->saveMany(factory(Description::class, 3)->make());

        $this->get(route('api.products.descriptions.index', ['products' => $product->id]))->assertResponseOk();

        array_map(function($product) {
            $this->seeJson($product->jsonSerialize());
        }, $product->descriptions->all());
    }

    public function testProductCreation()
    {
        $product = factory(Product::class)->make(['name' => 'meats']);

        $this->post(route('api.products.store'), $product->jsonSerialize(), $this->jsonHeaders)
        ->seeInDatabase('products', ['name' => $product->name]);
    }

    // public function testProductDescriptionCreation()
    // {
    //     $product = factory(Product::class)->create(['name' => 'meats']);
    //     $description = factory(Description::class)->make();

    //     $this->post(route('api.products.descriptions.store', ['products' => $product->id]), $description->jsonSerialize(), $this->jsonHeaders)
    //     ->seeInDatabase('descriptions', ['body' => $description->body]);
    // }
    
    // public function testProductUpdate()
    // {
    //     $product = factory(Product::class)->create(['name' => 'beats']);
    //     $product->name = 'feets';
    //     $product->save();
    //     //dd($product);
    //     //dd(['products' => $product->id]);
    //     $this->put(route('api.products.update', ['products' => $product->id]), $product->jsonSerialize(), $this->jsonHeaders)
    //     ->seeInDatabase('products', ['name' => $product->name]);
    // }
    
    // public function testProductCreationFailsWhenNameNotProvided()
    // {
    //     $product = factory(Product::class)->make(['name' => '']);

    //     $this->post(route('api.products.store'), $product->jsonSerialize(), $this->jsonHeaders)
    //     ->seeInDatabase('products', ['name' => $product->name])
    //     ->seeJson(['name' => ['Yo the name field is required']])
    //     ->assertResponseStatus(422);
    // }
}

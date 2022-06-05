<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
                 ->assertSee('Shop Tech Items')
                 ->assertSee('Get items for the cheapest price')
                 ->assertSee('Home')
                 ->assertSee('Shop')
                 ->assertSee('Cart')
                 ->assertSee('Shop Now')
                 ->assertDontSee('apple');
    }

    public function test_shop_page()
    {
        $response = $this->get('/shop');
 
        $response->assertStatus(200)
                 ->assertSee('Apple Macbook Pro 16')
                 ->assertSee('Samsung Galaxy Book Pro');
    }

    public function test_shop_detial_page()
    {
        $response = $this->get('/shop/13');
 
        $response->assertStatus(200)
                 ->assertSee('Apple Macbook Pro 16')
                 ->assertSee('$ 2499');
    }

    public function test_cart_page()
    {
        $response = $this->get('/cart');

        $response->assertStatus(200)
                 ->assertSee('Shopping Cart')
                 ->assertSee('Shopping cart is empty.');
    }

    public function test_database()
    {
        $this->assertDatabaseHas('products', [
            'name' => 'Apple Macbook Pro 16',
            'details' => 'Apple M1 Pro, 16 GPU, 16 GB, 512 GB SSD',
            'description' => 'The most powerful MacBook Pro ever is here. With the blazing-fast M1 Pro or M1 Max chip — the first Apple silicon designed for pros — you get groundbreaking performance and amazing battery life. Add to that a stunning Liquid Retina XDR display, the best camera and audio ever in a Mac notebook, and all the ports you need. The first notebook of its kind, this MacBook Pro is a beast.',
            'brand' => 'Apple',
            'price' => 2499,
            'shipping_cost' => 25,
            'image_path' => 'storage/product1.png'
        ]);
    }

    public function test_database_Mis()
    {
        $this->assertDatabaseMissing('products', [
            'name' => 'asd',
            'details' => 'zxc',
            'description' => 'TfgsPro eve a a beast',
            'brand' => 'Asdfle',
            'price' => 2499,
            'shipping_cost' => 25,
            'image_path' => 'storage/product1.png'
        ]);
    }

    public function test_create_product()
    {
        $cartItem = session()->get('cartItem', [
            "image_path"=> 'storage/product1.png',
            'name' => 'Apple',
            'brand' => 'Apple',
            'details' => 'Apple M1 Pro, 16 GPU, 16 GB, 512 GB SSD',
            'price' => 2499,
            'quantity' => 1
        ]);

        session()->put('cartItem',$cartItem);

        $response = $this->get('/cart');
        $response->assertSee('Apple');

    }
    
    public function test_delete_product()
    {
        $cartItem = session()->get('cartItem', [
            "image_path"=> 'storage/product1.png',
            'name' => 'Apple',
            'brand' => 'Apple',
            'details' => 'Apple M1 Pro, 16 GPU, 16 GB, 512 GB SSD',
            'price' => 2499,
            'quantity' => 1 
        ]);

        session()->put('cartItem',$cartItem);
        session()->forget('cartItem', $cartItem);

        $this->get('/cart')
             ->assertDontSee('Apple');

    }
    
    public function test_quanlity_user()
    {
        $cartItem = session()->get('cartItem', [
            "image_path"=> 'storage/product1.png',
            'name' => 'Apple',
            'brand' => 'Apple',
            'details' => 'Apple M1 Pro, 16 GPU, 16 GB, 512 GB SSD',
            'price' => 2499,
            'quantity' => 5 
        ]);

        session()->put('cartItem',$cartItem);

        $this->put('/cart')
             ->assertSee('12495');

    }
}

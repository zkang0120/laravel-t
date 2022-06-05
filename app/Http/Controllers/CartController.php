<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    
    public function cart(Request $request)
    {
        //dd(session('cartItem'));
        return view('cart.cart');
    }

    public function addToCart($id)
    {
        $product = Product::FindOrFail($id);
        $cartItem = session()->get('cartItem', []);

        if(isset($cartItem[$id]))
        {
            $cartItem[$id]['quantity']++;
        } else
        {
            $cartItem[$id]=[
                "image_path"=> $product->image_path,
                'name' => $product->name,
                'brand' => $product->brand,
                'details' => $product->details,
                'price' => $product->price,
                'quantity' => 1
            ];
        }

        session()->put('cartItem',$cartItem);
        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function delete(Request $request)
    {
        if($request->id) {
            $cartItem = session()->get('cartItem');

            if(isset($cartItem[$request->id])) {
                unset($cartItem[$request->id]); 
                session()->put('cartItem', $cartItem);
            }

            return redirect()->back()->with('success', 'Product deleted successfully');
        }
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cartItem = session()->get('cartItem');
            $cartItem[$request->id]["quantity"] = $request->quantity;
            session()->put('cartItem', $cartItem);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }
}

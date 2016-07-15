<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:products'
        ]);

        return Product::create([
            'name' => $request->input('name')
        ]);
    }

    // public function update(Request $request, $id)
    // {
    //     $product = Product::findOrFail($id);
    //     //dd($request->input('name'));
    //     $product->update([
    //         'name' => $request->input('name')
    //     ]);
    // }
}

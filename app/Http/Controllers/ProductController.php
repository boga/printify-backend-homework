<?php

namespace App\Http\Controllers;

use App\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws Exception
     */
    public function index()
    {
        throw new Exception("not implemented");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws Exception
     */
    public function create()
    {
        throw new Exception("not implemented");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws Exception
     */
    public function store(Request $request)
    {
        $postData = $request->all();
        if (empty($postData["color"]) ||
            empty($postData["price"]) ||
            empty($postData["size"]) ||
            empty($postData["type"])
        ) {
            throw new Exception("not enough data");
        }

        /** We could use Laravel's events to check if Product with these attributes exists already but as
         * long as we need this validation in this very API method we'll do it right here */
        $existingProduct = Product::where("color", $postData['color'])
            ->where("price", $postData['price'])
            ->where("size", $postData['size'])
            ->where("type", $postData['type'])
            ->first()
        ;
        if (!empty($existingProduct)) {
            throw new Exception("product with these attributes exists");
        }

        $newProduct = new Product();
        $newProduct->fill($postData);
        $newProduct->save();

        return [
            "product" => $newProduct,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function show($id)
    {
        throw new Exception("not implemented");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function edit($id)
    {
        throw new Exception("not implemented");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        throw new Exception("not implemented");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function destroy($id)
    {
        throw new Exception("not implemented");
    }
}

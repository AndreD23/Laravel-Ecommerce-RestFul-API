<?php

namespace App\Http\Controllers;

use App\Product;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\ProductNotBelongsToUserException;

class ProductController extends Controller
{

    public function __construct(){
        $this->middleware('auth:api')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ProductCollection::collection(Product::paginate(15));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = new Product();

        $product->name = $request->name;
        $product->detail = $request->description;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->discount = $request->discount;

        try{
            $product->save();
        } catch(\Exception $e){
            return response([
                'data' => 'Unable to create product'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response([
            'data' => new ProductResource($product)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return void
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\Response
     * @throws ProductNotBelongsToUserException
     */
    public function update(Request $request, Product $product)
    {

        //Verifica se o usuário tem permissão para editar o produto
        $this->productUserCheck($product);

        //Tratando os dados recebidos
        $request['detail'] = $request->description;
        unset($request['description']);

        try{
           $product->update($request->all());
        } catch(\Exception $e){
            return response([
                'data' => 'Unable to Update'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response([
            'data' => $product
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     * @throws ProductNotBelongsToUserException
     */
    public function destroy(Product $product)
    {

        //Verifica se o usuário tem permissão para editar o produto
        $this->productUserCheck($product);

        try{
            $product->delete();
        } catch(\Exception $e){
            return response([
                "data" => "Unable to Delete Product"
            ], Response::HTTP_BAD_REQUEST);
        }

        return response([
            "data" => "Product deleted"
        ], Response::HTTP_NO_CONTENT);
    }

    protected function productUserCheck($product)
    {
        if(Auth::id() !== $product->user_id){
            throw new ProductNotBelongsToUserException;
        }
    }
}

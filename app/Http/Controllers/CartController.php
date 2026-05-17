<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CarService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CarService $carService)
    {   
        dd($carService);
    }

  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product, CarService $carService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, CarService $carService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request ,Product $product, CarService $carService)
    {
        //
    }
}

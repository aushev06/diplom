<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodRequest;
use App\Models\Food;
use App\Models\Views\Food\FoodViewModel;
use App\Services\FoodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodController extends Controller
{
    /**
     * @var FoodService
     */
    private $service;
    /**
     * @var Food
     */
    private $model;

    public function __construct(FoodService $service, Food $model)
    {
        $this->service = $service;
        $this->model   = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Food::distinct('key')
            ->selectRaw('SUBSTRING(name, 1, 1) as "key"')
            ->get()
            ->map(function (Food $food) {
                return new FoodViewModel($food);
            });

        return response()->json(['data' => $response]);
    }


    public function foodList(Request $request)
    {
        $foods = Food::get();

        return response()->json($foods);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FoodRequest $request)
    {
        $food = $this->service->save($request, $this->model);

        return response()->json(['food' => $food]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $food = Food::findOrFail($id);

        return response()->json(['food' => $food]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(FoodRequest $request, $id)
    {
        $food = $this->service->save($request, $this->model->findOrFail($id));

        return response()->json(['food' => $food]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Food::findOrFail($id)->delete();

        return response()->json(1);
    }
}

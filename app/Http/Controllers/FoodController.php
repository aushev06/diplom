<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodRequest;
use App\Models\Food;
use App\Models\Views\Food\FoodViewModel;
use App\Providers\AuthServiceProvider;
use App\Services\FoodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
        $response = Food::
        query()
            ->distinct('key')
            ->selectRaw('SUBSTRING(name, 1, 1) as "key"')
            ->where(Food::ATTR_USER_ID, auth()->id())
            ->get()
            ->map(function (Food $food) {
                return new FoodViewModel($food);
            });

        return response()->json(['data' => $response]);
    }


    public function foodList(Request $request)
    {
        $foods = Food::query()
            ->where(Food::ATTR_USER_ID, auth()->id())
            ->get();

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
        if (Gate::allows(AuthServiceProvider::ALLOW_IS_ME, $food)) {
            return response()->json(['food' => $food]);
        }


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
        $model = $this->model->findOrFail($id);
        if (Gate::allows(AuthServiceProvider::ALLOW_IS_ME, $model)) {
            $food = $this->service->save($request, $model);
            return response()->json(['food' => $food]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Food::findOrFail($id);
        if (Gate::allows(AuthServiceProvider::ALLOW_IS_ME, $model)) {
            $model->delete();
            return response()->json(1);
        }


    }
}

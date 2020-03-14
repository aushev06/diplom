<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientCollection;
use App\Models\Client;
use App\Models\Views\ClientViewModel;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * @var ClientService
     */
    private $service;
    /**
     * @var Client
     */
    private $model;

    public function __construct(ClientService $service, Client $model)
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
        $response = Client::distinct('key')
            ->selectRaw('SUBSTRING(name, 1, 1) as "key"')
            ->get()
            ->map(function (Client $food) {
                return new ClientViewModel($food);
            });


        return response()->json(['data' => $response]);

    }


    /**
     * Возвращает простой список клиентов
     *
     * @param Request $request
     */
    public function listClients(Request $request)
    {
        $clients = Client::get();

        return response()->json($clients);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request)
    {
        $client = $this->service->save($request, $this->model);

        return response()->json(['client' => $client]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::with(Client::WITH_ORDERS)->findOrFail($id);

        return response()->json(['client' => $client]);
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
    public function update(ClientRequest $request, $id)
    {
        $client = $this->service->save($request, $this->model->findOrFail($id));
        return response()->json(['client' => $client]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->model->findOrFail($id)->delete();
        return response()->json(['status' => true]);
    }
}

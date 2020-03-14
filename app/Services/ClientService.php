<?php
/**
 * Created by PhpStorm.
 * User: aushev
 * Date: 04.11.2019
 * Time: 11:26
 */

namespace App\Services;


use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientService
{
    /**
     * @var Client
     */
    private $model;

    public function __construct(Client $model)
    {
        $this->model = $model;
    }

    public function save(ClientRequest $request, Client $model)
    {
        $data = $request->all([
            $model::ATTR_NAME,
            $model::ATTR_CITY,
            $model::ATTR_ADDRESS,
            $model::ATTR_PHONE,
            $model::ATTR_LINK,

        ]);

        $model->fill($data);
        $model->user_id = $request->user()->id;

        $model->save();

        return $model;
    }
}

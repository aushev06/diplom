<?php

namespace App\Models\Views;


use App\Models\Client;

class ClientViewModel
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var ClientViewDataModel[]
     */
    public $data = [];

    public function __construct($data)
    {
        $this->key = $data->key;

        $clients = Client::query()
            ->where(Client::ATTR_USER_ID, auth()->user()->id)
            ->where(Client::ATTR_NAME, 'LIKE', $this->key . '%')->get();

        foreach ($clients as $client) {
            $this->data[] = new ClientViewDataModel($client);
        }

    }
}

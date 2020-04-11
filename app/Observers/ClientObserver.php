<?php

namespace App\Observers;

use App\Models\Client;

class ClientObserver
{
    /**
     * Handle the client "created" event.
     *
     * @param \App\Models\Client $client
     * @return void
     */
    public function created(Client $client)
    {
        //
    }

    /**
     * Handle the client "updated" event.
     *
     * @param \App\Models\Client $client
     * @return void
     */
    public function updated(Client $client)
    {
        //
    }

    /**
     * Handle the client "deleted" event.
     *
     * @param \App\Models\Client $client
     * @return void
     */
    public function deleted(Client $client)
    {
        $client->orders()->delete();
    }

    /**
     * Handle the client "restored" event.
     *
     * @param \App\Models\Client $client
     * @return void
     */
    public function restored(Client $client)
    {
        //
    }

    /**
     * Handle the client "force deleted" event.
     *
     * @param \App\Models\Client $client
     * @return void
     */
    public function forceDeleted(Client $client)
    {
        //
    }
}

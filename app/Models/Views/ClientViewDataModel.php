<?php
/**
 * Created by PhpStorm.
 * User: aushev
 * Date: 07.11.2019
 * Time: 7:44
 */

namespace App\Models\Views;


class ClientViewDataModel
{
    /**
     * @var integer $id
     */
    public $id;
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $letter;

    /**
     * @var integer
     */
    public $count;

    /**
     * @var integer
     */
    public $total;

    public function __construct($client)
    {
        $this->name   = $client['name'];
        $this->letter = mb_substr($client['name'], 0, 1);
        $this->id     = $client['id'];
        $this->count  = isset($client['count']) ? $client['count'] : null;
        $this->total  = isset($client['total']) ? $client['total'] : null;
    }
}

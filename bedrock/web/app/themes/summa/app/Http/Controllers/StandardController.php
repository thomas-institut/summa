<?php

namespace App\Http\Controllers;


use Rareloop\Lumberjack\Http\Controller as BaseController;

/**
 * Class StandardController
 * @package App\Http\Controllers
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class StandardController extends BaseController
{

    public function __construct()
    {
        $capsule = new \Illuminate\Database\Capsule\Manager();
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $_ENV["DB_HOST"],
            'database'  => $_ENV["DB_NAME"],
            'username'  => $_ENV["DB_USER"],
            'password'  => $_ENV["DB_PASSWORD"],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $capsule->bootEloquent();
    }
}

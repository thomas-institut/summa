<?php

namespace App\Http\Controllers;

use App\Http\Operations\WebManager;


use Rareloop\Lumberjack\Facades\Session;
use Rareloop\Lumberjack\Http\ServerRequest;



class AdminController extends StandardController
{



    public function home(ServerRequest $request){

        $webInfo=WebManager::get($request);
        error_log(print_r(Session::all(), true));
        $user=wp_get_current_user();
        if ($user->ID != 0){
            if ($user->roles[0]=="administrator"){
                return "Logged in: Admin";
            } else {
                return "Logged in: No Admin";
            }
        } else {
            return "not allowed";
        }

        #return new TimberResponse('views/templates/home.twig', [ "webInfo"=>$webInfo, "toc" => $toc]);
    }











}

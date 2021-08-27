<?php

/**
 * The Template for displaying all single posts
 */

namespace App;

use App\Http\Controllers\Controller;
use App\Http\Operations\WebManager;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Rareloop\Lumberjack\Http\ServerRequest;
use Rareloop\Lumberjack\Post;
use Timber\Timber;
use Timber\User;

class SingleController extends Controller
{
    public function handle(ServerRequest $request)
    {
        $webInfo=WebManager::get($request);
        $webInfo["route"]="aktuelles";
        global $wp_query;
        $context = Timber::get_context();
        $lpost = new Post();


        $post['post'] = $lpost;
        $post['title'] = $lpost->title;
        $post['content'] = $lpost->content;
        $post["author"]=$lpost->author();
        $data["post"]=$lpost;

        error_log(print_r("HSADASDASASDASD", true));

        return new TimberResponse('templates/post.twig', [ "webInfo"=>$webInfo, "data" => $data]);
    }
}

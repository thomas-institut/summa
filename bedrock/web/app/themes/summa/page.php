<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 */

namespace App;

use App\Http\Controllers\Controller;
use App\Http\Operations\WebManager;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Rareloop\Lumberjack\Http\ServerRequest;
use Rareloop\Lumberjack\Page;
use Timber\Timber;


class PageController extends Controller
{
    public function handle(ServerRequest $request)
    {
        $webInfo=WebManager::get($request);

        $context = Timber::get_context();
        $page = new Page();

        $context['post'] = $page;
        $context['title'] = $page->title;
        $context['content'] = $page->content;

        return new TimberResponse('templates/generic-page.twig', [ "webInfo"=>$webInfo, "wp"=>$context]);
    }
}

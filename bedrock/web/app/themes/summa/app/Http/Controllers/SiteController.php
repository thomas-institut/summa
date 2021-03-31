<?php

namespace App\Http\Controllers;

use App\Http\Models\Article;
use App\Http\Models\Book;
use App\Http\Models\Chapter;
use App\Http\Models\Chunk;
use App\Http\Models\Work;
use App\Http\Operations\Output;
use App\Http\Operations\WebManager;

use Illuminate\Database\Eloquent\Builder;
use Michelf\MarkdownExtra;
use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;
use Rareloop\Lumberjack\Http\Controller as BaseController;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Rareloop\Lumberjack\Http\ServerRequest;
use Illuminate\Container\Container;
use Timber\Post;


class SiteController extends StandardController
{



    public function home(ServerRequest $request){

        $webInfo=WebManager::get($request);
        $works = Work::with("books")->get();
        error_log(print_r(\Rareloop\Lumberjack\Post::all(), true));






        return new TimberResponse('views/templates/home.twig', [ "webInfo"=>$webInfo, "works" => $works]);
    }


    /**
     * @param ServerRequest $request
     * @param $workId
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function work (ServerRequest $request, $workId){
        $webInfo=WebManager::get($request);
        $works = Output::create($workId);
        try {
            return new TimberResponse('views/templates/viewer.twig', [ "webInfo"=>$webInfo, "works"=>$works]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }

    /**
     * @param ServerRequest $request
     * @param $workId
     * @param $bookId
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function book(ServerRequest $request, $workId, $bookId){
        $webInfo=WebManager::get($request);
        $works=Output::create($workId, $bookId);
        try {
            return new TimberResponse('views/templates/viewer.twig', [ "webInfo"=>$webInfo, "works"=>$works]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }


    /**
     * @param ServerRequest $request
     * @param $workId
     * @param $bookId
     * @param $chapterId
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function chapter(ServerRequest $request, $workId, $bookId, $chapterId){
        $webInfo=WebManager::get($request);
        $works=Output::create($workId, $bookId, $chapterId);
        try {
            return new TimberResponse('views/templates/viewer.twig', [ "webInfo"=>$webInfo, "works"=>$works]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }


    /**
     * @param ServerRequest $request
     * @param $workId
     * @param $bookId
     * @param $chapterId
     * @param $articleId
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function article(ServerRequest $request, $workId, $bookId, $chapterId, $articleId){
        $webInfo=WebManager::get($request);
        $works=Output::create($workId, $bookId, $chapterId, $articleId);
        try {
            return new TimberResponse('views/templates/viewer.twig', [ "webInfo"=>$webInfo, "works"=>$works]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }










}

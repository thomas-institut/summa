<?php

namespace App\Http\Controllers;

use App\Http\Models\Article;
use App\Http\Models\Book;
use App\Http\Models\Question;
use App\Http\Operations\WebManager;
use Michelf\MarkdownExtra;
use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;
use Rareloop\Lumberjack\Http\Controller as BaseController;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Rareloop\Lumberjack\Http\ServerRequest;
use Illuminate\Container\Container;

class SiteController extends StandardController
{



    public function home(ServerRequest $request){

        $webInfo=WebManager::get($request);
        $books = Book::with("questions")->get();

        $books = $books->makeHidden(["content_lat"]);





        return new TimberResponse('views/templates/home.twig', [ "webInfo"=>$webInfo, "books" => $books]);
    }

    /**
     * show all books
     * @param ServerRequest $request
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function books(ServerRequest $request){
        $webInfo=WebManager::get($request);
        try {
            return new TimberResponse('views/templates/home.twig', [ "webInfo"=>$webInfo]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }

    /**
     * get a specific book by id
     * @param ServerRequest $request
     * @param $bookId
     * @return TimberResponse
     * @throws \Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException
     */
    public function book(ServerRequest $request, $bookId){
        $webInfo=WebManager::get($request);
        $articles = Article::whereHas('question', function ($q) use ( $bookId){
                $q->whereHas('book', function ($q2) use ($bookId) {
                    $q2->where("id", $bookId);
                });
        })->get()->toArray();
        for ($i=0; $i < count($articles); $i++){
            $articles[$i]["content_lat"]=MarkdownExtra::defaultTransform($articles[$i]["content_lat"]);
            $articles[$i]["content_ger"]=MarkdownExtra::defaultTransform($articles[$i]["content_ger"]);
        }
        try {
            return new TimberResponse('views/templates/articles.twig', [ "webInfo"=>$webInfo, "articles"=>$articles]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }

    /**
     * show all questions of specific book
     * @param ServerRequest $request
     * @param $bookId
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function questions(ServerRequest $request, $bookId){
        $webInfo=WebManager::get($request);
        error_log($bookId);
        try {
            return new TimberResponse('views/templates/home.twig', [ "webInfo"=>$webInfo]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }

    /**
     * get a specific question by bookid and question order
     * @param ServerRequest $request
     * @param $bookId
     * @param $questionOrder
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function question(ServerRequest $request, $bookId, $questionOrder){
        $webInfo=WebManager::get($request);
        $articles = Article::whereHas('question', function ($q) use ($questionOrder, $bookId){
            $q->where("order", $questionOrder)
                ->whereHas('book', function ($q2) use ($bookId) {
                    $q2->where("id", $bookId);
                });
        })->get()->toArray();
        for ($i=0; $i < count($articles); $i++){
            $articles[$i]["content_lat"]=MarkdownExtra::defaultTransform($articles[$i]["content_lat"]);
            $articles[$i]["content_ger"]=MarkdownExtra::defaultTransform($articles[$i]["content_ger"]);
        }
        try {
            return new TimberResponse('views/templates/articles.twig', [ "webInfo"=>$webInfo, "articles"=>$articles]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }

    /**
     * show all articles of specific question in specific book
     * @param ServerRequest $request
     * @param $bookId
     * @param $questionOrder
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function articles(ServerRequest $request, $bookId, $questionOrder){
        $webInfo=WebManager::get($request);
        error_log($bookId);
        error_log($questionOrder);
        try {
            return new TimberResponse('views/templates/home.twig', [ "webInfo"=>$webInfo]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }

    /**
     * get a specific article by bookid, question order and article order
     * @param ServerRequest $request
     * @param $bookId
     * @param $questionOrder
     * @param $articleOrder
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function article(ServerRequest $request, $bookId, $questionOrder, $articleOrder){
        $webInfo=WebManager::get($request);
        //$question = Article::where("id", "=", $articleOrder)->first()->toArray();
        $articles = Article::where("order", "=", $articleOrder )->whereHas('question', function ($q) use ($questionOrder, $bookId){
            $q->where("order", $questionOrder)
            ->whereHas('book', function ($q2) use ($bookId) {
                $q2->where("id", $bookId);
            });
        })->get()->toArray();
        error_log(print_r($articles, true));

        for ($i=0; $i < count($articles); $i++){
            $articles[$i]["content_lat"]=MarkdownExtra::defaultTransform($articles[$i]["content_lat"]);
            $articles[$i]["content_ger"]=MarkdownExtra::defaultTransform($articles[$i]["content_ger"]);
        }


        error_log(print_r($articles, true));
        try {
            return new TimberResponse('views/templates/articles.twig', [ "webInfo"=>$webInfo, "articles"=>$articles]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }










}

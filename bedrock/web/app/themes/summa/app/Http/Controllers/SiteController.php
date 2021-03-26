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


        $books = Book::with("questions")->get()->toArray();

        $text="Because the Teacher of Catholic
         truth ought not only to teach the proficient, but also to instruct beginners, according to the Apostle: ***As unto little*** [^1] ones in Christ, I gave you milk to drink, not meat (1 Cor 3:1–2), we purpose in this book to treat of whatever belongs to the Christian religion in such a way as may befit the instruction of beginners.

[^1]: This is the first footnote.";


        $webInfo["markdown2"]=MarkdownExtra::defaultTransform($text);


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
        error_log($bookId);
        try {
            return new TimberResponse('views/templates/home.twig', [ "webInfo"=>$webInfo]);
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
        $question = Article::where("id", "=", $articleOrder)->first()->toArray();
        $question["content_lat"]=MarkdownExtra::defaultTransform($question["content_lat"]);
        $question["content_ger"]=MarkdownExtra::defaultTransform($question["content_ger"]);
        error_log(print_r($question, true));
        try {
            return new TimberResponse('views/templates/question.twig', [ "webInfo"=>$webInfo, "question"=>$question]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }










}

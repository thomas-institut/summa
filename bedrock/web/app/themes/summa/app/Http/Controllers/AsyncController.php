<?php

namespace App\Http\Controllers;
use App\Http\Models\Article;
use App\Http\Models\Book;
use App\Http\Models\Chapter;
use App\Http\Models\Translator;
use App\Http\Models\Work;
use App\Http\Operations\Output;
use App\Http\Operations\WebManager;


use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;

use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Rareloop\Lumberjack\Http\ServerRequest;

use Timber\Timber;



class AsyncController extends StandardController
{
    public function bookCount(ServerRequest $request){
        $count=Book::count();
        return new TimberResponse('views/templates/home.project-counter.twig', [ "count"=>$count, "name" => "book-count"]);
    }
    public function chapterCount(ServerRequest $request){
        $count=Chapter::count();
        return new TimberResponse('views/templates/home.project-counter.twig', [ "count"=>$count, "name" => "chapter-count"]);
    }
    public function articleCount(ServerRequest $request){
        $count=Article::count();
        return new TimberResponse('views/templates/home.project-counter.twig', [ "count"=>$count, "name" => "article-count"]);
    }
    public function translatorCount(ServerRequest $request){
        $count=Translator::count();
        return new TimberResponse('views/templates/home.project-counter.twig', [ "count"=>$count, "name" => "translator-count"]);
    }








}

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


/**
 * Class AsyncController
 * @package App\Http\Controllers
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
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
    public function statusChapterCount(ServerRequest $request){
        $chapter=Chapter::count();
        $wipChapter=Chapter::where("translation_status", "=", "1")->count();
        $completedChapter=Chapter::where("translation_status", "=", "2")->count();
        $count["wip"]=round($wipChapter/$chapter*100, 2);
        $count["completed"]=round($completedChapter/$chapter*100, 2);

        return new TimberResponse('views/templates/home.project-status.twig', [ "count"=>$count, "name" => "chapter-count"]);
    }









}

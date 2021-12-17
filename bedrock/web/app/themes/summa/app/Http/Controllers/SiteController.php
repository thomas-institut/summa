<?php

namespace App\Http\Controllers;
use App\Http\Models\Book;
use App\Http\Models\Chapter;
use App\Http\Models\Translator;
use App\Http\Models\Work;
use App\Http\Operations\Output;
use App\Http\Operations\WebManager;
use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Rareloop\Lumberjack\Http\ServerRequest;
use Rareloop\Lumberjack\Post;
use Timber\Timber;


/**
 * Class SiteController
 * @package App\Http\Controllers
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class SiteController extends StandardController
{



    public function home(ServerRequest $request){

        $webInfo=WebManager::get($request);
        $news_posts = Timber::get_posts(array(
            'category_name' => 'News',
                'status' => 'publish',
                'posts_per_page' => '3',
                'order'          => 'DESC',
                'orderby'        => 'date',
        )
        );
        for ($i=0;$i<count($news_posts);$i++ ){
            $news_posts[$i]->thumbnail = $news_posts[$i]->thumbnail()->src("medium");
            $news_posts[$i]->author = $news_posts[$i]->author();
            if ($news_posts[$i]->post_excerpt!=""){
                $news_posts[$i]->preview = $news_posts[$i]->post_excerpt;
            } else {
                $post_content = str_replace("<!-- wp:paragraph -->", "", $news_posts[$i]->post_content);
                $post_content = str_replace("<!-- /wp:paragraph -->", "", $post_content);
                if (strlen($post_content)>128){
                    $news_posts[$i]->preview = substr($post_content, 0, 128)." ...";
                } else {
                    $news_posts[$i]->preview = $post_content;
                }

            }
        }
        $data["news"]=$news_posts;
        $data["translator"]=Translator::all()->toArray();

        return new TimberResponse('views/templates/home.twig', [ "webInfo"=>$webInfo, "data" => $data]);
    }

    public function project(ServerRequest $request){

        $webInfo=WebManager::get($request);
        $webInfo["route"]="projekt";
        $news_posts = Timber::get_posts(array(
                'category_name' => 'News',
                'status' => 'publish',
                'posts_per_page' => '3',
                'order'          => 'DESC',
                'orderby'        => 'date',
            )
        );
        for ($i=0;$i<count($news_posts);$i++ ){
            $news_posts[$i]->thumbnail = $news_posts[$i]->thumbnail()->src("medium");
            if ($news_posts[$i]->post_excerpt!=""){
                $news_posts[$i]->preview = $news_posts[$i]->post_excerpt;
            } else {
                $post_content = str_replace("<!-- wp:paragraph -->", "", $news_posts[$i]->post_content);
                $post_content = str_replace("<!-- /wp:paragraph -->", "", $post_content);
                if (strlen($post_content)>128){
                    $news_posts[$i]->preview = substr($post_content, 0, 128)." ...";
                } else {
                    $news_posts[$i]->preview = $post_content;
                }

            }
        }
        $data["news"]=$news_posts;
        $data["translator"]=Translator::all()->toArray();

        return new TimberResponse('views/templates/project.twig', [ "webInfo"=>$webInfo, "data" => $data]);
    }

    public function news(ServerRequest $request){

        $webInfo=WebManager::get($request);
        $webInfo["route"]="aktuelles";
        $news_posts = Timber::get_posts(array(
                'category_name' => 'News',
                'status' => 'publish',
                'order'          => 'DESC',
                'orderby'        => 'date',
            )
        );
        error_log(print_r(Post::all(), true));
        for ($i=0;$i<count($news_posts);$i++ ){
            $news_posts[$i]->thumbnail = $news_posts[$i]->thumbnail()->src("medium");

            if ($news_posts[$i]->post_excerpt!=""){
                $news_posts[$i]->preview = $news_posts[$i]->post_excerpt;
            } else {
                $post_content = str_replace("<!-- wp:paragraph -->", "", $news_posts[$i]->post_content);
                $post_content = str_replace("<!-- /wp:paragraph -->", "", $post_content);
                $news_posts[$i]->preview = $post_content;


            }
        }
        $data["news"]=$news_posts;


        return new TimberResponse('views/templates/news.twig', [ "webInfo"=>$webInfo, "data" => $data]);
    }


    public function index(ServerRequest $request){
        $webInfo=WebManager::get($request);
        $webInfo["route"]="index";
        $toc=Work::with("booksNoChunks")->get();
        return new TimberResponse('views/templates/index.twig', [ "webInfo"=>$webInfo, "toc" => $toc]);
    }
    public function corpus(ServerRequest $request){
        $webInfo=WebManager::get($request);
        $webInfo["route"]="corpus";
        $toc=Work::with("booksNoChunks")->get();
        return new TimberResponse('views/templates/corpus.twig', [ "webInfo"=>$webInfo, "toc" => $toc]);
    }


    /**
     * @param ServerRequest $request
     * @param $workId
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function work (ServerRequest $request, $workId){
        $webInfo=WebManager::get($request);
        //$works = Output::create($workId);
        //$toc = Work::with("books")->get();
        $works = array();
        $toc = array();
        try {
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo, "works"=>$works, "toc"=>$toc]);
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
        $webInfo["route"]="book";
        $works=Output::create($workId, $bookId);
        $toc = Work::with("books")->get();
        try {
            return new TimberResponse('views/templates/viewer.twig', [ "webInfo"=>$webInfo, "works"=>$works, "toc"=>$toc]);
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
        $webInfo["route"]="chapter";
        $works=Output::create($workId, $bookId, $chapterId);
        $toc = Work::with("booksNoChunks")->get();
        // OUTSOURCING
        $book = Book::where("project_id", "=", $bookId)->first()->toArray();
        $chapters=Chapter::where("book_id", "=", $book["id"])->with("book")->get()->toArray();

        $webInfo["navigation"]=array();
        $webInfo["navigation"]["firstChapter"]=$chapters[0];
        $webInfo["navigation"]["lastChapter"]=$chapters[count($chapters)-1];
        foreach ($chapters as $chapter){
            if ($chapter["project_id"]==$chapterId){
                $webInfo["navigation"]["currentChapter"]=$chapter;
            }
        }
        if ($webInfo["navigation"]["currentChapter"]["order"]!=0){
            $webInfo["navigation"]["prevChapter"]=$chapters[$webInfo["navigation"]["currentChapter"]["order"]-1];
        } else {
            $webInfo["navigation"]["prevChapter"]=$chapters[$webInfo["navigation"]["currentChapter"]["order"]];
        }
        if ($webInfo["navigation"]["currentChapter"]["order"]!=count($chapters)-1){
            $webInfo["navigation"]["nextChapter"]=$chapters[$webInfo["navigation"]["currentChapter"]["order"]+1];
        } else {
            $webInfo["navigation"]["nextChapter"]=$chapters[$webInfo["navigation"]["currentChapter"]["order"]];
        }

        $webInfo["route_title"]=$webInfo["navigation"]["currentChapter"]["book"]["thomas_id"].", ".$webInfo["navigation"]["currentChapter"]["thomas_id"]." - ".$webInfo["navigation"]["currentChapter"]["title_lat"];
        //END OF OUTSOURCING
        try {
            return new TimberResponse('views/templates/viewer.twig', [ "webInfo"=>$webInfo, "works"=>$works, "toc"=>$toc]);
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
        $works=array();
        $toc = array();
        try {
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo, "works"=>$works, "toc"=>$toc]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }










}

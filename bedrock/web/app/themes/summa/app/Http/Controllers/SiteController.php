<?php

namespace App\Http\Controllers;
use App\Http\Models\Translator;
use App\Http\Models\Work;
use App\Http\Operations\Output;
use App\Http\Operations\WebManager;


use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;

use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Rareloop\Lumberjack\Http\ServerRequest;

use Timber\Timber;



class SiteController extends StandardController
{



    public function home(ServerRequest $request){

        $webInfo=WebManager::get($request);
        $toc = Work::with("books")->get();
        #error_log(print_r(wp_get_current_user(), true));

        $news_posts = Timber::get_posts(array(
            'category_name' => 'News',
                'status' => 'publish',
                'posts_per_page' => '3',
                'order'          => 'DESC',
                'orderby'        => 'date',
        )
        );
        error_log(print_r($news_posts, true));
        for ($i=0;$i<count($news_posts);$i++ ){
            error_log(print_r($news_posts[$i]->thumbnail, true));
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
        error_log(print_r($data["translator"], true));

        return new TimberResponse('views/templates/home.twig', [ "webInfo"=>$webInfo, "data" => $data]);
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
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function book(ServerRequest $request, $workId, $bookId){
        $webInfo=WebManager::get($request);
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
        $works=Output::create($workId, $bookId, $chapterId);
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
     * @param $articleId
     * @return TimberResponse
     * @throws TwigTemplateNotFoundException
     */
    public function article(ServerRequest $request, $workId, $bookId, $chapterId, $articleId){
        $webInfo=WebManager::get($request);
        $works=Output::create($workId, $bookId, $chapterId, $articleId);
        $toc = Work::with("books")->get();
        try {
            return new TimberResponse('views/templates/viewer.twig', [ "webInfo"=>$webInfo, "works"=>$works, "toc"=>$toc]);
        }
        catch (TwigTemplateNotFoundException $e){
            return new TimberResponse('views/templates/errors/404.twig', [ "webInfo"=>$webInfo]);
        }
    }










}

<?php


namespace App\Http\Operations;


use App\Http\Models\Article;
use App\Http\Models\Book;
use App\Http\Models\Chapter;
use App\Http\Models\Chunk;
use App\Http\Models\Work;
use Michelf\MarkdownExtra;

class Output
{

    public static function create($workId, $bookId=null, $chapterId=null, $articleId=null){

        $works = Work::where("project_id", "=", $workId)->get()->toArray();

        for ($w=0; $w < count($works); $w++){
            if (isset($bookId)){
                $works[$w]["books"]=self::getBooks($works[$w], $bookId);
            } else {
                $works[$w]["books"]=self::getAllBooks($works[$w]);
            }
            for ($b=0; $b < count($works[$w]["books"]); $b++){
                if (isset($chapterId)){
                    $works[$w]["books"][$b]["chapters"]=self::getChapters( $works[$w]["books"][$b]["id"], $chapterId);
                } else {
                    $works[$w]["books"][$b]["chapters"]=self::getAllChapters( $works[$w]["books"][$b]["id"]);
                }
                for ($c=0; $c < count($works[$w]["books"][$b]["chapters"]); $c++){
                    if (isset($articleId)){
                        $works[$w]["books"][$b]["chapters"][$c]["articles"]=self::getArticles( $works[$w]["books"][$b]["chapters"][$c]["id"], $articleId);
                    } else {
                        $works[$w]["books"][$b]["chapters"][$c]["articles"]=self::getAllArticles( $works[$w]["books"][$b]["chapters"][$c]["id"]);
                    }
                    for ($a=0; $a < count($works[$w]["books"][$b]["chapters"][$c]["articles"]); $a++){
                        $works[$w]["books"][$b]["chapters"][$c]["articles"][$a]["chunks"]=self::getAllChunks( $works[$w]["books"][$b]["chapters"][$c]["articles"][$a]["id"]);
                    }
                }
            }
        }

        return $works;
    }

    private static function getBooks($work, $bookId){
        return Book::where("work_id", "=", $work["id"])->where("project_id", "=", $bookId)->get()->toArray();
    }

    private static function getAllBooks($work){
        return Book::where("work_id", "=", $work["id"])->get()->toArray();
    }


    private static function getChapters($bookId, $chapterId){
        return Chapter::where("book_id", "=", $bookId)->where("project_id", "=", $chapterId)->get()->toArray();
    }

    private static function getAllChapters($bookId){

        return Chapter::where("book_id", "=", $bookId)->get()->toArray();
    }

    private static function getArticles($chapterId, $articleId){
        return Article::where("chapter_id", "=", $chapterId)->where("project_id", "=", $articleId)->get()->toArray();
    }

    private static function getAllArticles($chapterId){
        return Article::where("chapter_id", "=", $chapterId)->get()->toArray();
    }


    /**
     * unused
     * @param $articleId
     * @return mixed
     */
    private static function getAllChunks($articleId){
        $chunks = Chunk::where("article_id", "=", $articleId)->get()->toArray();
        for ($i=0; $i < count($chunks); $i++){
            $chunks[$i]["text_lat"]=MarkdownExtra::defaultTransform($chunks[$i]["text_lat"]);
            $chunks[$i]["text_ger"]=MarkdownExtra::defaultTransform($chunks[$i]["text_ger"]);
        }
        return $chunks;

    }


    private static function render($works){
        for ($w=0; $w < count($works); $w++){
            for ($b=0; $b < count($works[$w]["books"]); $b++){
                for ($c=0; $c < count($works[$w]["books"][$b]["chapters"]); $c++){
                    for ($a=0; $a < count($works[$w]["books"][$b]["chapters"][$c]["articles"]); $a++){
                        for ($p=0; $p < count($works[$w]["books"][$b]["chapters"][$c]["articles"][$a]["chunks"]); $p++){
                            $works[$w]["books"][$b]["chapters"][$c]["articles"][$a]["chunks"][$p]["text_lat"]=MarkdownExtra::defaultTransform($works[$w]["books"][$b]["chapters"][$c]["articles"][$a]["chunks"][$p]["text_lat"]);
                            $works[$w]["books"][$b]["chapters"][$c]["articles"][$a]["chunks"][$p]["text_ger"]=MarkdownExtra::defaultTransform($works[$w]["books"][$b]["chapters"][$c]["articles"][$a]["chunks"][$p]["text_ger"]);
                        }
                    }
                }
            }
        }
        return $works;
    }
}
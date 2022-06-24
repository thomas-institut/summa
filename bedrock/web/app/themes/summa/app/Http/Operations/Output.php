<?php


namespace App\Http\Operations;


use App\Http\Models\Article;
use App\Http\Models\Book;
use App\Http\Models\Chapter;
use App\Http\Models\Chunk;
use App\Http\Models\Work;
use Michelf\MarkdownExtra;

/**
 * Class Output
 * @package App\Http\Operations
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class Output
{

    /**
     * @param $workId
     * @param null $bookId
     * @param null $chapterId
     * @param null $articleId
     * @return mixed
     *
     * Gather the complete output from the database, filtered by id.
     */
    public static function create($workId, $bookId = null, $chapterId = null, $articleId = null)
    {

        $works = Work::where("project_id", "=", $workId)->get()->toArray();

        for ($w = 0; $w < count($works); $w++) {
            // If $bookId is given, just get all the lower units of specific book
            if (isset($bookId)) {
                $works[$w]["books"] = self::getBooks($works[$w], $bookId);
            } else {
                $works[$w]["books"] = self::getAllBooks($works[$w]);
            }
            for ($b = 0; $b < count($works[$w]["books"]); $b++) {
                // If $chapterId is given, just get all the lower units of specific chapter
                if (isset($chapterId)) {
                    $works[$w]["books"][$b]["chapters"] = self::getChapters($works[$w]["books"][$b]["id"], $chapterId);
                } else {
                    $works[$w]["books"][$b]["chapters"] = self::getAllChapters($works[$w]["books"][$b]["id"]);
                }
                for ($c = 0; $c < count($works[$w]["books"][$b]["chapters"]); $c++) {
                    // // If $articleId is given, just get all the chunks of the specific article
                    if (isset($articleId)) {
                        $works[$w]["books"][$b]["chapters"][$c]["articles"] = self::getArticles($works[$w]["books"][$b]["chapters"][$c]["id"], $articleId);
                    } else {
                        $works[$w]["books"][$b]["chapters"][$c]["articles"] = self::getAllArticles($works[$w]["books"][$b]["chapters"][$c]["id"]);
                    }
                    for ($a = 0; $a < count($works[$w]["books"][$b]["chapters"][$c]["articles"]); $a++) {
                        $works[$w]["books"][$b]["chapters"][$c]["articles"][$a]["chunks"] = self::getAllChunks($works[$w]["books"][$b]["chapters"][$c]["articles"][$a]["id"]);
                    }
                }
            }
        }

        return $works;
    }

    private static function getBooks($work, $bookId)
    {
        return Book::where("work_id", "=", $work["id"])->where("project_id", "=", $bookId)->get()->toArray();
    }

    private static function getAllBooks($work)
    {
        return Book::where("work_id", "=", $work["id"])->get()->toArray();
    }


    private static function getChapters($bookId, $chapterId)
    {
        return Chapter::with("translator")->where("book_id", "=", $bookId)->where("project_id", "=", $chapterId)->get()->toArray();
    }

    private static function getAllChapters($bookId)
    {

        return Chapter::where("book_id", "=", $bookId)->get()->toArray();
    }

    private static function getArticles($chapterId, $articleId)
    {
        return Article::where("chapter_id", "=", $chapterId)->where("project_id", "=", $articleId)->get()->toArray();
    }

    private static function getAllArticles($chapterId)
    {
        return Article::where("chapter_id", "=", $chapterId)->get()->toArray();
    }


    /**
     * @param $articleId
     * @return mixed
     * Get Chunks of an article by id and transform markdown to html
     */
    private static function getAllChunks($articleId)
    {
        $chunks = Chunk::where("article_id", "=", $articleId)->orderBy("project_id", "ASC")->get()->toArray();
        for ($i = 0; $i < count($chunks); $i++) {
            $chunks[$i]["text_lat_md"] = $chunks[$i]["text_lat"];
            $chunks[$i]["text_ger_md"] = $chunks[$i]["text_ger"];
            $chunks[$i]["text_lat"] = MarkdownExtra::defaultTransform($chunks[$i]["text_lat"]);
            $chunks[$i]["text_ger"] = MarkdownExtra::defaultTransform($chunks[$i]["text_ger"]);
        }
        return $chunks;

    }

}

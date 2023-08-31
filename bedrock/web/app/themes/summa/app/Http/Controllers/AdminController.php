<?php

namespace App\Http\Controllers;

use App\Http\Models\Book;
use App\Http\Models\Chapter;
use App\Http\Models\Chunk;
use App\Http\Models\ChunkBackup;
use App\Http\Models\Glossary_Item;
use App\Http\Models\Translator;
use App\Http\Models\Work;
use App\Http\Operations\Output;
use App\Http\Operations\WebManager;
use Michelf\MarkdownExtra;
use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Rareloop\Lumberjack\Http\ServerRequest;

/**
 * Class AdminController
 * @package App\Http\Controllers
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class AdminController extends StandardController
{
    public function home(ServerRequest $request)
    {
        $webInfo = WebManager::get($request);
        $toc = Work::with("booksNoChunks")->get();
        $books = Book::with("chaptersNoArticles")->get();
        $translators = Translator::all();
        $glossary["german"] = Glossary_Item::orderBy("name", "asc")->where("language", "=", "ger")->get();
        $glossary["latin"] = Glossary_Item::orderBy("name", "asc")->where("language", "=", "lat")->get();
        try {
            if ($webInfo["user"]->ID != 0) {
                if ($webInfo["user"]->user_status < 2) {
                    return new TimberResponse('views/templates/redaktion/redaktion.twig', ["webInfo" => $webInfo, "toc" => $toc, "books" => $books, "translators" => $translators, "glossary"=>$glossary]);
                } else {
                    return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
                }
            } else {
                return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
            }
        } catch (TwigTemplateNotFoundException $e) {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }

    public function chapter(ServerRequest $request, $chapterId)
    {
        $webInfo = WebManager::get($request);
        $chapter = Chapter::where("id", "=", $chapterId)->with("book", "translator")->first();
        $translators = Translator::all();

        try {
            if ($webInfo["user"]->ID != 0) {
                if ($webInfo["user"]->user_status < 2) {
                    return new TimberResponse('views/templates/redaktion/redaktion.chapter.twig', ["webInfo" => $webInfo, "chapter" => $chapter, "translators" => $translators]);
                } else {
                    return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
                }
            } else {
                return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
            }
        } catch (TwigTemplateNotFoundException $e) {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }

    }

    public function setChapter(ServerRequest $request, $chapterId)
    {
        $webInfo = WebManager::get($request);
        $chapter = Chapter::find($chapterId);
        $chapter->translation_status = $request->getParsedBody()["translation_status"];
        $chapter->translator_id = $request->getParsedBody()["translator_id"];
        $chapter->save();
        $webInfo["chunk_change"] = true;
        $chapter = Chapter::where("id", "=", $chapterId)->with("book", "translator")->first();
        $translators = Translator::all();
        try {
            if ($webInfo["user"]->ID != 0) {
                if ($webInfo["user"]->user_status < 2) {
                    return new TimberResponse('views/templates/redaktion/redaktion.chapter.twig', ["webInfo" => $webInfo, "chapter" => $chapter, "translators" => $translators]);
                } else {
                    return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
                }
            } else {
                return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
            }
        } catch (TwigTemplateNotFoundException $e) {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }

    public function setTranslator(ServerRequest $request, $translatorId)
    {
        $webInfo = WebManager::get($request);
        $translator = Translator::find($translatorId);
        $translator->first_name = $request->getParsedBody()["first_name"];
        $translator->last_name = $request->getParsedBody()["last_name"];
        $translator->email = $request->getParsedBody()["email"];
        $translator->title = $request->getParsedBody()["title"];
        $translator->institution = $request->getParsedBody()["institution"];
        $translator->save();
        $webInfo["chunk_change"] = true;
        $translators = Translator::all();
        try {
            if ($webInfo["user"]->ID != 0) {
                if ($webInfo["user"]->user_status < 2) {
                    return new TimberResponse('views/templates/redaktion/redaktion.twig', ["webInfo" => $webInfo, "translators" => $translators, "translatorId" => $translatorId]);
                } else {
                    return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
                }
            } else {
                return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
            }
        } catch (TwigTemplateNotFoundException $e) {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }

    public function createTranslator(ServerRequest $request)
    {
        $webInfo = WebManager::get($request);
        $translator = new Translator;
        $translator->first_name = $request->getParsedBody()["first_name"];
        $translator->last_name = $request->getParsedBody()["last_name"];
        $translator->email = $request->getParsedBody()["email"];
        $translator->title = $request->getParsedBody()["title"];
        $translator->institution = $request->getParsedBody()["institution"];
        $translator->save();
        $webInfo["chunk_change"] = true;
        $translators = Translator::all();
        try {
            if ($webInfo["user"]->ID != 0) {
                if ($webInfo["user"]->user_status < 2) {
                    return new TimberResponse('views/templates/redaktion/redaktion.twig', ["webInfo" => $webInfo, "translators" => $translators]);
                } else {
                    return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
                }
            } else {
                return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
            }
        } catch (TwigTemplateNotFoundException $e) {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }

    public function createGlossaryItem(ServerRequest $request){
        $webInfo = WebManager::get($request);
        $glossaryItem = new Glossary_Item;
        $glossaryItem->name = $request->getParsedBody()["name"];
        $glossaryItem->language = $request->getParsedBody()["language"];
        $glossaryItem->genus = $request->getParsedBody()["genus"];
        if (isset($request->getParsedBody()["notes"])){
            $glossaryItem->notes = $request->getParsedBody()["notes"];
        }
        $glossaryItem->save();
        $webInfo["glossary_change"] = true;
        $glossary["german"] = Glossary_Item::orderBy("name", "asc")->where("language", "=", "ger")->get();
        $glossary["latin"] = Glossary_Item::orderBy("name", "asc")->where("language", "=", "lat")->get();
        try {
            if ($webInfo["user"]->ID != 0) {
                if ($webInfo["user"]->user_status < 2) {
                    return new TimberResponse('views/templates/redaktion/redaktion.twig', ["webInfo" => $webInfo, "glossary"=>$glossary]);
                } else {
                    return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
                }
            } else {
                return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
            }
        } catch (TwigTemplateNotFoundException $e) {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }

    public function article(ServerRequest $request, $workId, $bookId, $chapterId, $articleId, $mode)
    {
        $webInfo = WebManager::get($request);
        $webInfo["mode"]=$mode;
        $webInfo["location"]= [
            "workId" => $workId,
            "bookId" => $bookId,
            "chapterId" => $chapterId,
            "articleId" => $articleId
        ];
        $works = Output::create($workId, $bookId, $chapterId, $articleId);
        try {
            if ($webInfo["user"]->ID != 0) {
                if ($webInfo["user"]->user_status < 2) {
                    return new TimberResponse('views/templates/redaktion/redaktion.article.twig', ["webInfo" => $webInfo, "works" => $works]);
                } else {
                    return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
                }
            } else {
                return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
            }


        } catch (TwigTemplateNotFoundException $e) {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }

    }

    public function getPreview(ServerRequest $request)
    {
        $chunk["id"] = $request->query()["chunk"];
        $preview = MarkdownExtra::defaultTransform($request->getParsedBody()["input-preview"]);
        return new TimberResponse('views/templates/redaktion/redaktion.article.preview.twig', ["preview" => $preview, "chunk" => $chunk]);
    }

    public function setChunk(ServerRequest $request, $workId, $bookId, $chapterId, $articleId, $mode)
    {
        $webInfo = WebManager::get($request);
        $webInfo["location"]= [
            "workId" => $workId,
            "bookId" => $bookId,
            "chapterId" => $chapterId,
            "articleId" => $articleId
        ];
        $works = Output::create($workId, $bookId, $chapterId, $articleId);
        $chunk = Chunk::find($request->getParsedBody()["chunk_id"]);
        if ($mode=="translation"){
            $old_md = $chunk->text_ger;
            $chunk->text_ger = $request->getParsedBody()["chunk_text"];
            $backupField = "text_ger";
        } else {
            $old_md = $chunk->text_lat;
            $chunk->text_lat = $request->getParsedBody()["chunk_text"];
            $backupField = "text_lat";
        }
        $chunk->save();
        $webInfo["chunk_change"] = true;
        $chunkBackup = new ChunkBackup;
        $chunkBackup->chunk_id = $request->getParsedBody()["chunk_id"];
        $chunkBackup->uid = get_current_user_id();
        $chunkBackup->old_md = $old_md;
        $chunkBackup->new_md = $request->getParsedBody()["chunk_text"];
        $chunkBackup->field = $backupField;
        $chunkBackup->save();
        try {
            if ($webInfo["user"]->ID != 0) {
                if ($webInfo["user"]->user_status < 2) {
                    return new TimberResponse('views/templates/redaktion/redaktion.article.twig', ["webInfo" => $webInfo, "works" => $works]);
                } else {
                    return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
                }
            } else {
                return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
            }


        } catch (TwigTemplateNotFoundException $e) {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }

    public function createChunk(ServerRequest $request, $workId, $bookId, $chapterId, $articleId, $mode){
        $webInfo = WebManager::get($request);
        $webInfo["location"]= [
            "workId" => $workId,
            "bookId" => $bookId,
            "chapterId" => $chapterId,
            "articleId" => $articleId
        ];
        error_log(print_r($request->getParsedBody(), true));
        $chunk = new Chunk();
        $chunk -> article_id = $request->getParsedBody()["article_id"];
        $chunk -> project_id = $request->getParsedBody()["project_id"];
        $chunk -> thomas_id = $request->getParsedBody()["thomas_id"];
        $chunk -> save();



        $works = Output::create($workId, $bookId, $chapterId, $articleId);

        try {
            if ($webInfo["user"]->ID != 0) {
                if ($webInfo["user"]->user_status < 2) {
                    return new TimberResponse('views/templates/redaktion/redaktion.article.twig', ["webInfo" => $webInfo, "works" => $works]);
                } else {
                    return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
                }
            } else {
                return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
            }


        } catch (TwigTemplateNotFoundException $e) {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }
}

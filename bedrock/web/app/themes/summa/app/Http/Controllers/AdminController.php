<?php

namespace App\Http\Controllers;

use App\Http\Models\Book;
use App\Http\Models\Chapter;
use App\Http\Models\Chunk;
use App\Http\Models\ChunkBackup;
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
        try {
            if ($webInfo["user"]->ID != 0) {
                if ($webInfo["user"]->user_status < 2) {
                    return new TimberResponse('views/templates/redaktion/redaktion.twig', ["webInfo" => $webInfo, "toc" => $toc, "books" => $books, "translators" => $translators]);
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


    public function article(ServerRequest $request, $workId, $bookId, $chapterId, $articleId)
    {
        $webInfo = WebManager::get($request);
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

    public function setChunk(ServerRequest $request, $workId, $bookId, $chapterId, $articleId)
    {
        $webInfo = WebManager::get($request);
        $works = Output::create($workId, $bookId, $chapterId, $articleId);
        $chunk = Chunk::find($request->getParsedBody()["chunk_id"]);
        $old_md = $chunk->text_ger;
        $chunk->text_ger = $request->getParsedBody()["chunk_text"];
        $chunk->save();
        $webInfo["chunk_change"] = true;
        $chunkBackup = new ChunkBackup;
        $chunkBackup->chunk_id = $request->getParsedBody()["chunk_id"];
        $chunkBackup->uid = get_current_user_id();
        $chunkBackup->old_md = $old_md;
        $chunkBackup->new_md = $request->getParsedBody()["chunk_text"];
        $chunkBackup->field = "text_ger";
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
}

<?php

namespace App\Http\Controllers;

use App\Http\Models\Book;
use App\Http\Models\Chapter;
use App\Http\Models\Chunk;
use App\Http\Models\ChunkBackup;
use App\Http\Models\Glossary_Item;
use App\Http\Models\Glossary_Relation;
use App\Http\Models\Translator;
use App\Http\Models\Work;
use App\Http\Operations\Output;
use App\Http\Operations\WebManager;
use Laminas\Diactoros\Response\JsonResponse;
use Michelf\MarkdownExtra;
use MongoDB\Driver\Server;
use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Rareloop\Lumberjack\Http\ServerRequest;

/**
 * Class AdminController
 * @package App\Http\Controllers
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class GlossaryItemController extends StandardController
{

    public function public(ServerRequest $request, $name) {
        $webInfo = WebManager::get($request);
        $glossaryItem = Glossary_Item::with('relations')->where("name", "=", $name)->get();
        if (count($glossaryItem)==1){
            $glossaryItem = $glossaryItem[0];
            return new TimberResponse('views/templates/glossary-item.twig', ["webInfo" => $webInfo, "glossaryItem"=>$glossaryItem]);

        } elseif (count($glossaryItem)==0){
            return new JsonResponse(["webInfo" => $webInfo, "error"=>"more than 2 items"]);
        } else {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }
    public function show(ServerRequest $request, $name) {
        $webInfo = WebManager::get($request);
        $glossaryItem = Glossary_Item::with('relations')->where("name", "=", $name)->get();
        $glossaryItems["german"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "ger")->get();
        $glossaryItems["latin"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "lat")->get();
        if (count($glossaryItem)==1){
            $glossaryItem = $glossaryItem[0];
            return new TimberResponse('views/templates/redaktion/redaktion.glossary-item.twig', ["webInfo" => $webInfo, "glossaryItem"=>$glossaryItem, "glossaryItems"=>$glossaryItems]);

        } elseif (count($glossaryItem)==0){
            return new JsonResponse(["webInfo" => $webInfo, "error"=>"more than 2 items"]);
        } else {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }

    public function createRelation(ServerRequest $request){
        $webInfo = WebManager::get($request);
        try {
            $glossaryRelation = new Glossary_Relation;
            $glossaryRelation->subject_id = intval($request->getParsedBody()["subject_id"]);
            $glossaryRelation->object_id = intval($request->getParsedBody()["object_id"]);
            $glossaryRelation->relation_type = $request->getParsedBody()["relation_type"];
            $glossaryRelation->score=intval($request->getParsedBody()["score"]);
            $glossaryRelation->notes=$request->getParsedBody()["notes"];
            $glossaryRelation->save();
            $webInfo["alert"]=1;
        } catch (\ErrorException $e){
            $webInfo["alert"]=2;
        }

        $glossaryItem = Glossary_Item::with('relations')->where("id", "=", $request->getParsedBody()["subject_id"])->get();
        $glossaryItems["german"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "ger")->get();
        $glossaryItems["latin"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "lat")->get();
        if (count($glossaryItem)==1){
            $glossaryItem = $glossaryItem[0];
            return new TimberResponse('views/templates/redaktion/redaktion.glossary-item.twig', ["webInfo" => $webInfo, "glossaryItem"=>$glossaryItem, "glossaryItems"=>$glossaryItems]);

        } elseif (count($glossaryItem)==0){
            return new JsonResponse(["webInfo" => $webInfo, "error"=>"more than 2 items"]);
        } else {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }
}

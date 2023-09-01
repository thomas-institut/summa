<?php

namespace App\Http\Controllers;


use App\Http\Models\Glossary_Item;
use App\Http\Models\Glossary_Relation;

use App\Http\Operations\WebManager;
use Laminas\Diactoros\Response\JsonResponse;
use Michelf\MarkdownExtra;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Rareloop\Lumberjack\Http\ServerRequest;

/**
 * Class AdminController
 * @package App\Http\Controllers
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class GlossaryItemController extends StandardController
{

    public function all(ServerRequest $request){
        $webInfo = WebManager::get($request);
        $glossaryItems["german"] = Glossary_Item::orderBy("name", "ASC")->where("language", "=", "ger")->get();
        $glossaryItems["latin"] = Glossary_Item::orderBy("name", "ASC")->where("language", "=", "lat")->get();
        return new TimberResponse('views/templates/glossary.twig', ["webInfo" => $webInfo, "glossaryItems"=>$glossaryItems]);
    }


    public function public(ServerRequest $request, $name) {
        $webInfo = WebManager::get($request);
        $glossaryItem = Glossary_Item::with('relations')->where("name", "=", $name)->get();
        if (count($glossaryItem)==1){
            $glossaryItem = $glossaryItem[0];
            $glossaryItem["md_notes"]=MarkdownExtra::defaultTransform($glossaryItem["notes"]);
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
            $glossaryItem["md_notes"]=MarkdownExtra::defaultTransform($glossaryItem["notes"]);
            return new TimberResponse('views/templates/redaktion/redaktion.glossary-item.twig', ["webInfo" => $webInfo, "glossaryItem"=>$glossaryItem, "glossaryItems"=>$glossaryItems]);

        } elseif (count($glossaryItem)==0){
            return new JsonResponse(["webInfo" => $webInfo, "error"=>"more than 2 items"]);
        } else {
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }

    public function update(ServerRequest $request, $name){
        $webInfo = WebManager::get($request);
        $glossaryItem = Glossary_Item::where("name", "=", $name)->first();
        $glossaryItem->name = $request->post()["name"];
        $glossaryItem->language = $request->post()["language"];
        $glossaryItem->genus = $request->post()["genus"];
        if (isset($request->post()["notes"])){
            $glossaryItem->notes = $request->post()["notes"];
        }
        $glossaryItem->save();

        $glossaryItem = Glossary_Item::with('relations')->where("name", "=", $name)->get();
        $glossaryItem["md_notes"]=MarkdownExtra::defaultTransform($glossaryItem[0]["notes"]);
        $glossaryItems["german"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "ger")->get();
        $glossaryItems["latin"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "lat")->get();
        if (count($glossaryItem)==1){
            $glossaryItem = $glossaryItem[0];
            $glossaryItem["md_notes"]=MarkdownExtra::defaultTransform($glossaryItem["notes"]);
            error_log(print_r($glossaryItem, true));
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
            $glossaryRelation->subject_id = intval($request->post()["subject_id"]);
            $glossaryRelation->object_id = intval($request->post()["object_id"]);
            $glossaryRelation->relation_type = $request->post()["relation_type"];
            $glossaryRelation->score=intval($request->post()["score"]);
            $glossaryRelation->notes=$request->post()["notes"];
            $glossaryRelation->save();
            $webInfo["alert"]=1;
        } catch (\ErrorException $e){
            $webInfo["alert"]=2;
        }

        $glossaryItem = Glossary_Item::with('relations')->where("id", "=", $request->post()["subject_id"])->get();
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

<?php

namespace App\Http\Controllers;


use App\Http\Models\Glossary_Item;
use App\Http\Models\Glossary_Relation;

use App\Http\Operations\WebManager;
use Laminas\Diactoros\Response\JsonResponse;
use Michelf\MarkdownExtra;
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

    public function all(ServerRequest $request){
        $webInfo = WebManager::get($request);
        $glossaryItems["german"] = Glossary_Item::orderBy("name", "ASC")->where("language", "=", "ger")->get();
        $glossaryItems["latin"] = Glossary_Item::orderBy("name", "ASC")->where("language", "=", "lat")->get();

        return new TimberResponse('views/templates/glossary.twig', ["webInfo" => $webInfo, "glossaryItems"=>$glossaryItems]);


    }


    public function public(ServerRequest $request, $name) {
        $webInfo = WebManager::get($request);
        $glossaryItem = Glossary_Item::with('relations')->where("name", "=", $name)->first();

        if (isset($glossaryItem["definition"]) && $glossaryItem["definition"] !== "") {
            $glossaryItem["md_definition"] = MarkdownExtra::defaultTransform($glossaryItem["definition"]);
        }
        if (isset($glossaryItem["references"]) && $glossaryItem["references"] !== "") {
            $glossaryItem["md_references"] = MarkdownExtra::defaultTransform($glossaryItem["references"]);
        }
        return new TimberResponse('views/templates/glossary-item.twig', ["webInfo" => $webInfo, "glossaryItem"=>$glossaryItem]);


    }
    public function show(ServerRequest $request, $name) {
        $webInfo = WebManager::get($request);
        $glossaryItem = Glossary_Item::with('relations')->where("name", "=", $name)->first();
        try {
            if (isset($glossaryItem["definition"]) && $glossaryItem["definition"] !== "") {
                $glossaryItem["md_definition"] = MarkdownExtra::defaultTransform($glossaryItem["definition"]);
            }
            if (isset($glossaryItem["references"]) && $glossaryItem["references"] !== "") {
                $glossaryItem["md_references"] = MarkdownExtra::defaultTransform($glossaryItem["references"]);
            }


            $glossaryItems["german"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "ger")->get();
            $glossaryItems["latin"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "lat")->get();

            try {
                if ($webInfo["user"]->ID != 0) {
                    if ($webInfo["user"]->user_status < 2) {
                        return new TimberResponse('views/templates/redaktion/redaktion.glossary-item.twig', ["webInfo" => $webInfo, "glossaryItem"=>$glossaryItem, "glossaryItems"=>$glossaryItems]);
                    } else {
                        return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
                    }
                } else {
                    return new TimberResponse('views/templates/errors/401.twig', ["webInfo" => $webInfo]);
                }
            } catch (TwigTemplateNotFoundException $e) {
                return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
            }



        } catch (\Exception $e){
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }

    public function update(ServerRequest $request, $name){
        try {
            $webInfo = WebManager::get($request);
            $glossaryItem = Glossary_Item::where("name", "=", $name)->first();
            $glossaryItem->name = $request->post()["name"];
            $glossaryItem->language = $request->post()["language"];
            if (isset($request->post()["definition"])){
                $glossaryItem->definition = $request->post()["definition"];
            }
            if (isset($request->post()["references"])){
                $glossaryItem->references = $request->post()["references"];
            }
            $glossaryItem->save();
            $webInfo["alert"]=1;
        } catch (\Exception $e) {
            $webInfo["alert"]=2;
        }


        $glossaryItem = Glossary_Item::with('relations')->where("name", "=", $name)->first();
        if (isset($glossaryItem["definition"]) && $glossaryItem["definition"] !== "") {
            $glossaryItem["md_definition"] = MarkdownExtra::defaultTransform($glossaryItem["definition"]);
        }
        if (isset($glossaryItem["references"]) && $glossaryItem["references"] !== "") {
            $glossaryItem["md_references"] = MarkdownExtra::defaultTransform($glossaryItem["references"]);
        }


        $glossaryItems["german"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "ger")->get();
        $glossaryItems["latin"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "lat")->get();

        return new TimberResponse('views/templates/redaktion/redaktion.glossary-item.twig', ["webInfo" => $webInfo, "glossaryItem"=>$glossaryItem, "glossaryItems"=>$glossaryItems]);


    }

    public function createRelation(ServerRequest $request){
        $webInfo = WebManager::get($request);
        try {
            $glossaryRelation = new Glossary_Relation;
            $glossaryRelation->subject_id = intval($request->post()["subject_id"]);
            $glossaryRelation->object_id = intval($request->post()["object_id"]);
            $glossaryRelation->save();
            $webInfo["alert"]=1;
        } catch (\ErrorException $e){
            $webInfo["alert"]=2;
        }

        try {
            if (isset($glossaryItem["definition"]) && $glossaryItem["definition"] !== "") {
                $glossaryItem["md_definition"] = MarkdownExtra::defaultTransform($glossaryItem["definition"]);
            }
            if (isset($glossaryItem["references"]) && $glossaryItem["references"] !== "") {
                $glossaryItem["md_references"] = MarkdownExtra::defaultTransform($glossaryItem["references"]);
            }


            $glossaryItems["german"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "ger")->get();
            $glossaryItems["latin"]=Glossary_Item::orderBy("name", "asc")->where("language", "=", "lat")->get();

            return new TimberResponse('views/templates/redaktion/redaktion.glossary-item.twig', ["webInfo" => $webInfo, "glossaryItem"=>$glossaryItem, "glossaryItems"=>$glossaryItems]);
        } catch (\Exception $e){
            return new TimberResponse('views/templates/errors/404.twig', ["webInfo" => $webInfo]);
        }
    }
}

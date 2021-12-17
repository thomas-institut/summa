<?php


namespace App\Http\Operations;

use Rareloop\Lumberjack\Http\ServerRequest;

/**
 * Class WebManager
 * @package App\Http\Operations
 * @author Mark Eschweiler - Universität zu Köln / Thomas-Institut
 */
class WebManager
{
    public static function get(ServerRequest $request)
    {
        $webInfo["login"] = false;
        $webInfo["baseurl"] = $request->getServerParams()["WP_BASE"];
        if (isset($request->getQueryParams()["mode"])) {
            $webInfo["mode"] = $request->getQueryParams()["mode"];
        }


        if (isset($request->getServerParams()["HTTP_REFERER"])) {
            $webInfo["currentUrl"] = $request->getServerParams()["WP_HOME"] . $request->getServerParams()["REQUEST_URI"];
            setcookie("currentUrl", $request->getServerParams()["WP_HOME"] . $request->getServerParams()["REQUEST_URI"], 0, "", "", true);
        } else {
            $webInfo["currentUrl"] = $request->getServerParams()["WP_HOME"] . $request->getServerParams()["REQUEST_URI"];
            setcookie("currentUrl", $request->getServerParams()["WP_HOME"] . $request->getServerParams()["REQUEST_URI"], 0, "", "", true);
        }
        if (isset($_COOKIE["beta"])) {
            $webInfo["beta"] = $_COOKIE["beta"];
        }

        $webInfo["env"] = $_ENV["WP_ENV"];
        $webInfo["user"] = wp_get_current_user();

        return $webInfo;
    }
}
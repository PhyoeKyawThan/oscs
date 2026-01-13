<?php
include __DIR__.'/../config.php';
class Render extends Config
{
    private static string $current_uri = "";
    private static $routes = [];

    public static function setRoutes(array $routers)
    {
        foreach ($routers as $name => $router)
            Render::$routes = array_merge(Render::$routes, $router->routes());
    }

    public static function to()
    {
        Render::setCurrentUri();
        Render::replace();
    }

    private static function setCurrentUri()
    {
        Render::$current_uri =  $_SERVER['REQUEST_URI'];
    }
    private static function getCurrentRoute()
    {
        if (array_key_exists(Render::$current_uri, Render::$routes)) {
            return Render::$routes[Render::$current_uri];
        }
        return null;
    }
    private static function replace()
    {
        $current_route = Render::getCurrentRoute();
        if ($current_route) {
            ob_start();
            if (array_key_exists("datas",$current_route)){
                extract($current_route['datas']);
            }
            require $current_route['path'];
            ob_end_flush();
        } else {
            require __DIR__ . '/../errors/404.php';
        }
    }
}

<?php
// include __DIR__.'/../config.php';
class Render
{
    private static string $current_uri = "";
    private static $routes = [];
    private static string $argv = "";

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
        Render::$current_uri = $_SERVER['REQUEST_URI'];
    }

    private static function matchRegx()
    {
        foreach (Render::$routes as $name => $route) {
            if (isset($route['pattern'])) {
                $regex = preg_replace(
                    $route['pattern'],
                    '(?P<$1>[0-9]+)',
                    $name
                );
                $regex = '#^' . $regex . '$#';
                if (preg_match($regex, Render::$current_uri, $matches)) {
                    Render::$argv = $matches[1];
                    return $name;
                }
            }
        }

        return null;

    }
    private static function getCurrentRoute()
    {
        if (array_key_exists(Render::$current_uri, Render::$routes)) {
            return Render::$routes[Render::$current_uri];
        }
        $match_route = Render::matchRegx();
        return Render::matchRegx() ? Render::$routes[$match_route] : null;
    }
    private static function replace()
    {
        $current_route = Render::getCurrentRoute();
        if ($current_route) {
            ob_start();
            if (array_key_exists("datas", $current_route)) {
                $datas = &$current_route['datas'];
                if ($datas['callback']) {
                    $datas['callback'](Render::$argv, $datas);
                }
                extract($datas);
            }
            $path = $current_route['path'];
            require $current_route['baseTemplate'];
            ob_end_flush();
        } else {
            require __DIR__ . '/../errors/404.php';
        }
    }
}

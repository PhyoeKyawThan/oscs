<?php

class Router
{
    private string $basePath = "";
    private array $routes = [];
    public function __construct(string $basePath)
    {
        if (isset($basePath) && file_exists(__DIR__ . "/../" . $basePath)) {
            $this->basePath = __DIR__ . "/../" . $basePath;
        } else {
            throw new \Exception('Base path not found in your directory.');
        }
    }
    public function register(string $route, string $path, array $datas = [])
    {
        $pageName = $path;
        $path = $this->basePath . "/views/" . $path . ".php";
        if (file_exists($path)) {
            $this->routes[$route]['path'] = $path;
            if (!empty($datas)) {
                $this->routes[$route]['datas'] = $datas;
            }
            if ($route === "/") {
                $this->routes[""]['path'] = $path;
            }
        } else {
            // incase register route's php file gone wrong or not yet created
            $this->createNewFile($path, $pageName);
        }

        return $this;
    }

    private function createNewFile(string $path, string $pageName)
    {
        $data = [
            "pageName" => $pageName
        ];
        $default_new_template = file_get_contents(__DIR__ . '/../assets/base_tempate.html');
        $result = preg_replace_callback(
            '/\{\{\s*(\w+)\s*\}\}/', // match {{ var }}
            function ($matches) use ($data) {
                $key = $matches[1];
                return isset($data[$key]) ? $data[$key] : $matches[0];
            },
            $default_new_template
        );
        file_put_contents($path, $result);
    }



    public function routes()
    {
        return $this->routes;
    }
}

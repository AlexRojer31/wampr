<?php

class Router
{
    
    private string $routes_folder;
    private string $not_found_view;
    private array $routes = [];

    public function __construct(string $routes_foldder = "example", string $not_found_view = "")
    {
        $this->not_found_view = $not_found_view;
        $this->routes_folder = $routes_foldder;
        $this->autoload();
        $this->execute();
    }

    private function autoload() : void
    {
        spl_autoload_register(function($className) {
            $className = str_replace("\\","/", $className);
            if (file_exists($className.".php")) {
                include $className.".php";
            }
        });	
    }

    private function execute() : void
    {
        $this->routes = [];
        $this->search_routes($this->routes_folder);

        $uri = trim($_SERVER["REQUEST_URI"], "/");
        $uri = explode("?", $uri);
        $uri = $uri[0];

        $method = $_SERVER["REQUEST_METHOD"];
        
        foreach ($this->routes as $link) {
            $rc = new ReflectionClass(str_replace("/", "\\", str_replace(".php", "",$link)));
            if (str_contains($rc->getDocComment(), "@Route")) {
                preg_match("(@Route\(\".*\"\))", $rc->getDocComment(), $matches, PREG_OFFSET_CAPTURE);
                $route = trim(str_replace("@Route(\"", "", str_replace("\")", "", $matches[0][0])), "/");
                if ($route == $uri) {
                    $a = str_replace("/", "\\", str_replace(".php", "",$link));
                    $o =  new $a();
                    $o->$method();
                    return;
                }
            }
        }
        if (strlen($this->not_found_view) > 0) {
            require_once($this->not_found_view);
        } else {
            echo "<h1>404</h1><p>Resource not found. <a href=\"/\">go to start</a></p>";
        }
    }

    private function search_routes(String $dirrectory) : void
    {
		$dirrectoryCallBack = $dirrectory;
		$public = scandir($dirrectoryCallBack);
		foreach ($public as $value) {
			if ($value !== "." && $value !== "..") {
				$dirrectory = $dirrectoryCallBack."/".$value;
				if (is_file($dirrectory)) {
					$this->routes[] = str_replace("\\", "/", $dirrectory);
				} else {
					$this->search_routes($dirrectory);
				}
			}
		}
    }

}
?>
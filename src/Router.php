<?php
namespace App;

use AltoRouter;
use App\Security\ForbiddenException;
use Exception;

class Router{

    private $viewPath;

    private $router;

    public function __construct(string $viewPath)
    {  
        $this->viewPath=$viewPath;
        $this->router=new AltoRouter();
    }

    public function get(string $url, string $view, ?string $routeName=null):self
    {
        $this->router->map('GET',$url,$view,$routeName);
        return $this;
    }
    public function post(string $url, string $view, ?string $routeName=null):self
    {
        $this->router->map('POST',$url,$view,$routeName);
        return $this;
    }
    public function match(string $url, string $view, ?string $routeName=null):self
    {
        $this->router->map('POST|GET',$url,$view,$routeName);
        return $this;
    }

    public function run():self
    {
        $match=$this->router->match();
        if($match===false){
            $view='e404';
        }else{
            $view=$match['target'];
            $params=$match['params'];
        }
        $router=$this;
        $isAdmin=strpos($view,'admin/')!==false;
        $layout=$isAdmin ? 'admin/layouts/default' :'layouts/default';

        try{
            ob_start();
            require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
            $content=ob_get_clean();
            require $this->viewPath . DIRECTORY_SEPARATOR . $layout . '.php';
        }catch(ForbiddenException $e){
            header('Location: ' . $this->url('login') . '?forbidden=1');
            exit();
        }catch(Exception $e){
            ob_start();
            require $this->viewPath . DIRECTORY_SEPARATOR . "e404.php";
            $content=ob_get_clean();
            require $this->viewPath . DIRECTORY_SEPARATOR . $layout . ".php";
            exit();
        }
        return $this;
    }

    public function url(string $name,array $params=[])
    {
        return $this->router->generate($name,$params);
    }

}
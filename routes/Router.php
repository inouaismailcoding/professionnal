<?php 
namespace Router;
use Core\Exceptions\NotFoundException;


// On cree une classe router

class Router
{
    public $url; 
    public $routes=[];
    public $names=[];

    public function __construct($url)
    {$this->url=trim($url,"/"); }// on recupere tout les lien sans backslash
    
    public function get(string $path ,string $action)
    {
        // Recuperer tout les liens envoyer par le serveur
        $this->routes['GET'][]=new Route($path,$action);
    }
    public function post(string $path ,string $action)
    {
        // Recuperer tout les liens envoyer par le serveur
        $this->routes['POST'][]=new Route($path,$action);
    }

    public function run()
    {
        
        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route)
        {
            if($route->matches($this->url))
            {
                return $route->execute();
            }
        }
        throw new NotFoundException('La page est introuvable');
    }
    
}
?>
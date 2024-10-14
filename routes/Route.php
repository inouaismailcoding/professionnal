<?php 

namespace Router;

use Core\MYSQL_DB;

class Route 
{
    public $path;
    public $action;
    public $matches;
    public $names=[];

    // On creer une construct pour pouvoir recuperer le post et l'action
    public function __construct($path ,$action,$name=null)
    {
        $this->path=trim($path,"/");  // On retire les ('/') pour recuperer tout le lien 
        $this->action=$action;
        if (isset($name)) { $this->names[$name]="";}
        //echo "<p>Route: {$path} => {$action}</p>";  
    }

    // On cree une fonction matches pour verifie si on a recuperer le bon post qui va avec l'action
    public function matches(string $url)
    {
        // remplacement de : par / dans le lien pour pouvoir recuperer l'id
        $path=preg_replace('#:([\w]+)#','([^/]+)',$this->path);
        $pathToMatch="#^$path$#";

        if(preg_match($pathToMatch,$url,$matches))
        {
            $this->matches=$matches;
            return true;
            
        }
        else
        {return false;}

    }


    
    public function execute()
    {
        // explode nous separe la commande dans un tableau delimiter par @
    
        $params=explode("@",$this->action); 
        // On lance une nouvelle instance des enfants de controller "1er parametre"
        // Apres on instancie aussi la connection a la base de donnée a l'initialisation du controller

        $controller=new $params[0](new MYSQL_DB(DB_HOST,DB_USER,DB_NAME,DB_PASS));
        //"2eme parametre" la methode a executer
        $method=$params[1];
        // ON fait ternaire
        // si $this->matches:variable globale de route existe 
        //alors on execute l'action qui est dans blogcontroller 
        return isset($this->matches[1]) ? $controller->$method($this->matches[1]) : $controller->$method();
        
    }
}


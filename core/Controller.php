<?php
namespace Core;

use Core\MYSQL_DB;
// La classe mere de tout les classes ******Controller


class Controller
{

    private $db;
    //Comme tout les enfants de controller auront besoin de la connection
    public function __construct(MYSQL_DB $db)
    {
        if(session_status()==PHP_SESSION_NONE)
        {
            session_start();
        }
        //$this->db = new Model(new MYSQL_DB());
        $this->db=$db;
    }
    public function view(string $path, array $params = null)
    {
        // On commence par lancer le systeme de bevery 
        // les fonction de rappelle 
        ob_start();
        // on remplace les . par les directory_separator /
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);
        
        ob_clean();
        require VIEWS . $path . ".php";
        // On gere les vue qui ont un id en parametre

        $contents = ob_get_clean();
        require VIEWS . "layout.php";
    }
    public function getDB()
    {
        return $this->db;
    }
    protected function isConnect()
    {
        // On verifie si l'utilisateur est connecté ici il faudrait mettre ici les conditions de connexion
        if(isset($_SESSION['auth']) && $_SESSION['auth']==1){return true;}
        else{return header('location:'.HTDOCS.'/login');}
    }
    protected function isAdmin()
    {
        if($_SESSION['auth']==true){echo $_SESSION['role'];  }
    }

    // Récupérer l'adresse IP de l'utilisateur
    public function getUserIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
        
   
            
    public function getUserLocation() {
        $ip = $this->getUserIP();
    
        // Vérification IP locale
        if ($ip === '127.0.0.1' || strpos($ip, '192.168.') === 0) {
            return 'Lieu inconnu (IP locale)';
        }
    
        // Utilisation de cURL au lieu de file_get_contents
        $ch = curl_init("https://ipinfo.io/{$ip}/json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $locationData = curl_exec($ch);
        curl_close($ch);
    
        $location = json_decode($locationData, true);
        return isset($location['city']) ? $location['city'] . ', ' . $location['country'] : 'Lieu inconnu';
    }
    
}


?>
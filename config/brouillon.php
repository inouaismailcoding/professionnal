<?php
class SessionManager {
    private $session_lifetime; // En secondes

    public function __construct($lifetime = 1800) {
        $this->session_lifetime = $lifetime;
        session_start();

        // Vérification de la validité de la session
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $this->session_lifetime)) {
            $this->destroySession();
        }

        $_SESSION['LAST_ACTIVITY'] = time(); // Mise à jour de l'activité
    }

    // Enregistrer une valeur dans la session
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    // Obtenir une valeur de la session
    public function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    // Supprimer une clé dans la session
    public function delete($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    // Détruire la session
    public function destroySession() {
        session_unset();
        session_destroy();
    }
}

class CookieManager {
    private $cookie_lifetime; // En secondes

    public function __construct($lifetime = 86400) { // Valeur par défaut : 1 jour
        $this->cookie_lifetime = $lifetime;
    }

    // Créer un cookie
    public function set($name, $value) {
        setcookie($name, $value, time() + $this->cookie_lifetime, "/");
    }

    // Lire un cookie
    public function get($name) {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    // Supprimer un cookie
    public function delete($name) {
        setcookie($name, '', time() - 3600, "/");
    }
}

class CsrfTokenManager {
    private $token_lifetime; // En secondes

    public function __construct($lifetime = 600) { // 10 minutes par défaut
        $this->token_lifetime = $lifetime;
        session_start();
    }

    // Générer un token CSRF
    public function generateToken() {
        if (empty($_SESSION['csrf_token']) || $this->isTokenExpired()) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }

        return $_SESSION['csrf_token'];
    }

    // Vérifier si le token est valide
    public function verifyToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token) && !$this->isTokenExpired();
    }

    // Vérifier si le token a expiré
    private function isTokenExpired() {
        return isset($_SESSION['csrf_token_time']) && (time() - $_SESSION['csrf_token_time'] > $this->token_lifetime);
    }

    // Invalider le token
    public function invalidateToken() {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
    }
}

// Exemple d'utilisation :

// Gestion des sessions
$session = new SessionManager(1800); // Session valable 30 minutes
$session->set('username', 'john_doe');
echo $session->get('username');

// Gestion des cookies
$cookie = new CookieManager(86400 * 7); // Cookie valable 7 jours
$cookie->set('user_token', 'abc123');
echo $cookie->get('user_token');

// Gestion des tokens CSRF
$csrf = new CsrfTokenManager(600); // Token CSRF valable 10 minutes
$token = $csrf->generateToken();

// Vérification des tokens dans un formulaire POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($csrf->verifyToken($_POST['csrf_token'])) {
        // Le token est valide, traiter le formulaire
        echo "Formulaire soumis avec succès.";
    } else {
        // Token invalide, erreur
        echo "Erreur : Token CSRF invalide.";
    }
}

<?php

namespace Core;
use PDO;
use PDOException;
use Core\MYSQL_DB;
use Core\Logger;
use stdClass; use Exception;



class Model
{
    
    public $conn;
    public $_folder;
    public $databases;
    public $details;
    public $table;
    public $logger;
    // Constructeur de la classe
    public function __construct(MYSQL_DB $db) {
        $this->conn =$db;
        //$this->table=$table;
        $this->details = new stdClass();
        // Initialisation du logger avec les chemins personnalisés
        $this->logger = new Logger(ERROR_LOG_PATH, SQL_LOG_PATH);
    }




    // Méthode générique pour exécuter une requête SQL
    protected function executeQuery($query, $params = [], $single = null) {
        $d=$this->conn->executeQuery($query,$params,$single);
        return $d;
    }

    // executeQueryAlternative
    protected function query($sql,$params = [], $single = false){
        return $this->conn->executeQuery($sql,$params,$single);
    }
    

    
    // Méthode pour vérifier l'existence de clés étrangères
    public function isForeignKey($table) {
        $this->conn->isForeignKey($table);
    }

    // Construire une requête JOIN si des clés étrangères existent
    private function buildJoinQuery($columns, $table) {
        return $this->buildJoinQuery($columns, $table);
    }

    // Récupère les relations des tables
    public function getTableRelations(string $table) {
        return $this->conn->getTableRelations($table);
    }


    // Obtenir les détails complets d'une table
    public function getTableDetails( $table) {
        return $this->getTableDetails($table);
    }

    // Méthode pour obtenir le nombre de lignes d'une table
    private function getRowCount($table) {
        return $this->getRowCount($table);
    }

    // Méthode pour obtenir la dernière mise à jour
    private function getLastUpdate( $table) {
        $this->getLastUpdate($table);
    }

    // Méthode pour créer, mettre à jour et supprimer une ligne dans une table
    public function save_row($table, array $data, ?array $files = null) {
        $queryData = isset($data['id']) ? $this->buildUpdateQuery($table, $data) : $this->buildInsertQuery($table, $data, $files);
        return $this->executeSaveQuery($queryData, $table);
    }



    // Méthode pour exécuter la requête d'insertion ou de mise à jour
    private function executeSaveQuery(array $queryData, string $table) {
        if ($this->conn->getConnection() === null) {
            $stmt = $this->conn->getConnection()->prepare($queryData[0]);
        if ($stmt->execute($queryData[1])) {
            return [
                'data' => $this->fetchTable($table),
                'success' => true,
                'message' => isset($queryData[1]['id']) ? "Mise à jour réussie" : "Enregistrement réussi"
            ];
        }
        }else{
            $stmt = $this->conn->getConnection()->prepare($queryData[0]);
        if ($stmt->execute($queryData[1])) {
            return [
                'data' => $this->fetchTable($table),
                'success' => true,
                'message' => isset($queryData[1]['id']) ? "Mise à jour réussie" : "Enregistrement réussi"
            ];
        }
        }
        
        return ['success' => false, 'message' => "Erreur lors de l'opération"];
    }

    // Construction de la requête d'insertion
    private function buildInsertQuery($table, array $data, ?array $files = null) {
        return $this->conn->buildInsertQuery($table,$data,$files);

    }

    // Construction de la requête de mise à jour
    private function buildUpdateQuery($table, array $data) {
        return $this->conn->buildUpdateQuery($table,$data);
    }

    // Traitement des fichiers lors de l'insertion
    private function handleFiles(array $data, ?array $files = null) {
        return $this->conn->handleFiles($data,$files);
    }

    // Protection des données avant l'insertion ou la mise à jour
    private function sanitizeData($data) {
        return $this->conn->sanitizeData($data);
    }


    // Méthode pour récupérer les lignes d'une table
    public function fetchTable($table) {
        return $this->executeQuery("SELECT * FROM {$table} ORDER BY id DESC ")->data;
    }
    // Méthode pour récupérer les lignes d'une table
    public function get($table,$id) {
        return $this->executeQuery("SELECT * FROM {$table} WHERE id={$id}")->data;
    }
    // Méthode pour récupérer les lignes d'une table
    public function destroy_row($table,$id) {
        return $this->executeQuery("DELETE FROM {$table} WHERE id=:id",['id'=>$id])->data;
    }

    // Méthode pour récupérer les lignes d'une table
    public function update_row($table,$id) {
        return $this->executeQuery("DELETE FROM {$table} WHERE id=:id",['id'=>$id])->data;
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
    
        // Enregistrer une transaction avec action
    public function recordTransaction(string $table, INT $rowTableId, string $actionType,int $userId=null) {
        return $this->conn->recordTransaction($table,$rowTableId,$userId);
        }
        
    
}


### Points améliorés :
// 1. **Réutilisation des requêtes SQL** : Le code est plus concis et réutilise des méthodes génériques pour exécuter les requêtes SQL.
// 2. **Flexibilité** : La classe peut facilement être adaptée à d'autres bases de données en changeant simplement les paramètres d'initialisation.
// 3. **Modularité** : Chaque fonctionnalité (connexion, exécution de requêtes, gestion des fichiers) est bien isolée, ce qui rend le code plus facile à maintenir et à étendre.
// 4. **Commentaires clairs et organisation logique** : Le code est bien structuré et plus compréhensible avec des méthodes bien nommées.

// Ce script est maintenant plus optimal, extensible et facile à maintenir, en ligne avec vos préférences.

    
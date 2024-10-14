<?php

namespace Core;
use PDO;use Exception;
use PDOException;
use Core\Logger;
use stdClass;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Dompdf\Dompdf;

class MYSQL_DB
{

    public $host;
    public $pass;
    public $dbase;
    public $user;
    public $port;
    public $dsn;
    public $conn;
    public $logger;
    public $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND=>'SET CHARACTER SET UTF8'  
    ];

    // Constructeur de la classe
    public function __construct(string $host = DB_HOST, string $user = DB_USER, string $dbase = DB_NAME, string $pass = DB_PASS, $port = 3306) {
        $this->host =$host;
        $this->pass =$pass;
        $this->dbase =$dbase;
        $this->user =$user;
        $this->port =$port;
        $this->logger=new logger(ERROR_LOG_PATH, SQL_LOG_PATH);
        $this->getConnection();
    }

    public function getConnection()  {
        $this->dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbase}"; 
        try {
            $this->conn = new PDO($this->dsn, $this->user, $this->pass,$this->options);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            return $this->conn;

        } catch (PDOException $e) {
            $this->logger->logError($e->getMessage());
            die("[[ERREUR]] : " . $e->getMessage());
        }
    }

    public function executeQuery(string $sql,array $params=null,bool $single=null)
    {
        $method=is_null($params) ? 'query':'prepare';
        $fetch=is_null($single) ? 'fetchAll':'fetch';
        $messages=new stdClass;
        try {
            $stmt=$this->conn->$method($sql);
            if ($params=='query') {
                $data=$stmt->$fetch(PDO::FETCH_OBJ);
            }else{
                $stmt->execute($params);
                $data=$stmt->$fetch(PDO::FETCH_OBJ);
            }
            $this->logger->logSQLQuery($sql); // Enregistrer les requête SQL dans le fichier de logs
            $messages->success=true;
            $messages->data=$data;
            return $messages;
        } catch (PDOException $e) {
            $messages->error=true;
            $this->logger->logError($e->getMessage());
            die("[[<b>Erreur lors de l'enregistrement </b> ]] : " . $e->getMessage());
            
        }
    
            
    }

    function myQuery(string $sql,array $params=null, $single=null)
    {
        $method=is_null($params) ? 'query':'prepare';
        $fetch=is_null($single) ? 'fetchAll':'fetch';
        echo "<b>{$fetch}</b></br>";echo "<b>{$method}</b></br>";
        try {
            $stmt=$this->conn->$method($sql);
            if ($params=='query') {
                $this->logger->logSQLQuery($sql);
                return $stmt->$fetch(PDO::FETCH_OBJ);
            } else {
                $this->logger->logSQLQuery($sql);
                 $stmt->execute($params);
                 return $stmt->$fetch(PDO::FETCH_OBJ);
            }
        } catch (PDOException $e) {
            $this->logger->logError($e->getMessage());
            die("[[<b>Erreur lors de l'enregistrement </b> ]] : " . $e->getMessage());    
        }
            
    }

    // Méthode pour récupérer la liste de toutes les bases de données
    public function listDatabases() {
        return $this->executeQuery("SHOW DATABASES");
    }


    // Méthode pour récupérer la liste des tables
    public function getTables(?string $db_name = null) {
        $db_name = $db_name ? $db_name : $this->dbase;
        return $this->executeQuery("SELECT table_name FROM information_schema.tables WHERE table_schema = :db_name", ['db_name' => $db_name]);
    }

    // Méthode pour récupérer la description d'une table
    public function describeTable(string $table, ?string $database = null) {
        $database = $database ? $database: $this->dbase;
        return $this->executeQuery("DESCRIBE {$database}.{$table}");
    }

    // On recupere la liste des colonnes d'un table
    public function getColumnTable($table){
        $req="SHOW COLUMNS FROM {$table};";
        $columns= $this->executeQuery($req)->data;
        // Extraire seulement les noms de colonnes
        $columnNames = [];
        foreach ($columns as $column) {
            $columnNames[] = $column->Field;
        }
        return $columnNames;
    }

    // Methode pour verifier si les donnees du formulaire coincide avec la liste des colonnes
    public function cleanData($table,$data){
        // Clés envoyées via POST
        $postKeys = array_keys($data);
        // cla liste des colonnes
        $cols=$this->getColumnTable($table);
        // Vérifier si toutes les clés POST existent dans la liste des colonnes
        $invalidKeys = array_diff($postKeys, $cols);

        if (!empty($invalidKeys)) {
            // Supprimer les clés qui ne correspondent pas aux colonnes de la table
            foreach ($invalidKeys as $invalidKey) {
                if(key_exists($invalidKey,$data)){ unset($data[$invalidKey]);}
            }

        }
        $message= "Certaines clés POST de la table {$table} ne sont pas valides et ont été supprimées : " . implode(', ', $invalidKeys) ;
        $this->logger->logError($message);
        return $data;
    }

    // Méthode pour vérifier l'existence de clés étrangères
    public function isForeignKey($table) {
        $desc = $this->describeTable($table);
        return $this->buildJoinQuery($desc, $table);
    }
    
    // Construire une requête JOIN si des clés étrangères existent
    private function buildJoinQuery($columns, $table) {
        $joinQuery = '';
        foreach ($columns as $column) {
            if ($column->Key === 'MUL' && strpos($column->Field, '_id') !== false) {
                $relatedTable = substr($column->Field, 0, strpos($column->Field, '_id'));
                $joinQuery .= " INNER JOIN {$relatedTable} ON {$table}.{$column->Field} = {$relatedTable}.id";
            }
        }
        return $joinQuery;
    }
    
    // Récupère les relations des tables
    public function getTableRelations(string $table) {
        $query = "
            SELECT 
                TABLE_NAME AS child_table, 
                COLUMN_NAME AS child_column, 
                REFERENCED_TABLE_NAME AS parent_table, 
                REFERENCED_COLUMN_NAME AS parent_column
            FROM 
                information_schema.KEY_COLUMN_USAGE
            WHERE 
                REFERENCED_TABLE_NAME IS NOT NULL
                AND TABLE_SCHEMA = :database
                AND TABLE_NAME = :table
        ";
        $relations = $this->executeQuery($query, ['table' => $table, 'database' => $this->dbase]);
        return $this->formatRelations($relations->data);
    }

    // Formater les relations pour une présentation plus claire
    private function formatRelations($relations) {
        return array_reduce($relations, function($carry, $relation) {
            $carry[$relation->child_table][] = [
                'child_column' => $relation->child_column,
                'parent_table' => $relation->parent_table,
                'parent_column' => $relation->parent_column,
                'child_field'=>"{$relation->child_table}.{$relation->child_column}",
                'parent_field'=>"{$relation->parent_table}.{$relation->parent_column}"
            ];
            return $carry;
        }, []);
    }

    // Obtenir les détails complets d'une table
    public function getTableDetails( $table,$database=null) {
        $database=$database?$database:$this->dbase;
        $description =(array) $this->describeTable($table, $database);
        return [
            'table' => $table,
            'fields' => array_column($description, 'Field'),
            'description' => $description,
            'row_count' => $this->getRowCount($table),
            'column_count' => count($description),
            'last_update' => $this->getLastUpdate($database, $table),
            'last_user' => 'Non disponible'
        ];
    }

    // Méthode pour obtenir le nombre de lignes d'une table
    private function getRowCount($table) {
        $stmt = $this->executeQuery("SELECT COUNT(*) AS count FROM `$table`");
        return (array) $stmt->data;
    }

    // Méthode pour obtenir la dernière mise à jour
    private function getLastUpdate( $table,$database=null) {
        $database=$database?$database:$this->dbase;
        $stmt = $this->executeQuery("SELECT UPDATE_TIME FROM information_schema.tables WHERE table_schema = :database AND table_name = :table", ['database' => $database, 'table' => $table]);
        return  (array) $stmt->data;
    }

    // methode pour recuperer les derniere utilisateur a avoir amodifier une table
    public function getlastUserUpdatedTable($table){
        $req="SELECT u.username, a.updated_at FROM {$table} a
            JOIN users u ON a.user_id = u.id
            ORDER BY a.updated_at DESC LIMIT 1; ";
        return (array) $this->executeQuery($req)->data;
    }

    // Construction de la requête d'insertion
    public function buildInsertQuery($table, array $data, ?array $files = null) {
        $data = $this->handleFiles($data, $files);
        $data=$this->cleanData($table,$data);
        $keys = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($key) => ":{$key}", array_keys($data)));
        return ["INSERT INTO {$table} ({$keys}) VALUES ({$placeholders})", $this->sanitizeData($data)];
    }
    
    // Construction de la requête de mise à jour
    public function buildUpdateQuery($table, array $data,$id=null) {
        $data=$this->cleanData($table,$data);
        $setString = implode(", ", array_map(fn($key) => "{$key} = :{$key}", array_keys($data)));
        return ["UPDATE {$table} SET {$setString} WHERE id = :id", $this->sanitizeData($data)];
    }

    // Traitement des fichiers lors de l'insertion
    public function handleFiles(array $data, ?array $files = null) {
        if ($files) {
            foreach ($files as $key => $file) {
                if ($file['error'] === 0) {
                    $data[$key] = $file['name'];
                }
            }
        }
        return $data;
    }

        // Protection des données avant l'insertion ou la mise à jour
    public function sanitizeData($data) {
        $data=array_map('htmlspecialchars', $data);
        $fields=[];
        foreach ($data as $key => $value) {
            $fields[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            if (stripos($key, 'password') !== false || stripos($key, 'pass') !== false) {
                $fields[$key]=password_hash($value, PASSWORD_BCRYPT);
            }
        }

        return $fields;
    }

             // Enregistrer une transaction avec action
    public function recordTransaction(string $table, INT $rowTableId, string $actionType,int $userId=null) {
        $userId=isset($userId) ? $userId: $_SESSION['id'];
        $actionTypes = ['create', 'update', 'delete', 'view'];
            
            if (in_array($actionType, $actionTypes)) {
                $stmt = $this->conn->getConnection()->prepare("
                    INSERT INTO user_transactions (user_id, transaction_details, action_type,table_name) 
                    VALUES (?, ?, ?)
                ");
                $transactionDetails = "{$table} ID:  {$rowTableId } - Action: " . ucfirst($actionType);
                $stmt->execute([$userId, $transactionDetails, $actionType,$table]);
            } else {
                $this->logger->logError('Action Non Reconnu');
                throw new Exception("Action non reconnue");
            }
    }

      // Méthode pour récupérer toutes les lignes d'une table
    public function all($table) {
        return $this->executeQuery("SELECT * FROM {$table} ORDER BY id DESC ");
    }
    // Méthode pour récupérer une ligne particuliere d'une table en fonction de l'id
    public function get($table,$id) {
        return $this->executeQuery("SELECT * FROM {$table} WHERE id={$id}");
    }
    // Méthode pour récupérer les lignes d'une table
    public function create_row($table,$data,$file=null) {
        $req=$this->buildInsertQuery($table,$data,$file);
        return $this->executeQuery($req[0],$req[0]);
    }
    // Méthode pour récupérer les lignes d'une table
    public function update_row($table,$data,$id=null) {
        $req=$this->buildUpdateQuery($table,$data);
        return $this->executeQuery($req[0],$req[0]);
    }
    // Méthode pour récupérer les lignes d'une table
    public function destroy_row($table,$id) {
        return $this->executeQuery("DELETE FROM {$table} WHERE id=:id",['id'=>$id]);
    }

    // Exportation des données en Excel
    public function exportToExcel(string $table,$filename = 'data.xlsx') {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $data = (array) $this->all($table);
        $sheet->fromArray($data);
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
    }

    // Exportation des données en CSV
    public function exportToCSV(string $table,$filename = 'data.csv') {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $data = (array) $this->all($table);
        $sheet->fromArray($data);
        $writer = new Csv($spreadsheet);
        $writer->save($filename);
    }

    // Exportation des données en PDF
    public function exportToPDF(string $table,$filename = 'data.pdf') {
        $data =(array)  $this->all($table);
        $html = "<h1>Data from {$table}</h1><table>";
        foreach ($data as $row) {
            $html .= "<tr>";
            foreach ($row as $column) {
                $html .= "<td>{$column}</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</table>";
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename);
    }

    
        







}
<?php   
namespace Core;
use Core\MYSQL_DB;
        
class Table_MYSQL  { //extends Mysql_DB
    public $table;
    private $DB;
    private $_fields = [];
    public $fields;
    public $related;
    private $attrs = [];
    private $fieldForm = [];
    private $fieldFormBlock = [];
    public $attrsBlock ;

    /**
     * Constructeur de la classe Table_Mysql
     *
     * @param string $database Nom de la base de données
     * @param string $table Nom de la table
     * @param string $host Hôte de la base de données (par défaut: 'localhost')
     * @param string $user Utilisateur de la base de données (par défaut: 'root')
     * @param string|null $password Mot de passe de la base de données (par défaut: null)
     * @param int|null $port Port de la base de données (par défaut: null)
     */
    public function __construct(MYSQL_DB $db,string $table_name) {
        $this->table = $table_name;
        $this->DB = $db;
        $this->initializeFields();
        $this->getTableRelations();
    }

    /**
     * Récupère les champs et initialise les formulaires
     */
    private function initializeFields() {
        $this->getFields();
        $this->initializeFieldForms();
    }

    private function isRequired($null) {
        return $null === "NO" ? "required" : "";
    }

    public function fetch($id) {
        return $this->DB->get($this->table, $id);
    }

    public function getFields() {
        $desc = $this->DB->describeTable($this->table);
        foreach ($desc as $field) {
            $this->_fields[$field->Field] = $field;
            $this->fields[]=[$field->Field];
        }
    }

    private function getFieldAttributes($field) {
        $attrs = ['id' => 'id_' . $field->Field, 'name' => $field->Field];
        $type = strtok($field->Type, '(');

        switch ($type) {
            case 'float':
            case 'double':
            case 'decimal':
                $attrs['type'] = 'number';
                break;
            case 'date':
            case 'datetime':
                $attrs['type'] = 'date';
                break;
            case 'varchar':
            case 'char':
                $attrs['type'] = 'text';
                $attrs['max_length'] = strtok(')');
                break;
            case 'text':
                $attrs['rows'] = 5;
                $attrs['cols'] = 2;
                break;
            case 'int':
                $attrs['type'] = 'select';
                break;
            case 'file':
                $attrs['type'] = 'file';
                break;
        }
        return $attrs;
    }

    private function initializeFieldForms() {
        foreach ($this->_fields as $field) {
            if ($field->Field !== 'id') {
                $attrs = $this->getFieldAttributes($field);
                $this->attrs[$field->Field] = $attrs;
                $this->fieldForm[$field->Field] = $this->generateInputField($attrs, $field->Type, $this->isRequired($field->Null));
            }
        }
    }

    

    private function generateInputField($attrs, $type, $required) {
        switch ($type) {
            case 'file':
                return "<input {$this->joinAttributes($attrs)} {$required} type='file'>";
            case 'float':
            case 'double':
            case 'decimal':
                return "<input {$this->joinAttributes($attrs)} {$required} type='number'>";
            case 'varchar':
            case 'char':
                return "<input {$this->joinAttributes($attrs)} {$required} type='text'>";
            case 'text':
                return "<textarea {$this->joinAttributes($attrs)} {$required}></textarea>";
            case 'int':
                return "<select {$this->joinAttributes($attrs)} {$required}>{$this->addOptions($attrs['name'])}</select>";
            default:
                return '';
        }
    }

    private function joinAttributes($attrs) {
        return implode(' ', array_map(fn($k, $v) => "{$k}='{$v}'", array_keys($attrs), $attrs));
    }

    private function addOptions($foreign, $selected = null) {
        $table = strtok($foreign, "_id");
        $data = $this->DB->all($table);
        $options = $selected === null ? "<option value='' selected>---</option>" : "";

        foreach ($data as $row) {
            $selected_attr = $selected == $row->id ? "selected" : "";
            $options .= "<option value='{$row->id}' {$selected_attr}>{$row->id}</option>";
        }
        return $options;
    }

    private function sanitizeData($data) {
        $data=$this->DB->cleanData($this->table,$data);
        $sanitized_data = [];
        foreach ($data as $key => $value) {
            if (isset($this->_fields[$key])) {
                $sanitized_data[$key] = htmlspecialchars($value);
                if ($this->_fields[$key]->Type === "text") {
                    $sanitized_data[$key] = nl2br($value);
                }
                if ($key === 'password') {
                    $sanitized_data[$key] = password_hash($value, PASSWORD_BCRYPT);
                }
            }
        }
        return $sanitized_data;
    }

    public function createRow(array $data, ?array $files = null) {
        list($query, $params) = $this->buildInsertQuery($data, $files);
        if ($this->DB->executeQuery($query, $this->sanitizeData($params))) {
            return $this->buildResponse(true, "Enregistrement réussi avec succès");
        }
        return $this->buildResponse(false, "Erreur lors de l'enregistrement");
    }

    private function buildInsertQuery(array $data, ?array $files = null) {
        if ($files) {
            foreach ($files as $key => $value) {
                $data[$key] = $files[$key]['error'] === 0 ? $files[$key]['name'] : '';
            }
        }
        $keys = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($key) => ":{$key}", array_keys($data)));
        $query = "INSERT INTO {$this->table} ({$keys}) VALUES ({$placeholders})";
        return [$query, $data];
    }

    public function updateRow(array $data) {
        list($query, $params) = $this->buildUpdateQuery($data);
        if ($this->DB->executeQuery($query, $this->sanitizeData($params))) {
            return $this->buildResponse(true, "La modification de la ligne N°: {$params['id']} a bien été effectuée");
        }
        return $this->buildResponse(false, "Erreur lors de la modification de la ligne N°: {$params['id']}");
    }

    private function buildUpdateQuery(array $data) {
        $setString = implode(", ", array_map(fn($key) => "{$key} = :{$key}", array_keys($data)));
        $query = "UPDATE {$this->table} SET {$setString} WHERE id = :id";
        return [$query, $data];
    }

    public function deleteRow($id) {
        if ($this->DB->executeQuery("DELETE FROM {$this->table} WHERE id = ?", [$id])) {
            return $this->buildResponse(true, "La ligne N°: {$id} a été supprimée");
        }
        return $this->buildResponse(false, "Erreur lors de la suppression de la ligne N°: {$id}");
    }

    private function buildResponse($success, $message) {
        return [
            'success' => $success,
            'message' => $message
        ];
    }
    // Récupère les attributs d'un champ spécifique ou tous les attributs
    public function getAttributes(string $field = null) {
        return $field ? ($this->_attrs[$field] ?? null) : $this->attrs;
    }

    // Ajoute ou modifie les attributs d'un champ
    public function setFieldAttributes(string $field, array $attributes) {
        if (isset($this->_fields[$field])) {
            $this->attrs[$field] = array_merge($this->_attrs[$field] ?? [], $attributes);
            $this->fieldForm[$field] = $this->generateInputField($this->attrs[$field], $this->_fields[$field]->Type, $this->isRequired($this->_fields[$field]->Null));
        }
    }

    // Récupère les attributs des blocs de champs
    public function getFieldBlockAttributes(string $field = null) {
        return $field ? ($this->_attrsBlock[$field] ?? null) : $this->attrsBlock;
    }

    // Ajoute ou modifie les attributs d'un bloc de champ
    public function setFieldBlockAttributes(string $field, array $attributes) {
        if (isset($this->_fields[$field])) {
            $this->attrsBlock[$field] = array_merge($this->_attrsBlock[$field] ?? [], $attributes);
            $this->fieldFormBlock[$field] = "<div {$this->joinAttributes($this->attrsBlock[$field])}><label for='id_{$field}'>{$field}</label>{$this->fieldForm[$field]}</div>";
        }
    }

    private function updateFieldBlocks() {
        foreach ($this->fieldForm as $field => $form) {
            $this->fieldFormBlock[$field] = "<div {$this->joinAttributes($this->attrsBlock[$field])}><label for='id_{$field}'>{$field}</label>{$form}</div>";
        }
    }

    /**
     * Récupère les relations des tables
     *
     * @return array Tableau associatif des relations
     */
    public function getTableRelations() {
         $this->related=$this->relatedTable();
         return $this->DB->getTableRelations($this->table);
    }

    public function relatedTable(){
       
        return $this->DB->getTableRelations($this->table);
    }

    /**
     * Récupère les lignes de la table avec ou sans jointures
     *
     * @param bool $withJoins Si true, récupère les données avec jointures, sinon sans jointures.
     * @return array Résultats de la requête
     */
    public function fetchTable(bool $withJoins = false): array {
        return $withJoins ? $this->fetchWithJoins() : $this->fetchWithoutJoins();
    }

    /**
     * Récupère toutes les lignes de la table sans jointures
     *
     * @return array Résultats de la requête
     */
    private function fetchWithoutJoins(): array {
        return (array) $this->DB->all($this->table);
    }

    /**
     * Récupère toutes les lignes de la table avec les jointures basées sur les relations
     *
     * @return array Résultats de la requête avec jointures
     */
    public function fetchWithJoins(): array {
        $relations = (array)$this->DB->getTableRelations($this->table);
        $joins = [];
        $fields=[];
        foreach ($relations as $table => $relationsTable) {
            $fields[]="{$this->table}.*";
            foreach ($relationsTable as $relation) {
                $joins[] = "INNER JOIN {$relation['parent_table']} 
                            ON {$relation['child_field']} = {$relation['parent_field']}}.*";
            }
        }

        $joinQuery = implode(' ', $joins);
        $joinFields = implode(',', $fields);
        $query = "SELECT {$joinFields} FROM {$this->table} {$joinQuery}";

        return (array) $this->DB->executeQuery($query);
    }


    /**
     * Génère un formulaire HTML pour ajouter un nouvel enregistrement à la table
     *
     * @return string Le formulaire HTML pour ajouter un nouvel enregistrement
     */
    public function getAddForm(): string {
        // Initialiser le formulaire HTML
        $formHtml = "<form method='post' enctype='multipart/form-data'>";

        // Génération des blocs de formulaire pour chaque champ
        foreach ($this->fieldFormBlock as $field => $block) {
            // Si le bloc contient un champ de type 'file', ne pas pré-remplir la valeur
            if (strpos($block, 'type=\'file\'') !== false) {
                $formHtml .= str_replace("value=''", "", $block);
            } else {
                // Ajouter les valeurs par défaut ou vides pour les autres types de champs
                $formHtml .= $block;
            }
        }

        // Ajouter les boutons de soumission et d'annulation
        $formHtml .= "<div class='form-actions'>";
        $formHtml .= "<button type='submit' name='action' value='create'>Ajouter</button>";
        $formHtml .= "<button type='reset'>Réinitialiser</button>";
        $formHtml .= "</div>";

        // Fermer le formulaire
        $formHtml .= "</form>";

        return $formHtml;
    }

    /**
     * Récupère une ligne spécifique de la table et génère un formulaire modifiable avec les données chargées
     *
     * @param int $id L'identifiant de la ligne à récupérer
     * @return string Le formulaire HTML pré-rempli avec les données
     */
    public function getEditableForm(int $id): string {
        // Récupération des données de la ligne
        $data = $this->fetch($id);

        if (!$data) {
            return "<p>Erreur : Aucune donnée trouvée pour l'identifiant {$id}</p>";
        }

        // Initialiser le formulaire HTML
        $formHtml = "<form method='post' enctype='multipart/form-data'>";

        // Génération des blocs de formulaire avec les données
        foreach ($this->fieldFormBlock as $field => $block) {
            $value = isset($data->$field) ? htmlspecialchars($data->$field) : '';

            // Remplacer les valeurs par défaut dans les champs de texte
            if (strpos($block, 'value=\'\'') !== false) {
                $block = str_replace('value=\'\'', "value='{$value}'", $block);
            }

            // Ajouter le bloc de formulaire au HTML
            $formHtml .= $block;
        }

        // Ajouter un champ caché pour l'ID
        $formHtml .= "<input type='hidden' name='id' value='{$id}'>";

        // Ajouter les boutons de soumission et d'annulation
        $formHtml .= "<div class='form-actions'>";
        $formHtml .= "<button type='submit' name='action' value='update'>Mettre à jour</button>";
        $formHtml .= "<button type='reset'>Annuler</button>";
        $formHtml .= "</div>";

        // Fermer le formulaire
        $formHtml .= "</form>";

        return $formHtml;
    }


    /**
     * Génère une table HTML à partir des données de la table
     *
     * @return string Le code HTML de la table
     */
    public function generateHtmlTable(array $params=NULL): string {
        // Récupérer les données de la table
        $data=$params != NULL ? $params :$this->DB->all($this->table);
        
        // Vérifier s'il y a des données à afficher
        if (empty($data)) {
            return "<p>Aucune donnée disponible</p>";
        }

        // Initialiser la table HTML
        $html = "<table border='1' cellspacing='0' cellpadding='5'>";
        
        // Générer les en-têtes de la table
        $html .= "<thead><tr>";
        foreach (array_keys((array)$data[0]) as $header) {
            $html .= "<th>" . htmlspecialchars($header) . "</th>";
        }
        $html .= "</tr></thead>";

        // Générer les lignes de la table
        $html .= "<tbody>";
        foreach ($data as $row) {
            $html .= "<tr>";
            foreach ($row as $cell) {
                $html .= "<td>" . htmlspecialchars($cell) . "</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</tbody>";

        // Fermer la table HTML
        $html .= "</table>";

        return $html;
    }
 
}






?>


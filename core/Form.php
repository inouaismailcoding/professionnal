<?php
namespace Core;
use Core\Table_MYSQL;
/*
class Form {
    // la Fonction Construct 
    public $table;
    public  $table_name;
    public $fieldForm=[];
    public $fieldOptions=[];
    public $blockForm=[];
    public $fieldAttributes=[];
    public $tableDescription=[];
    public $blockAttributes=[];
    public $instanceFieldForm=[];
    public $instanceBlockForm=[];

    public function __construct(Table_MYSQL $table_Sql,$table_name) {
        $this->table = $table_Sql;
        $this->table->table=$table_name;
        $this->table_name=$table_name;
        $this->init();
    }

    // function pour initiliser le formulaire
    public function init(){
        $this->tableDescription=$this->table->getFields();

        $columns=$this->tableDescription;
        foreach ($columns as $column) {
            if ($column['name'] != 'id' && $column['dflt_value'] != 'CURRENT_TIMESTAMP' && $column['dflt_value'] != 'updated_at') {
                $this->fieldForm[$column['name']]=[];
                $this->fieldAttributes[$column['name']]=[];
                $this->instanceFieldForm[$column['name']]=[];
                $this->instanceBlockForm[$column['name']]=[];

                $this->blockForm[$column['name']]=[];
                $this->blockAttributes[$column['name']]=[];
                $this->blockAttributes[$column['name']]['class']='item-form';
            }
           
        }
        $this->generateForm($this->tableDescription);


       
    }
    // Fonction pour générer un champ générique avec des attributs dynamiques
    private function generateField($type, $name, $attributes = [], string $value=null) {
        $attrString = $this->generateAttributesString($attributes);
        if ($type !="file") {
            return isset($value)?"<input type='{$type}' name='{$name}' id='{$name}' value='{$value}' {$attrString} />":"<input type='{$type}' name='{$name}' id='{$name}' {$attrString} />";        }
    }

    // Fonction pour générer un textarea
    public function generateTextArea($name, $attributes = [], string $value=null) {
        $attrString = $this->generateAttributesString($attributes);
        return isset($value)?"<textarea name='{$name}' id='{$name}' {$attrString}>{$value}</textarea>":"<textarea name='{$name}' id='{$name}' {$attrString}></textarea>";
    }

    // Fonction pour générer un champ select
    public function generateSelectField($name, $options = [], $attributes = [], $multiple = false,int $value=null) {
        $attrString = $this->generateAttributesString($attributes);
        $optionsHtml = '';
        $val=isset($value)?"selected":"";
        foreach ($options as $value => $label) {
            $optionsHtml .= "<option value='{$value}' {$val}>{$label}</option>";
        }
        return "<select name='{$name}' id='{$name}' {$attrString}>{$optionsHtml}</select>";
    }

    // Fonction pour générer des checkbox
    public function generateCheckboxField($name, $options = [], $attributes = []) {
        $attrString = $this->generateAttributesString($attributes);
        $checkboxes = '';
        foreach ($options as $value => $label) {
            $checkboxes .= "<label><input type='checkbox' name='{$name}[]' value='{$value}' {$attrString}> {$label}</label><br/>";
        }
        return $checkboxes;
    }

    // Fonction pour générer des radio buttons
    public function generateRadioField($name, $options = [], $attributes = []) {
        $attrString = $this->generateAttributesString($attributes);
        $radios = '';
        foreach ($options as $value => $label) {
            $radios .= "<label><input type='radio' name='{$name}' value='{$value}' {$attrString}> {$label}</label><br/>";
        }
        return $radios;
    }

    // Générer une chaîne d'attributs à partir d'un tableau associatif
    private function generateAttributesString($attributes) {
        $attrArray = [];
        foreach ($attributes as $key => $value) {
            $attrArray[] = "{$key}='{$value}'";
        }
        return implode(' ', $attrArray);
    }

    // pour modifier les attributs des champs dans un tableau
    public function setAttributeField($name,$attributes=[]) {
            $this->fieldAttributes[$name] = array_merge($this->fieldAttributes[$name], $attributes);
            $this->Form();
    }
    // pour recuperer les attributs des champs dans un tableau
    public function getAttributeField($name=null) {
        return isset($name)?$this->fieldAttributes[$name]:$this->fieldAttributes;
    }

    // pour modifier les attributs du block des champs dans un tableau
    public function setAttributeBlock($name,$attributes=[]) {
        $this->blockAttributes[$name] = array_merge($this->blockAttributes[$name], $attributes);
        $this->Form();

    }
    // pour recuperer les attributs du block des champs dans un tableau
    public function getAttributeBlock($name=null) {
        return isset($name)?$this->blockAttributes[$name]:$this->blockAttributes;
    }

    // Fonction principale pour générer le formulaire en fonction de la description de la table
    public function generateForm($tableDescription,$fieldOptions = []) {
        foreach ($tableDescription as $column) {
            $name = $column['name'];
            $type = strtoupper($column['type']);
            $default=$column['dflt_value'];
            $required = $column['notnull'] == 1;
            $placeholder = "Enter {$name}";
            $this->fieldAttributes[$name]['placeholder']= $placeholder;
            if ($required) {
                $this->fieldAttributes[$name]['required'] = 'required';
            }

            // Vérifier si des options spécifiques sont définies pour ce champ
            $fieldOptions = $this->fieldOptions[$name] ?? [];
            if ($default !='CURRENT_TIMESTAMP' && $name !='id') {
                switch ($type) {
                    case 'VARCHAR':
                    case 'CHAR':
                        $this->fieldForm[$name]= $this->generateField('text', $name, $this->fieldAttributes[$name]);
                        $this->blockForm[$name]= $this->addFieldInDiv($name,$this->generateField('text', $name, $this->fieldAttributes[$name]));
                        break;
                    case 'INT':
                    case 'INTEGER':
                        $this->fieldForm[$name]= $this->generateField('number', $name, $this->fieldAttributes[$name]);
                        $this->blockForm[$name]= $this->addFieldInDiv($name,$this->generateField('number', $name, $this->fieldAttributes[$name]));
                        break;
                    case 'TEXT':
                        $this->fieldForm[$name]= $this->generateTextArea($name, $this->fieldAttributes[$name]);
                        $this->blockForm[$name]= $this->addFieldInDiv($name,$this->generateTextArea($name, $this->fieldAttributes[$name]));
                        break;
                    case 'DATE':
                        $this->fieldForm[$name]= $this->generateField('date', $name, $this->fieldAttributes[$name]);
                        $this->blockForm[$name]= $this->addFieldInDiv($name,$this->generateField('date', $name, $this->fieldAttributes[$name]));
                        break;
                    case 'SELECT':
                        $options = $fieldOptions[$name]?? [];
                        $this->fieldForm[$name]= $this->generateSelectField($name, $options, $this->fieldAttributes[$name]);
                        $this->blockForm[$name]= $this->addFieldInDiv($name,$this->generateSelectField($name, $options, $this->fieldAttributes[$name]));
                        break;
                    case 'CHECKBOX':
                        $options = $fieldOptions[$name]?? [];
                        $this->fieldForm[$name]= $this->generateCheckboxField($name, $options, $this->fieldAttributes[$name]);
                        $this->blockForm[$name]= $this->addFieldInDiv($name,$this->generateCheckboxField($name, $options, $this->fieldAttributes[$name]));
                        break;
                    case 'RADIO':
                        $options = $fieldOptions[$name] ?? [];
                        $this->fieldForm[$name]= $this->generateRadioField($name, $options, $this->fieldAttributes[$name]);
                        $this->blockForm[$name]= $this->addFieldInDiv($name,$this->generateRadioField($name, $options, $this->fieldAttributes[$name]));
                        break;
                    default:
                    $this->fieldForm[$name]= $this->generateField('text', $name, $this->fieldAttributes[$name]);
                    $this->blockForm[$name]= $this->addFieldInDiv($name,$this->generateField('text', $name, $this->fieldAttributes[$name]));
                        break;
                }
            }
           
    
        }
    }
        // Fonction principale pour générer le formulaire en fonction de la description de la table
    public function generateInstanceForm($id) {
        $value = $this->table->;
        if (is_array($value)) {
            foreach ($this->tableDescription as $column) {
                $name = $column['name'];
                $type = strtoupper($column['type']);
                $default=$column['dflt_value'];
                $required = $column['notnull'] == 1;
                $placeholder = "Enter {$name}";
                $this->fieldAttributes[$name]['placeholder']= $placeholder;
                if ($required) {
                    $this->fieldAttributes[$name]['required'] = 'required';
                }
    
                // Vérifier si des options spécifiques sont définies pour ce champ
                $fieldOptions = $this->fieldOptions[$name] ?? [];
                if ($default !='CURRENT_TIMESTAMP' && $name !='id') {
                    switch ($type) {
                        case 'VARCHAR':
                        case 'CHAR':
                            $this->instanceFieldForm[$name]= $this->generateField('text', $name, $this->fieldAttributes[$name],$value[$name]);
                            $this->instanceBlockForm[$name]= $this->addFieldInDiv($name,$this->generateField('text', $name, $this->fieldAttributes[$name],$value[$name]));
                            break;
                        case 'INT':
                        case 'INTEGER':
                            $this->instanceFieldForm[$name]= $this->generateField('number', $name, $this->fieldAttributes[$name],$value[$name]);
                            $this->instanceBlockForm[$name]= $this->addFieldInDiv($name,$this->generateField('number', $name, $this->fieldAttributes[$name],$value[$name]));
                            break;
                        case 'TEXT':
                            $this->instanceFieldForm[$name]= $this->generateTextArea($name, $this->fieldAttributes[$name],$value[$name]);
                            $this->instanceBlockForm[$name]= $this->addFieldInDiv($name,$this->generateTextArea($name, $this->fieldAttributes[$name],$value[$name]));
                            break;
                        case 'DATE':
                            $this->instanceFieldForm[$name]= $this->generateField('date', $name, $this->fieldAttributes[$name],$value[$name]);
                            $this->instanceBlockForm[$name]= $this->addFieldInDiv($name,$this->generateField('date', $name, $this->fieldAttributes[$name],$value[$name]));
                            break;
                        case 'SELECT':
                            $options = $fieldOptions[$name]?? [];
                            $this->instanceFieldForm[$name]= $this->generateSelectField($name, $options, $this->fieldAttributes[$name],$value[$name]);
                            $this->instanceBlockForm[$name]= $this->addFieldInDiv($name,$this->generateSelectField($name, $options, $this->fieldAttributes[$name],$value[$name]));
                            break;
                        case 'CHECKBOX':
                            $options = $fieldOptions[$name]?? [];
                            $this->instanceFieldForm[$name]= $this->generateCheckboxField($name, $options, $this->fieldAttributes[$name]);
                            $this->instanceBlockForm[$name]= $this->addFieldInDiv($name,$this->generateCheckboxField($name, $options, $this->fieldAttributes[$name]));
                            break;
                        case 'RADIO':
                            $options = $fieldOptions[$name] ?? [];
                            $this->instanceFieldForm[$name]= $this->generateRadioField($name, $options, $this->fieldAttributes[$name]);
                            $this->instanceBlockForm[$name]= $this->addFieldInDiv($name,$this->generateRadioField($name, $options, $this->fieldAttributes[$name]));
                            break;
                        default:
                        $this->instanceFieldForm[$name]= $this->generateField('text', $name, $this->fieldAttributes[$name],$value[$name]);
                        $this->instanceBlockForm[$name]= $this->addFieldInDiv($name,$this->generateField('text', $name, $this->fieldAttributes[$name],$value[$name]));
                            break;
                    }
                }
               
        
            }
        }else {
            echo "Aucune Ligne Correspondante";
        }
    }
    // Function pour ajouter le champs de saisi dans une div
    public function addFieldInDiv($name,$field) {
        return "<div {$this->generateAttributesString($this->blockAttributes[$name])} ><label for='{$name}'>{$name}</label>".$field."</div>";
    }
    // function pour recuperer la forme du formulaire
    public function Form() {
        $this->generateForm($this->tableDescription, $this->fieldOptions);
    }
    public function getFormAsString() {
        return implode(' ', $this->blockForm);
    }
    public function getFieldForm() {
        return $this->fieldForm;
    }
    public function getBlockForm() {
        return $this->blockForm;
    }

    public function getForm() {
        $form='';
        $form.= "<form method='POST' action'' data-table='{$this->table_name}' id='{$this->table_name}-form'>";
        $form.= $this->getFormAsString();
        $form.= "<input type='submit' id='btn-create-row' value='Envoyer'>";
        $form.= "</form>";

        return $form;
    }

    // Instance Form 
    public function instanceForm($id) {
        $this->generateInstanceForm($id);
    }
    public function getInstanceFormAsString($id) {
        $this->generateInstanceForm($id);
        return implode(' ', $this->instanceBlockForm);
    }
    public function getInstanceFieldForm($id) {
        $this->generateInstanceForm($id);
        return $this->instanceFieldForm;
    }
    public function getInstanceBlockForm($id) {
        $this->generateInstanceForm($id);
        return $this->instanceBlockForm;
    }

    public function getInstanceForm($id) {
        $form='';
        $form.= "<form method='POST' action='' data-table='{$this->table_name}' id='{$this->table_name}-form'>";
        $form.= $this->getInstanceFormAsString($id);
        $form.= "<input type='submit' id='btn-update-row' name='btn-update-row'  value='update'>";
        $form.= "</form>";

        return $form;
    }

}


*/
// Exemple de description de table
/*
$tableDescription = [
    ['name' => 'first_name', 'type' => 'varchar', 'notnull' => 1],
    ['name' => 'last_name', 'type' => 'varchar', 'notnull' => 0],
    ['name' => 'email', 'type' => 'varchar', 'notnull' => 1],
    ['name' => 'age', 'type' => 'int', 'notnull' => 0],
    ['name' => 'bio', 'type' => 'text', 'notnull' => 0],
    ['name' => 'gender', 'type' => 'radio', 'notnull' => 1],
    ['name' => 'hobbies', 'type' => 'checkbox', 'notnull' => 0],
    ['name' => 'birthdate', 'type' => 'date', 'notnull' => 1]
];

// Exemple d'options de champs
$fieldOptions = [
    'gender' => ['options' => ['1' => 'Male', '2' => 'Female']],
    'hobbies' => ['options' => ['1' => 'Reading', '2' => 'Traveling']],
    'age' => ['min' => 0, 'max' => 120],
];
*/
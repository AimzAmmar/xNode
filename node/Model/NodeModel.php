<?php
NODE::load('Inflector', 'Utility');
NODE::load('NodeDatabase');
NODE::load('NodeValidator');
NODE::load('NodeSanitizer');
/*
 *
 *
*/
/**
 * Created by JetBrains PhpStorm.
 * User: Ahmed
 * Date: 29/07/13
 * Time: 11:35
 * To change this template use File | Settings | File Templates.
 */
class NodeModel extends NodeDatabase
{
    // table name should be lowercase plural of the model name however if you specify something else for the model, please
    // use the $table field otherwise you won't need to specify it.
    public $table;

    // simple column name for the primary key used for this model, can be changed dynamically incase of compostire primary key
    public $primaryKey;

    public $name;

    protected $alwaysValidate = false;

    protected $alias;
    // an array with field name verses their validation rules
    private $columnInfo = array();

    public $validator;

    public $sanitizer;

    protected $queryBuilder;

    public $id;

    public $resultSet;

    public function NodeModel()
    {
        parent::__construct();
        if (!isset($this->name)) {
            $this->name = get_called_class();
        }
        if (!isset($this->table)) {
            $this->table = Inflector::pluralize(lcfirst($this->name));
        }
        if(!isset($this->alias)){
            $this->alias = $this->name;
        }
        if(!isset($this->primaryKey)){
            $this->primaryKey = $this->getPrimaryKey();
        }
        $this->validator = new NodeValidator();
        $this->sanitizer = new NodeSanitizer();
        $this->queryBuilder = new QueryBuilder();
    }

    private function resolveColumnInformation($data = array())
    {
        $difference = array_diff_key($data, $this->columnInfo);
        if (!empty($difference)) {
            $this->columnInfo = array_replace_recursive($this->getTableFieldsInformation($this->table), $this->columnInfo);
        }
    }
    /*
     * $data = simple array or columns to retrieve data
     * $conditions = array ('where','groupby','orderby');
     */
    public function select($data = array(), $conditions = ""){

        if(isset($conditions[1])){
            $conditions[1] = $this->sanitizer->sanitizeData($conditions[1]);
        }
        $options = array(
            "type" => "select",
            "fields" => $data,
            "table" => $this->table,
            "alias" => $this->name,
            "conditions" => $conditions
        );
        $query = $this->queryBuilder->buildQuery($options);
        return $query;
    }
    /*
     * $data ====> $data[table][column] = value
     * after resolving $data[table][column] = value of the same table of the model
     *
     */
    public function insert($data = array(), $validate = false)
    {
        $data = $this->resolveData($data, $this->name);
        if ($validate == true || $this->alwaysValidate == true) {
            //  $this->columnInfo = (isset($this->columnInfo)) ? $this->columnInfo : $this->getTableFieldsInformation($this->table);
            $this->resolveColumnInformation($data);
            //validate data here, call validation function
            $validationRules = array();
            foreach ($data as $key => $val) {
                if (isset($this->columnInfo[$key])) {
                    $validationRules = $this->columnInfo[$key];
                    $result[$key] = $this->validator->validateData($val, $validationRules, false);
                    if (!$result[$key]) {
                        return false;
                    }
                }
            }
        }
        // get the sanitizer to do its job
        // sanitizers are usually silent just to clean the input and save them according to their dataType w.r.t their
        // mysql dataTypes
        /* build query!

        */
        $data = $this->sanitizer->sanitizeData($data);

        $options = array(
            "type" => "insert",
            "fields" => $data,
            "table" => $this->table,
            "alias" => $this->name
        );
        $query = $this->queryBuilder->buildQuery($options);
        return $query;
        $result = $this->executeQuery($query);
        $this->id = $this->mysqli->insert_id;
        return $result;
    }

    /*
     * @function update
     *  $conditions = array (
                                  "where" => array("Id" => "123");
                                ); ==== WHERE Id = 123
    $conditions = array (
                                  "where" => array("AND" => array("Id" => 123, "Name" => "ahmed", array("NOT" => array("Id" => "1"))),
                                                    "OR" => array ("Last" => "ammar"));
                                ); ==== WHERE (Id = 132 AND Name = 'ahmed') AND Id != '1' OR Last = 'ammar'
    $conditions = "Id = 123 AND Name = 'ahmed'"
        if no condition is given the system would try to find the primary key in the data given and will update
        comparing the primary key. If no primary key is present in the model variables, the system will try to find,
        the primary key and will look for it into the data given if present will update else will run the query
        without any condition.
     */

    public function update($data = array(), $conditions, $validate = false)
    {
        $data = $this->resolveData($data);
        if ($validate == true || $this->alwaysValidate == true) {
            $this->resolveColumnInformation($data);
            $validationRules = array();
            foreach ($data as $key => $val) {
                if (isset($this->columnInfo[$key])) {
                    $validationRules = $this->columnInfo[$key];
                    $result[$key] = $this->validator->validateData($val, $validationRules, false);
                    if (!$result[$key]) {
                        return false;
                    }
                }
            }
        }

        $data = $this->sanitizer->sanitizeData($data);

        $options = array (
            'type' => 'update',
            'table' => $this->table,
            'alias' => $this->name,
            'fields' => $data,
            'conditions' => $conditions
        );

        $query = $this->queryBuilder->buildQuery($options);
        return $query;
    }


    public function delete($conditions){
        $options = array (
            'type' => 'delete',
            'table' => $this->table,
            'alias' => $this->name,
            'conditions' => $conditions
        );
        $query = $this->queryBuilder->buildQuery($options);
        return $query;
    }
    /*
    * validation function, it should be generic enough to be used by the login too;
    * try to get the same function
    *
    * $validationData = array ('column' => array(
    *                                          'field_type' => '',
    *                                          'value' => '',
    *                                          'type' => 'string',
    *                                          'max_length' => '15'
    *                                      ));
    */
    private function resolveData($data, $modelname = "")
    {
        // used to change
        // data is atleast sent as $data['MODEL']['field'] or $data['field']
        // for model associations the data is sent through the parent model
        // which will issue the associated model inserts and all
        // and the associative insert will sort out everything
        // $data['MODEL']['field'] => $data['field']
        // $data['User']['Id'] = 1, $data['User']['Login']['username'] = 'ahmed'

        if ($modelname == "") {
            $modelname = $this->name;
        }
        if (!is_array($data)) {
            $datastr = $data;
            $data = array();
            parse_str($datastr, $data);
        }
        if (array_key_exists($modelname, $data)) {
            $data = $data[$modelname];
        }
     //   else {
            // the data is assumed to be of this model and is not further processed
     //   }
        return $data;
    }

    public function setFeildValidationRules($column, $validation = "")
    {
        if ($validation == "") {
            $validation = $this->getTableFieldsInformation($this->table, $column);
            if ($validation) {
                return false;
            }
        } else {
            $this->columnInfo[$column] = $validation;
        }
    }

    /*
     * validation function, it should be generic enough to be used by the login too;
     * try to get the same function
     *
     * $validationData = array ( 'column' => array(
          *            'value' => 'columnValue',
          *            'type' => 'string',
          *            'max_Length' => '15'
          *           ));
 */
    public function setTableValidationRules($validationRules)
    {
        $this->columnInfo = $validationRules;
    }

    /*
     * function used to get the primary key from the column information
     * the function uses the flag information to get the primary key
     */

    public function getPrimaryKey($table = "")
    {
        if ($table == "") {
            $table = $this->table;
        }
        if (isset($this->primaryKey) && !empty($this->primaryKey)) {
            return $this->primaryKey;
        } else {
            $this->columnInfo = (isset($this->columnInfo)) ? $this->columnInfo : $this->getTableFieldsInformation($this->table);
            $primaryKey = $this->getPrimaryKeyFromColumnInformation();
            if (empty($primaryKey)) {
                $this->columnInfo = $this->getTableFieldsInformation($this->table);
                $primaryKey = $this->getPrimaryKeyFromColumnInformation();
            }
        }
        if (empty($primaryKey)) {
            return false;
        }
        if (sizeof($primaryKey) == 1) {
            $primaryKey = $primaryKey[0];
        } else {
            $primaryKey = $this->getAutoIncrementColumn($primaryKey);
        }
        return $primaryKey;
    }

    public function getAutoIncrementColumn($columns = array()){
        $auto = NULL;
        if(!empty($columns)){
            foreach($columns as $key => $val){
                foreach($this->columnInfo[$key]['flags'] as $k => $v){
                    if($v == "AI"){
                        $auto = $key;
                        break;
                    }
                }
            }
        } else {
            foreach ($this->columnInfo as $key => $val) {
                if (isset($val['flags'])) {
                    foreach ($val['flags'] as $k => $v) {
                        if ($v == "AI"){
                            $auto = $key;
                            break;
                        }
                    }
                }
            }
        }
        if(!$auto){
            $auto = $columns[0];
        }
        return $auto;
    }
    private function getPrimaryKeyFromColumnInformation()
    {
        $primaryKey = array();
        foreach ($this->columnInfo as $key => $val) {
            if (isset($val['flags'])) {
                foreach ($val['flags'] as $k => $v) {
                    if ($v == "Primary"){
                        array_push($primaryKey, $key);
                    }
                }
            }
        }
        return $primaryKey;
    }

    protected function getTableName($model = ''){
        $table = "";
        if($model == ''){
            $table = $this->tablePrefix.$this->table;
        } else {
            if(is_object($model)){
                $table = $model->tablePrefix.$model->table;
            } else {
                $table = $this->tablePrefix.Inflector::pluralize($model);
            }
        }

        return $table;
    }

}

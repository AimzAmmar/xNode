<?php
/**
 * xnode.
 * User: Ahmed
 * Date: 11/4/13
 * Time: 9:12 PM
 * 
 */

class QueryBuilder {

    var $DemoArray = array(
                'type' => 'select',
                'fields' => '*',
                'conditions' => '',

    );
    /*
     * $options = array(
     *      'type' => 'select'/'insert'/'delete'/'update'
     *      'fields' => array("fieldname = ?, field2 = ?")
     *      'conditions' => 'The where clause'
     *      'table' => name of the table (without prefix)
     *      'alias' => alias of the table if not specified, the name of the Model works like an alias
     *      'join' => array (
     *                          'type' => type of join like 'LeftJoin','RightJoin','OuterJoin' default is just JOIN
     *                          'table' => name of the table to join with
     *                          'alias' => alias of the table, if the table is joined without association, the singular first letter Capital alias is used for instance; "users" => User
     *                          'condition' => condition to join the tables like (Model1.Id >= Model2.UserId)
     *                  ); //join array can be of multiple indices if you need to join multiple tables
     *      'orderBy' => array('Id','ASC')
     *      'groupBy' => "Id, firstName"
     *      'Having' => ''similar to where clause. having (Group.Id > ?)
     * );
     */
    public function buildQuery($options){
        $type = isset($options['type'])?$options['type']:"select";
        $orderBy = $conditions = $join = $groupBy = $having = "";
        $table = $options['table'];
        $alias = isset($options['alias'])?$options['alias']:ucfirst(Inflector::singularize($table));
        if(isset($options['fields']) && is_array($options['fields'])){
            if ($options['type'] == "select") {
                $cols = array();
                $i = 0;
                foreach($options['fields'] as $key){
                    $cols[$i] = "`".$alias.".".$key."`";
                    $i++;
                }
                $fields = implode(",", $cols);

            } else if($options['type'] == "insert"){
                $columns = implode(",",array_keys($options['fields']));
                $fields = implode(",", $options['fields']);
            } else if ($options['type'] == "update") {
                $fields = $options['fields'];
                $clauses = "";
                foreach($fields as $key => $val){
                    $clauses .= "`".$key."` = ".$val.",";
                }
                $fields = rtrim($clauses, ",");

            }
        } else {
            $fields = isset($options['fields'])?$options['fields']:"*";
        }
        if(isset($options['conditions'])){
            $conditions = "WHERE ". $this->parseQuery($options['conditions']);
        }
        if(isset($options['orderBy'])){
            $orderBy = $options['orderBy'];
             if(is_array($orderBy)){
                 $orderBy = implode(",",$orderBy);
             }
            $orderBy = "ORDER BY ".$orderBy;
        }
        if(isset($options['groupBy'])){
            $groupBy = $options['groupBy'];
            if(is_array($groupBy)){
                $groupBy = implode(",",$groupBy);
            }
            $groupBy = "GROUP BY ".$groupBy;
            if(isset($options['having'])){
                $having = "HAVING ".$this->parseQuery($options['having']);
            }
        }
        if(isset($options['join'])){
            if(isset($options['join'][0]['table'])){
                foreach($options['join'] as $key){
                    $join .= $this->makeJoins($key);
                }
            } else {
                $join = $this->makeJoins($options['join']);
            }
        }
        $query = "";
        switch($type){
            case "select":
                $query = sprintf("SELECT %s FROM %s AS %s %s %s %s %s %s", $fields, $table, $alias, $join, $conditions, $groupBy, $having, $orderBy);
                break;
            case "insert":
                $query = sprintf("INSERT INTO %s (%s) VALUES (%s)", $table, $columns, $fields);
                break;
            case "update":
                $query = sprintf("UPDATE %s SET %s %s",$table, $fields, $conditions);
                break;
            case "delete":
                $query = sprintf("DELETE FROM %s %s", $table, $conditions);
        }
        return $query;
    }

    private function makeJoins($joinarr){
        $join = " JOIN ";
        if(is_array($joinarr)){
            if(isset($joinarr['type'])){
                $join = $joinarr['type']." JOIN ";
            }
            $join .= $joinarr['table'];
            if(isset($joinarr['alias'])){
                $join .= " AS ".$joinarr['alias'];
            }
            if(isset($joinarr['condition'])){
                $join .= " ON ".$this->parseQuery($joinarr['condition']);
            }
        } else {
            $join .= $joinarr;
        }
        return $join;
    }

    public function prepareWhereClause($conditions, $table)
    {
        if (!is_array($conditions)) {
            return $conditions;
        } else if (!empty($conditions)) {
            echo "<p>start</p>";
            $whereClause = "WHERE ";
            $whereClause .= $this->parseQuery($conditions);
            return $whereClause;
        } else {
            return false;
        }
    }

    /**
     * Return $parsedQuery with replaced values in the origional string
     *
     * @param string $query the query with the '?' sign to be replaced
     * @param string $delimeter the character(s) you used to replace with
     * @return string parsedQuery with the string worked out
     *
     */
    public function parseQuery($query, $delimeter = "?"){
        if(!is_array($query)){
            return $query;
        }
        $subject = $query[0];
        $values = $query[1];
        $parsedQuery = "";
        $subject = explode($delimeter, $subject);
        for($i = 0; $i < sizeof($subject); $i++){
            //check the input based on the type the login entered it, sanitize it.
            $val = isset($values[$i])?trim($values[$i]):"";
            $parsedQuery .= sprintf("%s %s",$subject[$i],$val);
        }
        return $parsedQuery;
    }
}
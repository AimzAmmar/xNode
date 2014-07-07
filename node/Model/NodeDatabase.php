<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ahmed
 * Date: 28/08/13
 * Time: 17:32
 * To change this template use File | Settings | File Templates.
 */
NODE::load('QueryBuilder','Model');

class NodeDatabase
{
    //using mysqli extension
    /*
    private $host = DB_HOST;
    private $login = DB_USER;
    private $password = DB_PWD;
    */
    protected $database = DB_NAME;
    protected $tablePrefix = DB_Table_PREFIX;
    protected $mysqli; //mysqli class object

    private static $mysqlTypes =
        array(
            MYSQLI_TYPE_LONG => "integer",
            MYSQLI_TYPE_DECIMAL => "decimal",
            MYSQLI_TYPE_FLOAT => "double",
            MYSQLI_TYPE_DOUBLE => "double",
            MYSQLI_TYPE_LONGLONG => "double",
            MYSQLI_TYPE_DATE => "string",
            MYSQLI_TYPE_TIME => "time",
            MYSQLI_TYPE_DATETIME => "string",
            MYSQLI_TYPE_ENUM => "ENUM",
            MYSQLI_TYPE_SET => "SET",
            MYSQLI_TYPE_VAR_STRING => "string",
            MYSQLI_TYPE_STRING => "string",
            MYSQLI_TYPE_CHAR => "char",
            MYSQLI_TYPE_GEOMETRY => "geometry",
            MYSQLI_TYPE_BLOB => "blob",
            MYSQLI_TYPE_LONG_BLOB => "longblob",
            MYSQLI_TYPE_MEDIUM_BLOB => "mediumblob",
            MYSQLI_TYPE_TINY_BLOB => "tinyblob",
            MYSQLI_TYPE_INTERVAL => "interval",
            MYSQLI_TYPE_NEWDATE => "date",
            MYSQLI_TYPE_YEAR => "year",
            MYSQLI_TYPE_INT24 => "integer",
            MYSQLI_TYPE_TIMESTAMP => "datetime",
            MYSQLI_TYPE_NULL => "default null",
            MYSQLI_TYPE_SHORT => "smallint",
            MYSQLI_TYPE_TINY => "tinyint",
            MYSQLI_TYPE_BIT => "bit",
            MYSQLI_TYPE_NEWDECIMAL => "numeric",
            MYSQLI_TYPE_DECIMAL => "decimal"

        );
      private static $bitflagmaps =
          array(
          "1" => "Not Null",
          "2" => "Primary",
          "4" => "Unique",
          "16" => "Blob",
          "32" => "Unsigned",
          "64" => "Zerofill",
          "128" => "Binary",
          "256" => "ENUM",
          "512" => "AI",
          "1024" => "Timestamp",
          "2048" => "SET",
          "32768" => "NUM",
          "16384" => "Partial",
          "65536" => "Unique"
      //    ,"Unique" => "65536"
      );
    function NodeDatabase()
    {
        $this->mysqli = $this->connectToDatabase(DB_HOST, DB_USER, DB_PWD, $this->database);
    }

    /*
     * function returns the associated php standard variable type against a mysqlitype constant
     * if the value is not in the array, the parameter is returned
     */
    protected function getMysqlType($mysqlCode)
    {
        return isset(self::$mysqlTypes[$mysqlCode]) ? self::$mysqlTypes[$mysqlCode] : $mysqlCode;
    }


    protected function addMysqlTypeCode($code, $value, $force = false)
    {
        if ($force) {
            self::$mysqlTypes[$code] = $value;
        } else {
            if (!isset(self::$mysqlTypes[$code])) {
                self::$mysqlTypes[$code] = $value;
            } else {
                return false;
            }
        }
        return true;
    }

    protected function connectToDatabase($host, $user, $password, $database)
    {
        $mysqli = new mysqli($host, $user, $password, $database);
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        return $mysqli;
    }

    function executeQuery($query)
    {
        // do set the debug values here to get the whole query too
        $res = $this->mysqli->query($query) or die("Error In Query : " . $query . "<br /> Mysqli Says " . $this->mysqli->error);
        return $res;
    }



    /*
   name	The name of the column
   orgname	Original column name if an alias was specified
   table	The name of the table this field belongs to (if not calculated)
   orgtable	Original table name if an alias was specified
   def	Reserved for default value, currently always ""
   db	Database (since PHP 5.3.6)
   catalog	The catalog name, always "def" (since PHP 5.3.6)
   max_length	The maximum width of the field for the result set.
   length	The width of the field, as specified in the table definition.
   charsetnr	The character set number for the field.
   flags	An integer representing the bit-flags for the field.
   type	The data type used for this field
   decimals	The number of decimals used (for integer fields)
    */
    public function getTableFieldsInformation($table, $column = "")
    {
        $result = $this->executeQuery("SELECT * FROM " . $table);
        if (!$result) {
            die('Query failed: ' . $this->mysqli->error);
        }
        /* get column metadata */
        $meta = false;
        while ($metares = $result->fetch_field()) {
            if (($metares && $column == "") || ($metares && $column == $metares->name)) {
                foreach ($metares as $key => $val) {
                    if ($key == "type") {
                        $val = $this->getMysqlType($val);
                    } else if($key == "flags"){
                        $val = $this->getFieldFlags($val);
                    }
                    $columninfo[$key] = $val;
                }
                $meta[$metares->name] = $columninfo;
            }
        }
        $result->free();
        return $meta;
    }

    public function getFieldFlags($bitflags){
        $flags = array();
        foreach(self::$bitflagmaps as $bitflag => $map){
            if($bitflags & (int)$bitflag){
                array_push($flags, $map);
            }
        }
        return $flags;
    }




    /*
     * *  $where = array(
    'OR' => array(
        array('Company.name' => 'Future Holdings'),
        array('Company.city' => 'CA')
    ),
    'AND' => array(
        array(
            'OR' => array(
                array('Company.status !=' => 'active'),
                'NOT' => array(
                    array('Company.status' => array('inactive', 'suspended'))
                )
            )
        )
    )
)
     */

}
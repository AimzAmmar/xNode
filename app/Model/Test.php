<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ahmed
 * Date: 27/08/13
 * Time: 19:28
 * To change this template use File | Settings | File Templates.
 */

class Test extends Model{

    var $table = "users";

    var $primaryKey;

    public function check(){
        $data['Test']['firstName'] = "ahmed";
        $data['Test']['lastName'] = "ammar";
        $data['Test']['Id'] = 123;
        echo "array results <br />";
        /*
 * $options = array(
 *      'type' => 'select'/'insert'/'delete'/'update'
 *      'fields' => array("fieldname = ?, field2 = ?")
 *      'conditions' => 'The where clause'
 *      'Model' => name of the model when called from model function and not from querybuilder class. you can leave table and alias empty in that case if the model exists with proper definition.
 *      'table' => name of the table (without prefix)
 *      'alias' => alias of the table if not specified, the name of the Model works like an alias
 *      'join' => array (
 *                          'type' => type of join like 'LeftJoin','RightJoin','OuterJoin' default is just JOIN
 *                          'table' => name of the table to join with
 *                          'alias' => alias of the table, if the table is joined without association, the singular first letter Capital alias is used for instance; "users" => User
 *                          'condition' => condition to join the tables like (Model1.Id >= Model2.UserId)
 *                  ); //join array can be of multiple indices if you need to join multiple tables
 *      'orderBy' => array('Id ASC', 'field DESC')
 *      'groupBy' => "Id, firstName"
 *      'Having' => ''similar to where clause. having (Group.Id > ?)
 * );
 */
        $join = array(array(
            'type' => 'RIGHT',
            'table' => 'logins',
            'alias' => 'Login',
            'condition' => array("? = ?", array("Login.UserId", "User.Id"))
        ),
            array(
                'type' => 'LEFT',
                'table' => 'adresses',
                'alias' => 'Address',
                'condition' => array("? = ?", array("Address.userId", "User.Id"))
        )
        );
        /*
         * $join = array(
         * 'type' => type of join like 'LeftJoin','RightJoin','OuterJoin' default is just JOIN
     *                          'table' => name of the table to join with
     *                          'alias' => alias of the table, if the table is joined without association, the singular first letter Capital alias is used for instance; "users" => User
     *                          'condition' => condition to join the tables like (Model1.Id >= Model2.UserId)
         * );
         */

        $options = array(
            'type' => 'select',
            'fields' => "FirstName, LastName",
        //    'conditions' => array("Id = ? OR LastName LIKE '%?%'", array(123, 'ahmahs')),
            'table' => 'users',
            'alias' => 'User',
            'join' => $join
        );
        echo $this->queryBuilder->buildQuery($options);
        echo "<br /> <br />";
        echo "<br />";
    }
}
?>

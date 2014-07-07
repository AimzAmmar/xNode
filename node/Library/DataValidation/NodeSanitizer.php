<?php
/**
 * xnode.
 * User: Ahmed
 * Date: 02/09/13
 * Time: 00:44
 * 
 */

class NodeSanitizer {

    public function sanitizeValue($value){
        $rval = $value;
        if($rval != ""){
            if(gettype($rval) == "string"){
                trim($value);
                $rval = stripslashes($rval);
                $rval = sprintf("'%s'", $rval);
            }
        }
        return $rval;
    }

    public function sanitizeData($data = array()){
        foreach($data as $key => $val){
            $data[$key] = $this->sanitizeValue($val);
        }
        return $data;
    }

}
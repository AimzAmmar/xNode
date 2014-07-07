<?php
/**
 * xnode.
 * User: Ahmed
 * Date: 01/09/13
 * Time: 10:28
 *
 * Class NodeValidator will be used for sql data validation as well as for view form fields
 */

class NodeValidator
{

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
    public function validateFormData(array $validationData = array())
    {
        $result = array();
        foreach ($validationData as $column => $val) {
            $value = $val['value'];
            $result[$column] = $this->validateData($value, $validationData[$column]);
        }
        return $result;
    }

    /*
     * and extension to validateData function, this function can be used to validate a single column
     * data
     * validationRules = array(
     *                                          'field_type' => email / zipcode / dateTime / IP/ Regex/ URL
     *                                          'type' => 'string / int/ float/ dateTime/ alphanumeric',
     *                                          'max_length' => '15',
     *
     *                                          'error' => '' `The error message to show incase of error; this can be useful if you want to display this message to the login however a custom message will be displayed too`
     *
     *                                      );
     *
     * return trye : array();
     *
     *   array = (
     *              'success' => boolean true / false,
     *              'error' => string error telling whats the problem NULL otherwise
     *               );
     */
    public function validateData($value, array $validationRules = array(), $verbose = false)
    {
        $validate = array('success' => false, 'error' => NULL);
        if (empty($validationRules)) {
            echo "No Valid Validation Rules Specified";
        } else {
            if (isset($validationRules['field_type'])) {
                $fieldType = $validationRules['field_type'];
                $validate = $this->validateFieldType($value, $fieldType, $verbose);
                if(!$verbose && !$validate){
                    return $validate;
                } else if($verbose && $validate['success'] == false){
                    return $validate;
                }
            }
            if (isset($validationRules['type'])) {
                $dataType = $validationRules['type'];
                $validate = $this->validateDataType($value, $dataType, $verbose);
                if(!$verbose && !$validate){
                    return $validate;
                } else if($verbose && $validate['success'] == false){
                    return $validate;
                }
            }
            if (isset($validationRules['length'])) {
                // need to check the length things
                // max_length is not valid, length is.
                //the str length function would do good as if the mysql column is int(11)
                //the int(11) means that it can only have 11 digits in it
                // int(11) => 2147483647
                $maxLength = $validationRules['length'];
                $validate = $this->validateLength($value, $maxLength, $verbose);
            }
        }
            return $validate;
    }

    public function validateLength($value, $length, $verbose = false){
        $valLength = settype($value, "string");
        $valLength = strlen($valLength);
        $validate = array();
        if ($valLength < $length) {
            $validate['success'] = true;
        } else {
            $validate['success'] = false;
            $validate['error'] = "The max_length allowed is $maxLength but supplied $valLength";
        }
        if($verbose){
            return $validate;
        } else {
            return $validate['success'];
        }
    }
    public function validateFieldType($value, $fieldType, $verbose = false){
        $validate = array();
        switch ($fieldType) {
            case "email":
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $validate['success'] = true;
                } else {
                    $validate['success'] = false;
                    $validate['error'] = 'email is not well formatted';
                }
                break;
            case "zipcode":
                if (filter_var($value, FILTER_VALIDATE_INT)) {
                    $validate['success'] = true;
                }
                break;
            case "phone":
                break;
            case "dateTime":
                $date_arr = explode('/', $value);
                if (count($date_arr) == 3) {
                    if (checkdate($date_arr[0], $date_arr[1], $date_arr[2])) {
                        $validate['success'] = true;
                    } else {
                        if ($date_arr[0] <= 0 || $date_arr[0] >= 13) {
                            $validate['success'] = false;
                            $validate['error'] = "Month value out of range. It should be between 1 -12 inclusive";
                        } else if ($date_arr[2] < 1 || $date_arr[2] > 32767) {
                            $validate['success'] = false;
                            $validate['error'] = "Year value out of range. The year is between 1 and 32767 inclusive.";
                        } else {
                            $validate['success'] = false;
                            $validate['error'] = "Day value incorrect. The day is within the allowed number of days for the given month. Leap years are taken into consideration.";
                        }
                    }
                } else {
                    $validate['success'] = false;
                    $validate['error'] = "input is missing something";
                }
                break;
            case "ip":
                if (filter_var($value, FILTER_VALIDATE_IP)) {
                    $validate['success'] = true;
                } else {
                    $validate['success'] = false;
                    $validate['error'] = "Ip seems to be missing something";
                }
                break;
            case "regex":
                if (filter_var($value, FILTER_VALIDATE_REGEXP)) {
                    $validate['success'] = true;
                }
                break;
            default:
                $validate['success'] = false;
                $validate['error'] = "No valid field_type detected";
                break;
        }
        if($verbose){
            return $validate;
        } else {
            return $validate['success'];
        }
    }
   public function validateDataType($value, $dataType, $verbose = false){
        $valueType = gettype($value);
       $validate = array();
        if ($dataType == $valueType) {
            $validate['success'] = true;
        } else {
            $validate['success'] = false;
            $validate['error'] = "Supplied Value is $valueType while expected as ".$dataType;
        }
       if($verbose){
        return $validate;
       } else {
           return $validate['success'];
       }
    }
}
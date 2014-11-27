<?php
/**
 * The validater and fixer class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * The valida class, checking datas by rules.
 *
 * @package framework
 */
class validater
{
    /**
     * The max count of args.
     */
    const MAX_ARGS = 3;

    /**
     * Bool checking.
     * 
     * @param  bool $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkBool($var)
    {
        return filter_var($var, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Int checking.
     * 
     * @param  int $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkInt($var)
    {
        $args = func_get_args();
        if($var != 0) $var = ltrim($var, 0);  // Remove the left 0, filter don't think 00 is an int.

        /* Min is setted. */
        if(isset($args[1]))
        {
            /* And Max is setted. */
            if(isset($args[2]))
            {
                $options = array('options' => array('min_range' => $args[1], 'max_range' => $args[2]));
            }
            else
            {
                $options = array('options' => array('min_range' => $args[1]));
            }

            return filter_var($var, FILTER_VALIDATE_INT, $options);
        }
        else
        {
            return filter_var($var, FILTER_VALIDATE_INT);
        }
    }

    /**
     * Float checking.
     * 
     * @param  float  $var 
     * @param  string $decimal 
     * @static
     * @access public
     * @return bool
     */
    public static function checkFloat($var, $decimal = '.')
    {
        return filter_var($var, FILTER_VALIDATE_FLOAT, array('options' => array('decimail' => $decimal)));
    }

    /**
     * Email checking.
     * 
     * @param  string $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkEmail($var)
    {
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }

    /**
     * URL checking. 
     *
     * The check rule of filter don't support chinese.
     * 
     * @param  string $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkURL($var)
    {
        return filter_var($var, FILTER_VALIDATE_URL);
    }

    /**
     * IP checking.
     * 
     * @param  ip $var 
     * @param  string $range all|public|static|private
     * @static
     * @access public
     * @return bool
     */
    public static function checkIP($var, $range = 'all')
    {
        if($range == 'all') return filter_var($var, FILTER_VALIDATE_IP);
        if($range == 'public static') return filter_var($var, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
        if($range == 'private')
        {
            if($var == '127.0.0.1' or filter_var($var, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false) return true;
            return false;
        }
    }

    /**
     * Date checking. Note: 2009-09-31 will be an valid date, because strtotime auto fixed it to 10-01.
     * 
     * @param  date $date 
     * @static
     * @access public
     * @return bool
     */
    public static function checkDate($date)
    {
        if($date == '0000-00-00') return true;
        $stamp = strtotime($date);
        if(!is_numeric($stamp)) return false; 
        return checkdate(date('m', $stamp), date('d', $stamp), date('Y', $stamp));
    }

    /**
     * REG checking.
     * 
     * @param  string $var 
     * @param  string $reg 
     * @static
     * @access public
     * @return bool
     */
    public static function checkREG($var, $reg)
    {
        return filter_var($var, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $reg)));
    }
    
    /**
     * Length checking.
     * 
     * @param  string $var 
     * @param  string $max 
     * @param  int    $min 
     * @static
     * @access public
     * @return bool
     */
    public static function checkLength($var, $max, $min = 0)
    {
        $length = function_exists('mb_strlen') ? mb_strlen($var, 'utf-8') : strlen($var);
        return self::checkInt($length, $min, $max);
    }

    /**
     * Not empty checking.
     * 
     * @param  mixed $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkNotEmpty($var)
    {
        return !empty($var);
    }

    /**
     * Empty checking.
     * 
     * @param  mixed $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkEmpty($var)
    {
        return empty($var);
    }

    /**
     * Account checking.
     * 
     * @param  string $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkAccount($var)
    {
        return self::checkREG($var, '|^[a-zA-Z0-9_]{1}[a-zA-Z0-9_\.]{1,}[a-zA-Z0-9_]{1}$|');
    }

    /**
     * Check captcha.
     * 
     * @param  mixed    $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkCaptcha($var)
    {
        if(!isset($_SESSION['captcha'])) return false;
        return $var == $_SESSION['captcha'];
    }

    /**
     * Must equal a value.
     * 
     * @param  mixed  $var 
     * @param  mixed $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkEqual($var, $value)
    {
        return $var == $value;
    }

    /**
     * Must greater than a value.
     * 
     * @param  mixed    $var 
     * @param  mixed    $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkGT($var, $value)
    {
        return $var > $value;
    }

    /**
     * Must less than a value.
     * 
     * @param  mixed    $var 
     * @param  mixed    $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkLT($var, $value)
    {
        return $var < $value;
    }

    /**
     * Must greater than a value or equal a value.
     * 
     * @param  mixed    $var 
     * @param  mixed    $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkGE($var, $value)
    {
        return $var >= $value;
    }

    /**
     * Must less than a value or equal a value.
     * 
     * @param  mixed    $var 
     * @param  mixed    $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkLE($var, $value)
    {
        return $var <= $value;
    }

    /**
     * Must in value list.
     * 
     * @param  mixed  $var 
     * @param  mixed $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkIn($var, $value)
    {
        if(!is_array($value)) $value = explode(',', $value);
        return in_array($var, $value);
    }

    /**
     * Call a function to check it.
     * 
     * @param  mixed  $var 
     * @param  string $func 
     * @static
     * @access public
     * @return bool
     */
    public static function call($var, $func)
    {
        return filter_var($var, FILTER_CALLBACK, array('options' => $func));
    }
}

/**
 * fixer class, to fix data types.
 * 
 * @package framework
 */
class fixer
{
    /**
     * The data to be fixed.
     * 
     * @var ojbect
     * @access private
     */
    private $data;

    private $stripedFields = array();
    /**
     * The construction function, according the scope, convert it to object.
     * 
     * @param  string $scope    the scope of the var, should be post|get|server|session|cookie|env
     * @access private
     * @return void
     */
    private function __construct($scope)
    {
       switch($scope)
       {
           case 'post':
               $this->data = (object)$_POST;
               break;
           case 'server':
               $this->data = (object)$_SERVER;
               break;
           case 'get':
               $this->data = (object)$_GET;
               break;
           case 'session':
               $this->data = (object)$_SESSION;
               break;
           case 'cookie':
               $this->data = (object)$_COOKIE;
               break;
           case 'env':
               $this->data = (object)$_ENV;
               break;
           case 'file':
               $this->data = (object)$_FILES;
               break;

           default:
               die('scope not supported, should be post|get|server|session|cookie|env');
       }
    }

    /**
     * The factory function.
     * 
     * @param  string $scope 
     * @access public
     * @return object fixer object.
     */
    public static function input($scope)
    {
        return new fixer($scope);
    }

    /**
     * Email fix.
     * 
     * @param  string $fieldName 
     * @access public
     * @return object fixer object.
     */
    public function cleanEmail($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_EMAIL);
        return $this;
    }

    /**
     * urlenocde.
     * 
     * @param  string $fieldName 
     * @access public
     * @return object fixer object.
     */
    public function encodeURL($fieldName)
    {
        $fields = $this->processFields($fieldName);
        $args   = func_get_args();
        foreach($fields as $fieldName)
        {
            $this->data->$fieldName = isset($args[1]) ?  filter_var($this->data->$fieldName, FILTER_SANITIZE_ENCODED, $args[1]) : filter_var($this->data->$fieldName, FILTER_SANITIZE_ENCODED);
        }
        return $this;
    }

    /**
     * Clean the url.
     * 
     * @param  string $fieldName 
     * @access public
     * @return object fixer object.
     */
    public function cleanURL($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_URL);
        return $this;
    }

    /**
     * Float fixer.
     * 
     * @param  string $fieldName 
     * @access public
     * @return object fixer object.
     */
    public function cleanFloat($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION|FILTER_FLAG_ALLOW_THOUSAND);
        return $this;
    }

    /**
     * Int fixer. 
     * 
     * @param  string $fieldName 
     * @access public
     * @return object fixer object.
     */
    public function cleanINT($fieldName = '')
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_NUMBER_INT);
        return $this;
    }

    /**
     * Special chars 
     * 
     * @param  string $fieldName 
     * @access public
     * @return object fixer object
     */
    public function specialChars($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName)
        {
            if(empty($this->stripedFields) or !in_array($fieldName, $this->stripedFields)) $this->data->$fieldName = $this->specialArray($this->data->$fieldName);
        }
        return $this;
    }

    /**
     * Special array 
     * 
     * @param  mix      $data 
     * @access public
     * @return mix
     */
    public function specialArray($data)
    {
        if(!is_array($data)) return htmlspecialchars($data, ENT_QUOTES);

        foreach($data as &$value) $value = $this->specialArray($value);

        return $data;
    }

    /**
     * Strip tags
     *
     * @param  string $fieldName
     * @param  string $allowedTags
     * @access public
     * @return object fixer object
     */
    public function stripTags($fieldName, $allowedTags)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName)
        {
            if(version_compare(phpversion(), '5.4', '<') and get_magic_quotes_gpc()) $this->data->$fieldName = stripslashes($this->data->$fieldName);

            if(!in_array($fieldName, $this->stripedFields)) $this->data->$fieldName = strip_tags($this->data->$fieldName, $allowedTags);
            $this->stripedFields[] = $fieldName;
        }
        return $this;
    }

    /**
     * Skip special chars.
     * 
     * @param  string    $filename 
     * @access public
     * @return object fixer object
     */
    public function skipSpecial($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->stripedFields[] = $fieldName;
        return $this;
    }

    /**
     * Quote 
     * 
     * @param  string $fieldName 
     * @access public
     * @return object fixer object
     */
    public function quote($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_MAGIC_QUOTES);
        return $this;
    }

    /**
     * Set default value of some fileds.
     * 
     * @param  string $fields 
     * @param  mixed  $value 
     * @access public
     * @return object fixer object
     */
    public function setDefault($fields, $value)
    {
        $fields = strpos($fields, ',') ? explode(',', str_replace(' ', '', $fields)) : array($fields);
        foreach($fields as $fieldName)if(!isset($this->data->$fieldName) or empty($this->data->$fieldName)) $this->data->$fieldName = $value;
        return $this;
    }

    /**
     * Set value of a filed on the condition is true.
     * 
     * @param  bool   $condition 
     * @param  string $fieldName 
     * @param  string $value 
     * @access public
     * @return object fixer object
     */
    public function setIF($condition, $fieldName, $value)
    {
        if($condition) $this->data->$fieldName = $value;
        return $this;
    }

    /**
     * Set the value of a filed in force.
     * 
     * @param  string $fieldName 
     * @param  mixed  $value 
     * @access public
     * @return object fixer object
     */
    public function setForce($fieldName, $value)
    {
        $this->data->$fieldName = $value;
        return $this;
    }

    /**
     * Remove a field.
     * 
     * @param  string $fieldName 
     * @access public
     * @return object fixer object
     */
    public function remove($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) unset($this->data->$fieldName);
        return $this;
    }

    /**
     * Remove a filed on the condition is true.
     * 
     * @param  bool   $condition 
     * @param  string $fields 
     * @access public
     * @return object fixer object
     */
    public function removeIF($condition, $fields)
    {
        $fields = $this->processFields($fields);
        if($condition) foreach($fields as $fieldName) unset($this->data->$fieldName);
        return $this;
    }

    /**
     * Add an item to the data.
     * 
     * @param  string $fieldName 
     * @param  mixed  $value 
     * @access public
     * @return object fixer object
     */
    public function add($fieldName, $value)
    {
        $this->data->$fieldName = $value;
        return $this;
    }

    /**
     * Add an item to the data on the condition if true.
     * 
     * @param  bool   $condition 
     * @param  string $fieldName 
     * @param  mixed  $value 
     * @access public
     * @return object fixer object
     */
    public function addIF($condition, $fieldName, $value)
    {
        if($condition) $this->data->$fieldName = $value;
        return $this;
    }

    /**
     * Join the field.
     * 
     * @param  string $fieldName 
     * @param  string $value 
     * @access public
     * @return object fixer object
     */
    public function join($fieldName, $value)
    {
        if(!isset($this->data->$fieldName) or !is_array($this->data->$fieldName)) return $this;
        $this->data->$fieldName = join($value, $this->data->$fieldName);
        return $this;
    }

    /**
     * Call a function to fix it.
     * 
     * @param  string $fieldName 
     * @param  string $func 
     * @access public
     * @return object fixer object
     */
    public function callFunc($fieldName, $func)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_CALLBACK, array('options' => $func));
        return $this;
    }

    /**
     * Get the data after fixing.
     *
     * If only one field, return it's value directly. 
     * More fields, remove other fields not in the list and return $data.
     * 
     * @param  string $fields   the fields list.
     * @access public
     * @return mix
     */
    public function get($fields = '')
    {
        $fields = str_replace(' ', '', trim($fields));
        foreach($this->data as $field => $value) $this->specialChars($field);

        if(empty($fields)) return $this->data;
        if(strpos($fields, ',') === false) return $this->data->$fields;

        $fields = array_flip(explode(',', $fields));
        foreach($this->data as $field => $value)
        {
            if(!isset($fields[$field])) unset($this->data->$field);
            if(!in_array($field, $this->stripedFields)) $this->data->$field = $this->specialChars($this->data->field);
        }

        return $this->data;
    }

    /**
     * Process fields, if contains ',', split it to array. If not in $data, remove it.
     * 
     * @param  string $fields 
     * @access private
     * @return array
     */
    private function processFields($fields)
    {
        $fields = strpos($fields, ',') ? explode(',', str_replace(' ', '', $fields)) : array($fields);
        foreach($fields as $key => $fieldName) if(!isset($this->data->$fieldName)) unset($fields[$key]);
        return $fields;
    }
}

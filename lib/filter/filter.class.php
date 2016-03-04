<?php
/**
 * ZenTaoPHP的验证和过滤类。
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
 * validater类，检查数据是否符合规则。
 * The validater class, checking data by rules.
 * 
 * @package framework
 */
class validater
{
    /**
     * 最大参数个数。
     * The max count of args.
     */
    const MAX_ARGS = 3;

    /**
     * 是否是Bool类型。
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
     * 是否是Int类型。
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
        if($var != 0) $var = ltrim($var, 0);  // 去掉变量左边的0，00不是Int类型
        // Remove the left 0, filter don't think 00 is an int.

        /* 如果设置了最小的整数。  Min is setted.  */
        if(isset($args[1]))
        {
            /* 如果最大的整数也设置了。  And Max is setted.  */
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
     * 检查不是Int类型。
     * Not int checking. 
     * 
     * @param  int    $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkNotInt($var)
    {
        return !self::checkInt($var);
    }

    /**
     * 检查Float类型。
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
        return filter_var($var, FILTER_VALIDATE_FLOAT, array('options' => array('decimal' => $decimal)));
    }

    /**
     * 检查Email。
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
     * 检查电话或手机号码
     * Check phone number.
     * 
     * @param  string    $var 
     * @static
     * @access public
     * @return void
     */
    public static function checkPhone($var)
    {
        return (validater::checkTel($var) or validater::checkMobile($var));
    }

    /**
     * 检查电话号码
     * Check tel number.
     * 
     * @param  int    $var 
     * @static
     * @access public
     * @return void
     */
    public static function checkTel($var)
    {
        return preg_match("/^([0-9]{3,4}-)?[0-9]{7,8}$/", $var);
    }

    /**
     * 检查手机号码
     * Check mobile number.
     * 
     * @param  string    $var 
     * @static
     * @access public
     * @return void
     */
    public static function checkMobile($var)
    {
        return preg_match("/^1[3-5,8]{1}[0-9]{9}$/", $var);
    }

    /**
     * 检查网址。
     * 该规则不支持中文字符的网址。
     *
     * URL checking. 
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
     * 检查域名，不支持中文。
     * Domain checking. 
     *
     * The check rule of filter don't support chinese.
     * 
     * @param  string $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkDomain($var)
    {
        return preg_match('/^([a-z0-9-]+\.)+[a-z]{2,15}$/', $var);
    }

    /**
     * 检查IP地址。
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
     * 日期检查。注意，2009-09-31是一个合法日期，系统会将它转换为2009-10-01。
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
     * 检查正则表达式。
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
     * 检查长度。
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
     * 检查不为空。
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
     * 检查为空。
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
     * 检查用户名。
     * Account checking.
     * 
     * @param  string $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkAccount($var)
    {
        global $config;
        $accountRule = empty($config->accountRule) ? '|^[a-zA-Z0-9_]{1}[a-zA-Z0-9_\.]{1,}[a-zA-Z0-9_]{1}$|' : $config->accountRule;
        return self::checkREG($var, $accountRule);
    }

    /**
     * 检查Code。
     * Check code.
     * 
     * @param  string $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkCode($var)
    {
        return self::checkREG($var, '|^[A-Za-z0-9]+$|');
    }

    /**
     * 检查验证码。
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
     * 是否等于给定的值。
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
     * 检查不等于给定的值
     * Must not equal a value.
     * 
     * @param  mixed    $var 
     * @param  mixed    $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkNotEqual($var, $value)
    {
        return $var != $value;
    }

    /**
     * 检查大于给定的值。
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
     * 检查小于给定的值
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
     * 检查大于等于给定的值
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
     * 检查小于等于给定的值
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
     * 检查是否在给定的列表里面。
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
     * 检查文件名。
     * Check file name.
     * 
     * @param  string    $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkFileName($var)
    {
        return !preg_match('/>+|:+|<+/', $var);
    }

    /**
     * 检查敏感词。
     * Check sensitive words.
     * 
     * @param  object   $vars 
     * @param  array    $dicts 
     * @static
     * @access public
     * @return void
     */
    public static function checkSensitive($vars, $dicts)
    {
        foreach($vars as $var)
        {
            if(!$var) continue;
            foreach($dicts as $dict)
            {
                if(strpos($var, $dict) === false) continue;
                if(strpos($var, $dict) !== false) return false;
            }
        }
        return true;
    }

    /**
     * 调用一个方法进行检查。
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
 * fixer类，处理数据。
 * fixer class, to fix data types.
 * 
 * @package framework
 */
class fixer
{
    /**
     * 处理的数据。
     * The data to be fixed.
     * 
     * @var ojbect
     * @access private
     */
    private $data;

    /**
     * 跳过处理的字段。
     * The fields to striped.
     * 
     * @var array 
     * @access private
     */
    private $stripedFields = array();

    /**
     * 构造方法，将超级全局变量转换为对象。
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
     * 工厂方法。
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
     * 处理Email。
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
     * url编码。
     * urlencode.
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
     * 清理网址。
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
     * 处理Float类型。
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
     * 处理Int类型。
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
     * 将字符串转换为可以在浏览器查看的编码。
     * Special chars.
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
     * 忽略该标签。
     * Strip tags 
     * 
     * @param  string $fieldName 
     * @param  string $allowableTags 
     * @access public
     * @return object fixer object
     */
    public function stripTags($fieldName, $allowedTags)
    {
        global $app;
        $app->loadClass('purifier', true);
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Filter.YouTube', 1);

        /* Disable caching. */
        $config->set('Cache.DefinitionImpl', null);

        $purifier = new HTMLPurifier($config);
        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');

        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName)
        {
            if(version_compare(phpversion(), '5.4', '<') and get_magic_quotes_gpc()) $this->data->$fieldName = stripslashes($this->data->$fieldName);

            if(!in_array($fieldName, $this->stripedFields))
            {
                if(!defined('RUN_MODE') or RUN_MODE != 'admin') $this->data->$fieldName = $purifier->purify($this->data->$fieldName);
            }
            $this->stripedFields[] = $fieldName;
        }
        return $this;
    }

    /**
     * 忽略处理给定的字段。
     * Skip special chars check.
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
     * 给字段添加引用，防止字符与关键字冲突。
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
     * 设置字段的默认值。
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
     * 如果条件为真，则为字段赋值。
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
     * 强制给字段赋值。
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
     * 移除一个字段。
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
     * 如果条件为真，移除该字段。
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
     * 为数据添加新的项。
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
     * 如果条件为真，则为数据添加新的项。
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
     * 为指定字段增加值。 
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
     * 调用一个方法来处理数据。
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
     * 处理完成后返回数据。
     * Get the data after fixing.
     * 
     * @param  string $fieldName 
     * @access public
     * @return object
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
     * 处理字段，如果字段中含有','，拆分成数组。如果字段不在$data中，删除掉。
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

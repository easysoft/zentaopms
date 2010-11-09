<?php
/**
 * The validater and fixer class file of ZenTaoPHP.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPHP
 * @version     $Id: filter.class.php 134 2010-09-11 07:24:27Z wwccss $
 * @link        http://www.zentao.net
 */
/**
 * validate类，提供对数据的验证。
 * 
 * @package ZenTaoPHP
 */
class validater
{
    /**
     * 参数个数的最大值。
     */
    const MAX_ARGS = 3;

    /* 检查是否是布尔型。*/
    public static function checkBool($var)
    {
        return filter_var($var, FILTER_VALIDATE_BOOLEAN);
    }

    /* 检查是否是整型。*/
    public static function checkInt($var)
    {
        $args = func_get_args();
        if($var != 0) $var = ltrim($var, 0);  // 将左边的0去掉，filter的这个过滤规则比较严格。

        /* 设置了min。*/
        if(isset($args[1]))
        {
            /* 同时设置了max。*/
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

    /* 检查是否是浮点型。*/
    public static function checkFloat($var, $decimal = '.')
    {
        return filter_var($var, FILTER_VALIDATE_FLOAT, array('options' => array('decimail' => $decimal)));
    }

    /* 检查是否是email地址。*/
    public static function checkEmail($var)
    {
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }

    /* 检查是否是URL地址。备注：filter的这个检查并不靠普，比如如果url地址含有中文，就会失效。 */
    public static function checkURL($var)
    {
        return filter_var($var, FILTER_VALIDATE_URL);
    }

    /* 检查是否是IP地址。NO_PRIV_RANGE是检查是否是私有地址，NO_RES_RANGE检查是否是保留IP地址。*/
    public static function checkIP($var, $range = 'all')
    {
        if($range == 'all')    return filter_var($var, FILTER_VALIDATE_IP);
        if($range == 'public static') return filter_var($var, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
        if($range == 'private')
        {
            if(filter_var($var, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false) return $var;
            return false;
        }
    }

    /* 检查是否是日期。bug: 2009-09-31会被认为合法的日期，因为strtotime自动将其改为了10-01。*/
    public static function checkDate($date)
    {
        if($date == '0000-00-00') return true;
        $stamp = strtotime($date);
        if(!is_numeric($stamp)) return false; 
        return checkdate(date('m', $stamp), date('d', $stamp), date('Y', $stamp));
    }

    /* 检查是否符合正则表达式。*/
    public static function checkREG($var, $reg)
    {
        return filter_var($var, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $reg)));
    }
    
    /* 检查长度是否在指定的范围内。*/
    public static function checkLength($var, $max, $min = 0)
    {
        return self::checkInt(strlen($var), $min, $max);
    }

    /* 检查长度是否在指定的范围内。*/
    public static function checkNotEmpty($var)
    {
        return !empty($var);
    }

    /* 检查用户名。*/
    public static function checkAccount($var)
    {
        return self::checkREG($var, '|[a-zA-Z0-9._]{3}|');
    }

    /* 必须为某值。*/
    public static function checkEqual($var, $value)
    {
        return $var == $value;
    }

    /* 调用回掉函数。*/
    public static function call($var, $func)
    {
        return filter_var($var, FILTER_CALLBACK, array('options' => $func));
    }
}

/**
 * fixer类，提供对数据的修正。
 * 
 * @package ZenTaoPHP
 */
class fixer
{
    /**
     * 要处理的数据。
     * 
     * @var ojbect
     * @access private
     */
    private $data;

    /* 构造函数。*/
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

    /* factory。*/
    public function input($scope)
    {
        return new fixer($scope);
    }

    /* 去除email里面的非法字符。*/
    public function cleanEmail($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_EMAIL);
        return $this;
    }

    /* 对URL进行编码。*/
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

    /* 去除url里面的非法字符。*/
    public function cleanURL($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_URL);
        return $this;
    }

    /* 获取浮点数。*/
    public function cleanFloat($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION|FILTER_FLAG_ALLOW_THOUSAND);
        return $this;
    }

    /* 获取整型。*/
    public function cleanINT($fieldName = '')
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_NUMBER_INT);
        return $this;
    }

    /* 处理特殊字符。*/
    public function specialChars($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = htmlspecialchars($this->data->$fieldName);
        return $this;
    }

    /* 去除字符串里面的标签。*/
    public function stripTags($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_STRING);
        return $this;
    }

    /* 添加斜线。*/
    public function quote($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_MAGIC_QUOTES);
        return $this;
    }

    /* 设置默认值。*/
    public function setDefault($fields, $value)
    {
        $fields = strpos($fields, ',') ? explode(',', str_replace(' ', '', $fields)) : array($fields);
        foreach($fields as $fieldName)if(!isset($this->data->$fieldName) or empty($this->data->$fieldName)) $this->data->$fieldName = $value;
        return $this;
    }

    /* 条件设置。*/
    public function setIF($condition, $fieldName, $value)
    {
        if($condition) $this->data->$fieldName = $value;
        return $this;
    }

    /* 强制设置。*/
    public function setForce($fieldName, $value)
    {
        $this->data->$fieldName = $value;
        return $this;
    }

    /* 删除某一个字段。*/
    public function remove($fieldName)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) unset($this->data->$fieldName);
        return $this;
    }

    /* 条件删除。*/
    public function removeIF($condition, $fields)
    {
        $fields = $this->processFields($fields);
        if($condition) foreach($fields as $fieldName) unset($this->data->$fieldName);
        return $this;
    }

    /* 添加一个字段。*/
    public function add($fieldName, $value)
    {
        $this->data->$fieldName = $value;
        return $this;
    }

    /* 条件添加。*/
    public function addIF($condition, $fieldName, $value)
    {
        if($condition) $this->data->$fieldName = $value;
        return $this;
    }

    /* 连接。*/
    public function join($fieldName, $value)
    {
        if(!isset($this->data->$fieldName) or !is_array($this->data->$fieldName)) return $this;
        $this->data->$fieldName = join($value, $this->data->$fieldName);
        return $this;
    }

    /* 调用回掉函数。*/
    public function callFunc($fieldName, $func)
    {
        $fields = $this->processFields($fieldName);
        foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_CALLBACK, array('options' => $func));
        return $this;
    }

    /* 返回最终处理之后的数据。*/
    public function get($fieldName = '')
    {
        if(empty($fieldName)) return $this->data;
        return $this->data->$fieldName;
    }

    /* 处理传入的字段名：如果含有逗号，将其拆为数组。然后检查data变量中是否有这个字段。*/
    private function processFields($fields)
    {
        $fields = strpos($fields, ',') ? explode(',', str_replace(' ', '', $fields)) : array($fields);
        foreach($fields as $key => $fieldName) if(!isset($this->data->$fieldName)) unset($fields[$key]);
        return $fields;
    }
}

<?php
error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('CONFIG_ROOT', dirname(dirname(__FILE__)) . '/config/');
define('MODULE_ROOT', dirname(dirname(__FILE__)) . '/case/');
include CONFIG_ROOT . '/config.php';

include dirname(__FILE__) . DS . 'result.class.php';
include dirname(__FILE__) . DS . 'drivers' . DS . 'webdriver' . DS . "webdriver.class.php";
$driver = new webdriver($config);

/* Set the error reporting. */
include 'page.class.php';

$includeFiles = get_included_files();
if($includeFiles)
{
    foreach(glob(dirname($includeFiles[0]) . '/page/*') as $file) include $file;
}

function loadModel($module)
{
    $classPath = MODULE_ROOT . "{$module}/{$module}.class.php";
    if(file_exists($classPath))
    {
        include $classPath;
        return new $module();
    }

    return false;
}

/**
 * Save variable to $_result.
 *
 * @param  mixed    $result
 * @access public
 * @return bool true
 */
function r($result)
{
    global $_result;
    $_result = $result;
    return true;
}

/**
 * Print value or properties.
 *
 * @param  string    $key
 * @param  string    $delimiter
 * @access public
 * @return void
 */
function p($keys = '', $delimiter = ',')
{
    global $_result, $results;

    if(empty($_result)) return print(implode("\n", array_fill(0, substr_count($keys, $delimiter) + 1, 0)) . "\n");

    if(is_array($_result) && isset($_result['code']) && $_result['code'] == 'fail') return print((string) $_result['message'] . "\n");

    /* Print $_result. */
    if($keys === '' && is_array($_result)) return print_r($_result) . "\n";
    if($keys === '' || !is_array($_result) && !is_object($_result)) return print((string) $_result . "\n");
    if(in_array(explode(':', $keys)[1], array('text', 'attr', 'url', 'title')))
    {
        list($elementName, $action) = explode(':', $keys);

        if($action == 'text')  $results->get()['page']->$elementName->getText();
        if($action == 'url')   $results->get()['page']->getUrl();
        if($action == 'title') $results->get()['page']->getTitle();
    }

    $parts  = explode(';', $keys);
    foreach($parts as $part)
    {
        $values = getValues($_result, $part, $delimiter);
        if(!is_array($values)) continue;

        foreach($values as $value) echo $value . "\n";
    }

    return true;
}

/**
 * 当数组/对象变量$var存在$key项时，返回存在的对应值或设定值，否则返回$key或不存在的设定值。
 * When the $var has the $key, return it, else result one default value.
 *
 * @param  array|object    $var
 * @param  mixed           $valueWhenNone     value when the key not exits.
 * @param  mixed           $valueWhenExists   value when the key exits.
 * @access public
 * @return mixed
 */
function zget($var, $key, $valueWhenNone = false, $valueWhenExists = false)
{
  if(!is_array($var) and !is_object($var)) return false;

  $type = is_array($var) ? 'array' : 'object';
  $checkExists = $type == 'array' ? isset($var[$key]) : isset($var->$key);

  if($checkExists)
  {
      if($valueWhenExists !== false) return $valueWhenExists;
      return $type == 'array' ? $var[$key] : $var->$key;
  }

  if($valueWhenNone !== false) return $valueWhenNone;
  return $key;
}

/**
 * Get values
 *
 * @param mixed  $value
 * @param string $keys
 * @param string $delimiter
 * @access public
 * @return void
 */
function getValues($value, $keys, $delimiter)
{
    $object = '';
    $index  = -1;
    $pos    = strpos($keys, ':');
    if($pos)
    {
        $arrKey = substr($keys, 0, $pos);
        $keys   = substr($keys, $pos + 1);

        $pos = strpos($arrKey, '[');
        if($pos)
        {
            $object = substr($arrKey, 0, $pos);
            $index  = trim(substr($arrKey, $pos + 1), ']');
        }
        else
        {
            $index = $arrKey;
        }
    }
    $keys = explode($delimiter, $keys);

    if($object !== '')
    {
        if(is_array($value))
        {
            $value = $value[$object];
        }
        else if(is_object($value))
        {
            $value = $value->$object;
        }
        else
        {
            return print("Error: No object name '$object'.\n");
        }
    }

    if($index != -1)
    {
        if(is_array($value))
        {
            if(!isset($value[$index])) return print("Error: Cannot get index $index.\n");
            $value = $value[$index];
        }
        else if(is_object($value))
        {
            if(!isset($value->$index)) return print("Error: Cannot get index $index.\n");
            $value = $value->$index;
        }
        else
        {
            return print("Error: Not array, cannot get index $index.\n");
        }
    }

    $values = array();
    foreach($keys as $key) $values[] = zget($value, $key, '');

    return $values;
}

/**
 * Expect values, ztf will put params to step.
 *
 * @param  string    $exepect
 * @access public
 * @return void
 */
function e($expect)
{
}

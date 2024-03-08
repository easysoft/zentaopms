<?php
define('DS', DIRECTORY_SEPARATOR);
define('CONFIG_ROOT', dirname(dirname(__FILE__)) . '/config/');
include CONFIG_ROOT . '/config.php';

/* Set the error reporting. */
error_reporting(E_ALL);

class uiTester
{
    public static $instance;
    public $config;
    public $caseTitle;
    public $caseCode;
    public $reportFile;
    public $reportURL;
    public $errors = array();
    public $results;

    /**
     * Factory function.
     *
     * @param  string    $project
     * @param  string    $driver
     * @param  object    $config
     * @static
     * @access public
     * @return object
     */
    public static function factory($project, $driver, $config)
    {
        if(empty(self::$instance)) self::$instance = array();
        if(isset(self::$instance[$driver])) return self::$instance[$driver];

        $className = $driver;
        $classFile = dirname(__FILE__) . DS . 'drivers' . DS . $driver . DS . "$driver.class.php";
        if(!is_file($classFile)) return trigger_error("the class file {$classFile} not found");

        include $classFile;

        self::$instance[$driver] = new $className($project, $config);

        return self::$instance[$driver];
    }

    /**
     * __construct function
     *
     * @param  string    $driver
     * @param  object    $config
     * @access public
     * @return void
     */
    public function __construct($project)
    {
        global $config;
        $this->config = $config;
    }

    /**
     * Auto echo return info.
     *
     * @access public
     * @return mixed
     */
    public function __toString()
    {
        $output = new stdclass;
        $output->result     = end($this->results);
        $output->reportFile = $this->reportFile;
        $output->reportURL  = $this->reportURL;
        $output->errors     = $this->errors;
        $output->caseTitle  = $this->caseTitle;

        return json_encode($output);
    }

    /**
     * Set title and code.
     *
     * @param  string    $title
     * @param  string    $code
     * @param  string    $project
     * @access public
     * @return void
     */
    public function setCase($title, $code)
    {
        $this->caseTitle = $title;
        $this->caseCode  = $code;
        $this->initReport();
    }

    /**
     * SetWebRoot of the driver.
     *
     * @param  string    $webRoot
     * @access public
     * @return mixed
     */
    public function setWebRoot($webRoot)
    {
        $this->config->webRoot = $webRoot;
    }

    /**
     * Create and init report.
     *
     * @access public
     * @return void
     */
    public function initReport()
    {
        $reportType     = $this->config->reportType;
        $reportTemplate = $this->config->reportTemplate[$reportType];

        $reportPath = $this->config->reportRoot . DS . $this->caseCode . DS;
        $suffix     = ($reportType == 'markdown') ? 'md' : $reportType;
        $this->reportFile = $reportPath . date('ymdhis') . '.' . $suffix;
        $this->reportURL  = str_replace($this->config->reportRoot, $this->config->reportWebRoot, $this->reportFile);

        if(!is_dir($reportPath)) mkdir($reportPath, 0777, true);

        $reportContent = str_replace(array('{TITLE}', '{CODE}'), array($this->caseTitle, $this->caseCode), $reportTemplate);

        file_put_contents($this->reportFile, $reportContent);
    }

    /**
     * Export test report of a page.
     *
     * @param  array  $alertsMessage
     * @access public
     * @return void
     */
    public function saveReport($message)
    {
        $message .= "\n";
        file_put_contents($this->reportFile, $message, FILE_APPEND);
    }

    /**
     * Print close tag.
     *
     * @access public
     * @return void
     */
    public function endReport()
    {
        $message = '</div></body></html>';
        $this->saveReport($message);
    }

    /**
     * Log assert result to asserts.
     *
     * @param  string    $result
     * @access public
     * @return mixed
     */
    public function logAsserts($result)
    {
        $this->results[] = $result;
    }

    /**
     * Get asserts.
     *
     * @access public
     * @return array
     */
    public function getAsserts()
    {
        return $this->results;
    }
}

include 'page.class.php';

$includeFiles = get_included_files();
if($includeFiles)
{
    foreach(glob(dirname($includeFiles[0]) . '/page/*') as $file) include $file;
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
    global $_result;

    if(empty($_result)) return print(implode("\n", array_fill(0, substr_count($keys, $delimiter) + 1, 0)) . "\n");

    if(is_array($_result) && isset($_result['code']) && $_result['code'] == 'fail') return print((string) $_result['message'] . "\n");

    /* Print $_result. */
    if($keys === '' && is_array($_result)) return print_r($_result) . "\n";
    if($keys === '' || !is_array($_result) && !is_object($_result)) return print((string) $_result . "\n");

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

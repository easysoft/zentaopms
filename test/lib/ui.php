<?php
error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('CONFIG_ROOT', dirname(__FILE__, 2) . '/config/');
define('MODULE_ROOT', dirname(__FILE__, 2) . '/ui/');
include CONFIG_ROOT . '/config.php';

include __DIR__ . '/result.class.php';
include __DIR__ . '/drivers/webdriver/webdriver.class.php';
$driver = new webdriver($config);

/* Set the error reporting. */
include 'page.class.php';

include dirname(__FILE__, 3) . '/framework/helper.class.php';
include dirname(__FILE__, 3) . '/lib/dao/dao.class.php';
$dao = new dao();
include 'yaml.class.php';

/**
 * Save variable to $_result.
 *
 * @param  mixed    $result
 * @access public
 * @return bool true
 */
function r($result)
{
    global $_result, $result;
    $_result = $result->get();
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
    global $_result, $result;

    if(empty($_result)) return print(implode("\n", array_fill(0, substr_count($keys, $delimiter) + 1, 0)) . "\n");
    if($keys == 'url')  return print($result->get('url') . PHP_EOL);

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
 * Get webdriver page attr.
 *
 * @param  string $arrKey
 * @param  array  $keys
 * @access public
 * @return object
 */
function getPageAttr($arrKey, $keys)
{
    global $result;
    $value  = new stdclass();
    $page   = $result->get('page');
    $method = 'get' . ucfirst($arrKey);
    foreach($keys as $key)
    {
        if(in_array($arrKey, array('text', 'attr', 'value')))
        {
            if(strpos($key, '-') === false)
            {
                $value->$key = $page->{$key}->$method();
            }
            else
            {
                $pos     = strpos($key, '-');
                $element = substr($key, 0, $pos);
                $attr    = substr($key, $pos + 1);
                $value->$key = $page->{$element}->$method($attr);
            }
        }
        elseif($arrKey == 'url')
        {
            print($result->get('url') . PHP_EOL);
        }
        else
        {
            $value->$key = $page->$method();
        }
    }

    return $value;
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
    $index  = -1;
    $pos    = strpos($keys, ':');
    if($pos)
    {
        $arrKey = substr($keys, 0, $pos);
        $keys   = substr($keys, $pos + 1);

        $index = $arrKey;
    }

    $keys = explode($delimiter, $keys);
    if($index != -1)
    {
        if(in_array($arrKey, array('text', 'attr', 'url', 'title', 'value')))
        {
            $value = getPageAttr($arrKey, $keys);
        }
        elseif(is_array($value))
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

function success($status = '')
{
    global $result;
    $result->status= $status;

    return $result->get();
}

function failed($status)
{
    global $result;
    $result->status= $status;

    return $result->get('status');
}

class tester
{
    public $page;
    public $config;
    public $result;

    public function __construct()
    {
        global $config, $result;
        $this->config = $config;
        $this->page   = new Page();
        $this->result = $result;
    }

    /**
     * Login to the test URL.
     *
     * @param  string $account
     * @param  string $password
     * @access public
     * @return void
     */
    public function login($account = '', $password = '')
    {
        if(!$account)  $account  = $this->config->defaultAccount;
        if(!$password) $password = $this->config->defaultPassword;

        $this->page->deleteCookie();

        $this->page->get('');
        $this->page->getErrors();
        $this->page->account->setValue($account);
        $this->page->password->setValue($password);
        $this->page->submit->click();

        $this->page->getCookie();

        return $this->page;
    }

    public function loadPage($module, $method, $params = array(), $iframeID = '')
    {
        if($this->config->requestType == 'GET')
        {
            $url = "index.php?m=$module&f=$method";
            if(!empty($params)) foreach($params as $key => $value) $url .= "&$key=$value";
        }
        else
        {
            $url = "$module-$method";
            if(!empty($params)) foreach($params as $value) $url .= "-$value";
            $url .= ".html";
        }

        $this->page->go($url); // 跳转到地址
        $appIframeID = $iframeID ? $iframeID : "appIframe-{$module}";
        $this->page->wait(1);
        $this->page->getErrors($appIframeID);

        return $this->setPage($module, $method);
    }

    public function setPage($module, $method)
    {
        global $result;

        $pageClass = "{$method}Page";
        if(!class_exists($pageClass))include dirname(__FILE__, 2). "/ui/$module/page/$method.php";

        $methodPage = new $pageClass();
        $result->setPage($methodPage);
        return $methodPage;
    }
}

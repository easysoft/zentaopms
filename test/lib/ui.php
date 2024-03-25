<?php
/* Set the error reporting. */
error_reporting(E_ALL);

/* 设置常量和常用目录路径 */
define('RUN_MODE', 'test');
$zentaoRoot    = dirname(__FILE__, 3) . '/';
$testPath      = $zentaoRoot . 'test' . '/';
$frameworkRoot = $zentaoRoot . 'framework' . '/';


/* Load the framework. */
include $frameworkRoot . 'router.class.php';
include $frameworkRoot . 'control.class.php';
include $frameworkRoot . 'model.class.php';
include $frameworkRoot . 'helper.class.php';


/* 初始化禅道框架 */
$app      = router::createApp('pms', dirname(__FILE__, 3), 'router');
$uiTester = $app->loadCommon();

/* 加载框架配置项 */
define('CONFIG_ROOT', $testPath . 'config' . '/');
include CONFIG_ROOT . 'config.php';


/* 加载用例执行结果处理类 */
include __DIR__ . '/result.class.php';

/* 初始化php-webdriver类 */
include __DIR__ . '/webdriver/webdriver.class.php';
$driver = new webdriver();

/* 初始化页面元素 */
include 'page.class.php';

/* 加载测试数据处理类，初始化测试数据 */
include 'yaml.class.php';

/**
 * Save variable to $_result.
 *
 * @param  mixed    $result
 * @access public
 * @return bool true
 */
function r()
{
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
    global $result;

    $_result = $result->get();

    if(empty($_result)) return print(implode("\n", array_fill(0, substr_count($keys, $delimiter) + 1, 0)) . "\n");

    if(is_array($_result) && isset($_result['code']) && $_result['code'] == 'fail') return print((string) $_result['message'] . "\n");

    /* Print $_result. */
    if($keys === '' && is_array($_result)) return print_r($_result) . "\n";

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
        if(in_array($arrKey, array('text', 'attr', 'title', 'value')))
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
function e()
{
}

/**
 * Set success result.
 *
 * @access public
 * @return object
 */
function success()
{
    global $result;
    $result->status = 'SUCCESS';

    return $result;
}

/**
 * Set failure result.
 *
 * @param  string    $message
 * @access public
 * @return object
 */
function failed($message)
{
    global $result;
    $result->status  = 'FAILED';
    $result->message = $message;
    if(!empty($result->page)) $result->page->getErrors();

    return $result;
}

/**
 * Close the Browser.
 *
 * @access public
 * @return void
 */
function closeBrowser()
{
    global $driver;
    $driver->closeBrowser();
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
        if(!$account)  $account  = $this->config->uitest->defaultAccount;
        if(!$password) $password = $this->config->uitest->defaultPassword;

        $this->page->deleteCookie();

        $this->page->get('');
        $this->page->getErrors();
        $this->page->account->setValue($account);
        $this->page->password->setValue($password);
        $this->page->submit->click();

        $this->page->getCookie();

        return $this->page;
    }

    /**
     * Open a test URL.
     *
     * @param  string $module
     * @param  string $method
     * @param  array  $params
     * @param  string $iframeID
     * @access public
     * @return object
     */
    public function openURL($module, $method, $params = array(), $iframeID = '')
    {
        if(!$module || !$method) return;
        $this->result->module = $module;
        $this->result->method = $method;

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

        return $this;
    }

    /**
     * Set up a test page.
     *
     * @param  string  $module
     * @param  string  $method
     * @access public
     * @return object
     */
    public function initPage($module = '', $method = '')
    {
        if($this->result->module && !$module) $module = $this->result->module;
        if($this->result->method && !$method) $method = $this->result->method;

        $pageClass = "{$method}Page";
        if(!class_exists($pageClass)) include dirname(__FILE__, 3). "/module/$module/test/ui/page/$method.php";

        $methodPage = new $pageClass();
        $this->result->setPage($methodPage);
        return $methodPage;
    }

    /**
     * Visit a form test page.
     *
     * @param  int    $module
     * @param  int    $method
     * @param  array  $params
     * @param  string $iframeID
     * @access public
     * @return object
     */
    public function formPage($module, $method, $params = array(), $iframeID = '')
    {
        $this->openURL($module, $method, $params, $iframeID);
        return $this->initPage();
    }

    /**
     * Parsing the current page's URL.
     *
     * @access public
     * @return void
     */
   public function parseCurrentUrl()
    {
        if(empty($this->result->pageObject)) return;

        return $this->result->pageObject->getUrl();
    }
}

<?php
/* Set the error reporting. */
error_reporting(E_ALL);

/* 设置常量和常用目录路径 */
define('RUN_MODE', 'uitest');
$zentaoRoot    = dirname(__FILE__, 3) . '/';
$configRoot    = $zentaoRoot . 'test/config/';
$frameworkRoot = $zentaoRoot . 'framework' . '/';

if($argc > 1 && $argv[1] == '-extract')
{
    include 'parseztfstep.php';
    printSteps();
    exit;
}

/* Load the framework. */
include $frameworkRoot . 'router.class.php';
include $frameworkRoot . 'control.class.php';
include $frameworkRoot . 'model.class.php';
include $frameworkRoot . 'helper.class.php';

/* 初始化禅道框架 */
$app      = router::createApp('pms', dirname(__FILE__, 3), 'router');
$uiTester = $app->loadCommon();

/* 加载框架配置项 */
include $configRoot . 'config.php';

/* 加载框架基础类 */
include 'result.class.php';              // 加载用例执行结果处理类
include 'page.class.php';                // 加载页面元素类
include 'yaml.class.php';                // 加载测试数据处理类
include 'webdriver/webdriver.class.php'; // 加载php-webdriver类

/**
 * Save variable to $_result.
 *
 * @param  mixed    $result
 * @access public
 * @return bool true
 */
function r($testResult)
{
    global $_result;
    $_result = $testResult;
    return true;
}

/**
 * Print value or properties.
 *
 * @param  string    $index
 * @param  string    $delimiter
 * @access public
 * @return void
 */
function p($index = '', $delimiter = ',')
{
    global $_result;

    /* Print $_result. */
    if($index === '') return print_r($_result) . "\n";

    $keywords  = explode(';', $index);
    foreach($keywords as $keyword)
    {
        $resultList = getValuesByKeyword($_result, $keyword, $delimiter);
        if(!is_array($resultList)) continue;

        foreach($resultList as $result) echo $result . "\n";
    }

    return true;
}

/**
 * Get values by keyword.
 *
 * @param mixed  $result
 * @param string $keyword
 * @param string $delimiter
 * @access public
 * @return int|array
 */
function getValuesByKeyword($result, $keyword, $delimiter)
{
    $index  = -1;
    $pos    = strpos($keyword, ':');
    if($pos)
    {
        $index   = substr($keyword, 0, $pos);
        $keyword = substr($keyword, $pos + 1);
    }

    $keys = explode($delimiter, $keyword);
    if($index != -1)
    {
        if(is_array($result))
        {
            if(!isset($result[$index])) return print("Error: Cannot get index $index.\n");
            $result = $result[$index];
        }
        else if(is_object($result))
        {
            if(!isset($result->$index)) return print("Error: Cannot get index $index.\n");
            $result = $result->$index;
        }
        else
        {
            return print("Error: Not array, cannot get index $index.\n");
        }
    }

    $values = array();
    foreach($keys as $key) $values[] = zget($result, $key, '');

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

class tester extends result
{
    public $webdriver;
    public $page;
    public $config;
    public $cookieFile;
    public $lang;

    /**
     * Initialize the basic configuration of the ui test framework.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $config;
        $this->config = $config;

        $this->webdriver  = new webdriver($config->uitest->chrome);
        $this->page       = new page($this->webdriver);
        $this->cookieFile = '/tmp/cookie';

        // 设置异常处理函数
        set_exception_handler(array($this, 'exceptionHandler'));
    }

    /**
     * Exception handler.
     *
     * @param  int    $e
     * @access public
     * @return void
     */
    public function exceptionHandler($e)
    {
        print("异常信息: \n");
        print($e->getMessage() . "\n");

        if($this->config->uitest->exitBrowserOnException) $this->closeBrowser();
    }

    /**
     * Login to the test URL.
     *
     * @param  string $account
     * @param  string $password
     * @access public
     * @return object
     */
    public function login($account = '', $password = '')
    {
        if(!$account)  $account  = $this->config->uitest->defaultAccount;
        if(!$password) $password = $this->config->uitest->defaultPassword;
        $webRoot = $this->getWebRoot();

        $this->page->openURL($webRoot)->deleteCookie()->refresh();

        if($this->config->uitest->langClient == 'en')
        {
            $this->page->deleteCookie('lang');
            $cookie = array();
            $cookie['lang']  = 'en';
            $this->addCookie($webRoot, $cookie);
            $this->page->refresh()->wait(1);
        }

        $this->checkError();

        $this->page->dom->account->setValue($account);
        $this->page->dom->password->setValue($password);
        $this->page->dom->submit->click();

        $this->page->saveCookie($this->cookieFile);

        sleep(1);
        if(empty($this->lang)) $this->initLang();

        return $this->page;
    }

    /**
     * Add a cookie in test site.
     *
     * @param  string  $webRoot
     * @param  array   $cookie eg:array('lang' = 'zh-cn')
     * @param  bool    $clear
     * @access public
     * @return bool|object
     */
    public function addCookie($webRoot, $cookie, $clear = true)
    {
        if(empty($cookie) || !is_array($cookie)) return false;

        if($clear) $this->page->openURL($webRoot)->deleteCookie();

        /* Set path, domain and secure for cookie. */
        $fullCookie['name']   = key($cookie);
        $fullCookie['value']  = current($cookie);
        $fullCookie['path']   = '/';
        $fullCookie['domain'] = parse_url($webRoot, PHP_URL_HOST);
        $fullCookie['secure'] = false;
        $this->page->addCookie($fullCookie);

        return $this;
    }

    /**
     * Switch a zentao vision.
     *
     * @param  string    $vision
     * @access public
     * @return object
     */
    public function switchVision($vision)
    {
        $currentVision = $this->page->getCookie('vision');
        if($currentVision) $this->page->deleteCookie('vision');

        $this->page->addCookie(array('name' => 'vision', 'value' => $vision));
        $this->page->wait(1)->refresh();

        return $this;
    }

    /**
     * Get cookie value in cookie file.
     *
     * @param  string    $cookieName
     * @access public
     * @return bool|string
     */
    public function getCookieValueFromFile($cookieName)
    {
        if(!file_exists($this->cookieFile)) return false;

        $cookies = json_decode(file_get_contents($this->cookieFile), true);
        if(empty($cookies)) return false;

        foreach($cookies as $cookie) if($cookie['name'] == $cookieName) return $cookie['value'];
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
        $this->module = $module;
        $this->method = $method;
        $webRoot = $this->getWebRoot();

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

        if(!$this->page->getCookie('zentaosid'))
        {
            $zentaosid = $this->getCookieValueFromFile('zentaosid');
            $this->addCookie($webRoot, array('zentaosid' => $zentaosid));
            $this->page->refresh()->wait(1);
        }
        $this->page->openURL($webRoot . $url);

        $appIframeID = $iframeID ? $iframeID : "appIframe-{$module}";
        $this->page->wait(1);
        $this->checkError($appIframeID);

        return $this;
    }

    /**
     * Set up a test page.
     *
     * @param  string  $module
     * @param  string  $method
     * @param  string  $ext
     * @access public
     * @return object
     */
    public function loadPage($module = '', $method = '', $ext = '')
    {
        if($this->module && !$module) $module = $this->module;
        if($this->method && !$method) $method = $this->method;
        $edition = $this->config->edition;

        $method    = strtolower($method);
        $pageClass = "{$method}Page";

        $openPage = dirname(__FILE__, 3). "/module/$module/test/ui/page/$method.php";
        if(!class_exists($pageClass))
        {
            if($edition != 'open')
            {
                if(!$ext) $ext = $edition;
                if(file_exists($openPage))
                {
                    include $openPage;
                }
                else
                {
                    $extDir = is_dir(dirname(__FILE__, 3). "/extension/$ext/$module/ext/test") ? dirname(__FILE__, 3). "/extension/$ext/$module/ext/test" : dirname(__FILE__, 3). "/extension/$ext/$module/test";
                    include "$extDir/ui/page/$method.php";
                }
            }
            else
            {
                include $openPage;
            }
        }

        $methodPage = new $pageClass($this->webdriver);
        $this->pageObject = $methodPage;

        return $methodPage;
    }

    /**
     * Visit a form test page.
     *
     * @param  int    $module
     * @param  int    $method
     * @param  array  $params
     * @param  string $iframeID
     * @param  string $ext
     * @access public
     * @return object
     */
    public function initForm($module, $method, $params = array(), $iframeID = '', $ext = '')
    {
        $this->openURL($module, $method, $params, $iframeID);
        return $this->loadPage($module, $method, $ext);
    }

    /**
     * Screenshoot in page.
     *
     * @param  string $imageFile
     * @access public
     * @return object
     */
    public function screenshot($imageFile = '')
    {
        if(!$imageFile) $imageFile = $this->config->uitest->captureRoot;
        return $this->page->capture($imageFile);
    }

    /**
     * Check errors in page.
     *
     * @access public
     * @return void
     */
    public function checkError($iframeID = '')
    {
        $errors = $this->page->dom->getErrorsInPage($iframeID);
        if(!empty($errors))
        {
            $this->errors = $errors;
            $this->screenshot();
        }

        return $errors;
    }

    /**
     * Get web root in test site.
     *
     * @access public
     * @return string
     */
    public function getWebRoot()
    {
        return rtrim($this->config->uitest->webRoot, '/') . '/';
    }

    /**
     * Init langage file on login test site.
     *
     * @access public
     * @return bool
     */
    public function initLang()
    {
        $webRoot = $this->getWebRoot();
        if(!$webRoot) return false;
        if(!$this->config->uitest->langClient) return false;
        $langClient = $this->config->uitest->langClient;

        $langFile  = '/tmp/lang_' . $langClient . '_' . str_replace('.', '_', parse_url($webRoot, PHP_URL_HOST));
        if(file_exists($langFile))
        {
            $this->lang = json_decode(file_get_contents($langFile));
            return true;
        }

        $zentaosid = $this->getCookieValueFromFile('zentaosid');
        if($zentaosid)
        {
            $url    = $webRoot . "api.php/v1/langs?modules=all&lang={$langClient}&zentaosid={$zentaosid}";
            $header = array('Cookie: device=desktop; lang=' . $langClient . '; theme=default; zentaosid=' . $zentaosid);

            $response = common::http($url, null, array(), $header);
            if(empty($response)) return false;

            $this->lang = json_decode($response);
            file_put_contents($langFile, $response);
            return true;
        }

        return false;
    }

    /**
     * Check fields tip in form page.
     *
     * @param  string  $module
     * @access public
     * @return void
     */
    public function checkFormTips($module)
    {
        $tips = $this->page->dom->getFormTips();
        if(empty($tips)) return false;

        foreach($tips as $field => $tip)
        {
            if(strpos($tip, '|') !== false) list($tip, $fieldValue) = explode('|', $tip);
            $field = trim(preg_replace('/Tip$/', '', $field), '[]');
            if(!isset($this->lang->{$module}->{$field})) return false;

            $field = $this->lang->{$module}->{$field};

            $checkList = array();
            $checkList[] = sprintf($this->lang->error->notempty, $field);
            if(isset($fieldValue))
            {
                $checkList[] = sprintf($this->lang->error->unique, $field, $fieldValue);
                $checkList[] = sprintf($this->lang->error->repeat, $field, $fieldValue);
            }

            if(!in_array($tip, $checkList)) return false;
        }

        return true;
    }

    /**
     * Close the Browser.
     *
     * @access public
     * @return void
     */
    function closeBrowser()
    {
        $this->page->closeBrowser();
    }
}

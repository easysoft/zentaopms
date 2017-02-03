<?php
/**
 * 此文件包括ZenTaoPHP框架的三个类：baseRouter, config, lang。
 * The router, config and lang class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code. In place of 
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * baseRouter类。
 * The baseRouter class.
 *
 * @package framework
 */
class baseRouter
{

    /**
     * ZenTaoPHP的基础目录，一般是程序的根目录。
     * The base path of the ZenTaoPHP framework.
     *
     * @var string
     * @access public
     */
    public $basePath;

    /**
     * 框架的根目录。
     * The root directory of the framwork($this->basePath/framework)
     * 
     * @var string
     * @access public
     */
    public $frameRoot;

    /**
     * 应用类库的根目录($this->appRoot/lib)。
     * The root directory of the app library($this->appRoot/lib).
     * 
     * @var string
     * @access public
     */
    public $coreLibRoot;

    /**
     * 应用程序的根目录。
     * The root directory of the app.
     * 
     * @var string
     * @access public
     */
    public $appRoot;

    /**
     * 临时文件的根目录。
     * The root directory of temp.
     * 
     * @var string
     * @access public
     */
    public $tmpRoot;

    /**
     * 缓存的根目录。
     * The root directory of cache.
     * 
     * @var string
     * @access public
     */
    public $cacheRoot;

    /**
     *WWW目录 
     * The root directory of www.
     * 
     * @var string
     * @access public
     */
    public $wwwRoot;

    /**
     * 附件存放目录
     * The root directory of data.
     * 
     * @var string
     * @access public
     */
    public $dataRoot;

    /**
     * 日志文件的根目录。
     * The root directory of log.
     * 
     * @var string
     * @access public
     */
    public $logRoot;

    /**
     * 配置文件的根目录。
     * The root directory of config.
     * 
     * @var string
     * @access public
     */
    public $configRoot;

    /**
     * 模块的根目录。
     * The root directory of module.
     * 
     * @var string
     * @access public
     */
    public $moduleRoot;

    /**
     * 主题的根目录。
     * The root directory of theme.
     * 
     * @var string
     * @access public
     */
    public $themeRoot;

    /**
     * 用户使用的语言。
     * The lang of the client user.
     * 
     * @var string
     * @access public
     */
    public $clientLang;

    /**
     * 用户使用的主题。
     * The theme of the client user.
     * 
     * @var string
     * @access public
     */
    public $clientTheme;

    /**
     * 当前模块的control对象。
     * The control object of current module.
     * 
     * @var object
     * @access public
     */
    public $control;

    /**
     * 模块名。
     * The module name
     * 
     * @var string
     * @access public
     */
    public $moduleName;

    /**
     * 当前访问模块的control文件。
     * The control file of the module current visiting.
     * 
     * @var string
     * @access public
     */
    public $controlFile;

    /**
     * 当前访问的方法名。
     * The name of the method current visiting.
     * 
     * @var string
     * @access public
     */
    public $methodName;

    /**
     * 当前方法的扩展文件。
     * The action extension file of current method.
     * 
     * @var string
     * @access public
     */
    public $extActionFile;

    /**
     * 访问的URI。
     * The URI.
     * 
     * @var string
     * @access public
     */
    public $URI;

    /**
     * url地址传递的参数。
     * The params passed in through url.
     * 
     * @var array
     * @access public
     */
    public $params;

    /**
     * 视图类型。
     * The view type.
     * 
     * @var string
     * @access public
     */
    public $viewType;

    /**
     * 全局$config对象。
     * The global $config object.
     * 
     * @var object
     * @access public
     */
    public $config;

    /**
     * 全局$lang对象。
     * The global $lang object.
     * 
     * @var object
     * @access public
     */
    public $lang;

    /**
     * 全局$dbh对象，数据库连接句柄。
     * The global $dbh object, the database connection handler.
     * 
     * @var object
     * @access public
     */
    public $dbh;

    /**
     * 从数据库的句柄。
     * The slave database handler.
     * 
     * @var object
     * @access public
     */
    public $slaveDBH;

    /**
     * $post对象，用于访问$_POST变量。
     * The $post object, used to access the $_POST var.
     * 
     * @var ojbect
     * @access public
     */
    public $post;

    /**
     * $get对象，用于访问$_GET变量。
     * The $get object, used to access the $_GET var.
     * 
     * @var ojbect
     * @access public
     */
    public $get;

    /**
     * $session对象，用于访问$_SESSION变量。
     * The $session object, used to access the $_SESSION var.
     * 
     * @var ojbect
     * @access public
     */
    public $session;

    /**
     * $server对象，用于访问$_SERVER变量。
     * The $server object, used to access the $_SERVER var.
     * 
     * @var ojbect
     * @access public
     */
    public $server;

    /**
     * $cookie对象，用于访问$_COOKIE变量。
     * The $cookie object, used to access the $_COOKIE var.
     * 
     * @var ojbect
     * @access public
     */
    public $cookie;

    /**
     * $global对象，用于访问$_GLOBAL变量。
     * The $global object, used to access the $_GLOBAL var.
     * 
     * @var ojbect
     * @access public
     */
    public $global;

    /**
     * 网站代号
     * The code of current site.
     * 
     * @var string
     * @access public
     */
    public $siteCode;

    /**
     * 客户端设备类型
     * The device type of client.
     * 
     * @var string   
     * @access public
     */
    public $clientDevice;

    /**
     * 应用名称
     * The appName.
     * 
     * @var string
     * @access public
     */
    public $appName = '';

    /**
     * 构造方法, 设置路径，类，超级变量等。注意：
     * 1.应该使用createApp()方法实例化router类；
     * 2.如果$appRoot为空，框架会根据$appName计算应用路径。
     *
     * The construct function.
     * Prepare all the paths, classes, super objects and so on.
     * Notice: 
     * 1. You should use the createApp() method to get an instance of the router.
     * 2. If the $appRoot is empty, the framework will compute the appRoot according the $appName
     *
     * @param string $appName   the name of the app 
     * @param string $appRoot   the root path of the app
     * @access public
     * @return void
     */
    public function __construct($appName = 'demo', $appRoot = '')
    {
        $this->setPathFix();
        $this->setBasePath();
        $this->setFrameRoot();
        $this->setCoreLibRoot();
        $this->setAppRoot($appName, $appRoot);
        $this->setTmpRoot();
        $this->setCacheRoot();
        $this->setLogRoot();
        $this->setConfigRoot();
        $this->setModuleRoot();
        $this->setThemeRoot();
        $this->setWwwRoot();
        $this->setDataRoot();

        $this->setSuperVars();
        $this->loadConfig('common');
        $this->filterSuperVars();

        $this->setDebug();
        $this->setErrorHandler();

        $this->connectDB();

        $this->setTimezone();
        $this->setClientLang();
        $this->loadLang('common');
        $this->setClientTheme();

        $this->loadClass('front',  $static = true);
        $this->loadClass('filter', $static = true);
        $this->loadClass('dao',    $static = true);
        $this->loadClass('mobile', $static = true);

        $this->setClientDevice();
    }

    /**
     * 创建一个应用。
     * Create an application.
     * 
     * <code>
     * <?php
     * $demo = router::createApp('demo');
     * ?>
     * or specify the root path of the app. Thus the app and framework can be seperated.
     * <?php
     * $demo = router::createApp('demo', '/home/app/demo');
     * ?>
     * </code>
     * @param string $appName   the name of the app 
     * @param string $appRoot   the root path of the app
     * @param string $className the name of the router class. When extends a child, you should pass in the child router class name.
     * @static
     * @access public
     * @return object   the app object
     */
    public static function createApp($appName = 'demo', $appRoot = '', $className = '')
    {
        if(empty($className)) $className = __CLASS__;
        return new $className($appName, $appRoot);
    }

    //-------------------- 路径相关方法(Path related methods)--------------------//

    /**
     * 设置应用名称
     * Set app name.
     * 
     * @param  string    $appName 
     * @access public
     * @return void
     */
    public function setAppName($appName)
    {
        $this->appName = $appName;
    }

    /**
     * 设置目录分隔符。
     * Set the path directory separator.
     * 
     * @access public
     * @return void
     */
    public function setPathFix()
    {
        define('DS', DIRECTORY_SEPARATOR);
    }

    /**
     * 设置基础目录。
     * Set the base path.
     *
     * @access public
     * @return void
     */
    public function setBasePath()
    {
        $this->basePath = realpath(dirname(dirname(dirname(__FILE__)))) . DS;
    }

    /**
     * 设置框架根目录。
     * Set the frame root.
     * 
     * @access public
     * @return void
     */
    public function setFrameRoot()
    {
        $this->frameRoot = $this->basePath . 'framework' . DS;
    }

    /**
     * 设置应用类库的根目录。
     * Set the app lib root.
     * 
     * @access public
     * @return void
     */
    public function setCoreLibRoot()
    {
        $this->coreLibRoot = $this->basePath . 'lib' . DS;
    }

    /**
     * 设置应用的根目录。
     * Set the app root.
     *
     * @param string $appName 
     * @param string $appRoot 
     * @access public
     * @return void
     */
    public function setAppRoot($appName = 'demo', $appRoot = '')
    {
        if(empty($appRoot))
        {
            $this->appRoot = $this->basePath . 'app' . DS . $appName . DS;
        }
        else
        {
            $this->appRoot = realpath($appRoot) . DS;
        }
        if(!is_dir($this->appRoot)) $this->triggerError("The app you call not found in {$this->appRoot}", __FILE__, __LINE__, $exit = true);
    }

    /**
     * 设置临时文件的根目录。
     * Set the tmp root.
     * 
     * @access public
     * @return void
     */
    public function setTmpRoot()
    {
        $this->tmpRoot = $this->basePath . 'tmp' . DS;
    }

    /**
     * 设置缓存的根目录。
     * Set the cache root.
     * 
     * @access public
     * @return void
     */
    public function setCacheRoot()
    {
        $this->cacheRoot = $this->tmpRoot . 'cache' . DS;
    }

    /**
     * 设置log的根目录。
     * Set the log root.
     * 
     * @access public
     * @return void
     */
    public function setLogRoot()
    {
        $this->logRoot = $this->tmpRoot . 'log' . DS;
    }

    /**
     * 设置config配置文件的根目录。
     * Set the config root.
     * 
     * @access public
     * @return void
     */
    public function setConfigRoot()
    {
        $this->configRoot = $this->basePath . 'config' . DS;
    }

    /**
     * 设置模块的根目录。
     * Set the module root.
     * 
     * @access public
     * @return void
     */
    public function setModuleRoot()
    {
        $this->moduleRoot = $this->basePath . 'module' . DS;
    }

    /**
     * Set the www root.
     * 
     * @access public
     * @return void
     */
    public function setWwwRoot()
    {
        $this->wwwRoot = rtrim(dirname($_SERVER['SCRIPT_FILENAME']), DS) . DS;
    }

    /**
     * Set the data root.
     * 
     * @access public
     * @return void
     */
    public function setDataRoot()
    {
        $this->dataRoot = $this->wwwRoot . 'data' . DS;
    }

    /**
     * 设置主题根目录。
     * Set the theme root.
     * 
     * @access public
     * @return void
     */
    public function setThemeRoot()
    {
        $this->themeRoot = $this->wwwRoot . 'theme' . DS;
    }

    /**
     * 过滤超级变量数据
     * Filter superVars.
     * 
     * @access public
     * @return void
     */
    public function filterSuperVars()
    {
        if(!empty($_COOKIE))
        {
            foreach($_COOKIE as $cookieKey => $cookieValue)
            {
                if(preg_match('/[^a-zA-Z0-9_\.]/', $cookieKey)) unset($_COOKIE[$cookieKey]);
                if(preg_match('/[^a-zA-Z0-9=_\|\- ,`+\/\.%\x7f-\xff]/', $cookieValue)) unset($_COOKIE[$cookieKey]);
            }
        }

        if(!empty($_FILES) and isset($this->config->file->dangers))
        {
            foreach($_FILES as $varName => $files)
            {
                if(is_array($files['name']))
                {
                    foreach($files['name'] as $i => $fileName)
                    {
                        $extension = ltrim(strrchr($fileName, '.'), '.');
                        if(strrpos(",{$this->config->file->dangers},", ",{$extension},") !== false)
                        {
                            unset($_FILES);
                            break;
                        }
                    }
                }
                else
                {
                    $extension = ltrim(strrchr($files['name'], '.'), '.');
                    if(strrpos(",{$this->config->file->dangers},", ",{$extension},") !== false) unset($_FILES);
                }
            }
        }
        $_POST   = processArrayEvils($_POST);
        $_GET    = processArrayEvils($_GET);
        $_COOKIE = processArrayEvils($_COOKIE);
        unset($GLOBALS);
        unset($_REQUEST);
    }

    /**
     * 设置超级变量。
     * Set the super vars.
     * 
     * @access public
     * @return void
     */
    public function setSuperVars()
    {
        $this->post    = new super('post');
        $this->get     = new super('get');
        $this->server  = new super('server');
        $this->cookie  = new super('cookie');
        $this->session = new super('session');
        $this->global  = new super('global');
    }

    /**
     * 设置站点代号
     * Set the code of current site. 
     * 
     * www.xirang.com => xirang
     * xirang.com     => xirang
     * xirang.com.cn  => xirang
     * xirang.cn      => xirang
     * xirang         => xirang
     * 192.168.1.1    => 192.168.1.1
     *
     * @access public
     * @return void
     */
    public function setSiteCode()
    {
        return $this->siteCode = helper::getSiteCode($this->server->http_host);
    }

    /**
     * 设置客户端的设备类型
     * Set client device.
     * 
     * @access public
     * @return void
     */
    public function setClientDevice()
    {
        $this->clientDevice = helper::getClientDevice();
    }

    /**
     * 设置Debug模式。
     * set Debug. 
     * 
     * @access public
     * @return void
     */
    public function setDebug()
    {
        if(!empty($this->config->debug)) error_reporting(E_ALL & ~ E_STRICT);
    }

    /**
     * 设置错误处理句柄。
     * Set the error handler.
     * 
     * @access public
     * @return void
     */
    public function setErrorHandler()
    {
        set_error_handler(array($this, 'saveError'));
        register_shutdown_function(array($this, 'shutdown'));
    }

    /**
     * 根据配置设置当前时区。
     * Set the time zone according to the config.
     * 
     * @access public
     * @return void
     */
    public function setTimezone()
    {
        if(isset($this->config->timezone)) date_default_timezone_set($this->config->timezone);
    }

    /**
     * 获取应用名称
     * Get app name 
     * 
     * @access public
     * @return string
     */
    public function getAppName()
    {
        return $this->appName;
    }

    /**
     * 获取$basePath，即基础路径。
     * Get the $basePath var.
     * 
     * @access public
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * 获取$frameRoot，即框架根目录。
     * Get the $frameRoot var.
     * 
     * @access public
     * @return string
     */
    public function getFrameRoot()
    {
        return $this->frameRoot;
    }

    /**
     * 获取$appRoot变量，即应用的根目录。
     * Get the $appRoot var.
     * 
     * @access public
     * @return string
     */
    public function getAppRoot()
    {
        return $this->appRoot;
    }

    /**
     * 获取$wwwRoot变量。
     * Get the $wwwRoot var
     * 
     * @access public
     * @return string
     */
    public function getWwwRoot()
    {
        return $this->wwwRoot;
    }

    /**
     * 获取$coreLibRoot变量，即应用类库的根目录。
     * Get the $coreLibRoot var.
     * 
     * @access public
     * @return string
     */
    public function getCoreLibRoot()
    {
        return $this->coreLibRoot;
    }

    /**
     * 获取$tmpRoot变量，即临时文件的根目录。
     * Get the $tmpRoot var.
     * 
     * @access public
     * @return string
     */
    public function getTmpRoot()
    {
        return $this->tmpRoot;
    } 

    /**
     * 获取$cacheRoot变量，即缓存文件的根目录。
     * Get the $cacheRoot var.
     * 
     * @access public
     * @return string
     */
    public function getCacheRoot()
    {
        return $this->cacheRoot;
    } 

    /**
     * 获取$logRoot变量，即日志文件的根目录。
     * Get the $logRoot var.
     * 
     * @access public
     * @return string
     */
    public function getLogRoot()
    {
        return $this->logRoot;
    } 

    /**
     * 获取$configRoot变量，即配置文件的根目录。
     * Get the $configRoot var.
     * 
     * @access public
     * @return string
     */
    public function getConfigRoot()
    {
        return $this->configRoot;
    }

    /**
     * 获取$moduleRoot变量，即应用模块的根目录。
     * Get the $moduleRoot var.
     * 
     * @param  string $appName 
     * @access public
     * @return string
     */
    public function getModuleRoot($appName = '')
    {
        if($appName == '') return $this->moduleRoot;
        return dirname($this->moduleRoot) . DS . $appName . DS;
    }

    /**
     * 获取$dataRoot目录
     * Get the $dataRoot var
     * 
     * @access public
     * @return string
     */
    public function getDataRoot()
    {
        return $this->dataRoot;
    }

    /**
     * 获取$themeRoot变量，即主题的根目录。
     * Get the $themeRoot var.
     * 
     * @access public
     * @return string
     */
    public function getThemeRoot()
    {
        return $this->themeRoot;
    }

    //------ 客户端环境有关的函数(Client environment related functions) ------//

    /**
     * 根据用户浏览器的语言设置和服务器配置，选择显示的语言。
     * 优先级：$lang参数 > session > cookie > 浏览器 > 配置文件。
     *
     * Set the language.
     * Using the order of method $lang param, session, cookie, browser and the default lang.
     *
     * @param   string $lang  zh-cn|zh-tw|zh-hk|en
     * @access  public
     * @return  void
     */
    public function setClientLang($lang = '')
    {
        if(!empty($lang))
        {
            $this->clientLang = $lang;
        }
        elseif(isset($_SESSION['lang']))
        {
            $this->clientLang = $_SESSION['lang'];
        }
        elseif(isset($_COOKIE['lang']))
        {
            $this->clientLang = $_COOKIE['lang'];
        }
        elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            if(strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ',') === false)
            {
                $this->clientLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            }
            else
            {
                $this->clientLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ','));
            }

            /* Fix clientLang for ie >= 10. https://www.drupal.org/node/365615. */
            if(stripos($this->clientLang, 'hans')) $this->clientLang = 'zh-cn';
            if(stripos($this->clientLang, 'hant')) $this->clientLang = 'zh-tw';
        }
        if(!empty($this->clientLang))
        {
            $this->clientLang = strtolower($this->clientLang);
            if(!isset($this->config->langs[$this->clientLang])) $this->clientLang = $this->config->default->lang;
        }    
        else
        {
            $this->clientLang = $this->config->default->lang;
        }
        setcookie('lang', $this->clientLang, $this->config->cookieLife, $this->config->webRoot);
        if(!isset($_COOKIE['lang'])) $_COOKIE['lang'] = $this->clientLang;
    }

    /**
     * 获取$clientLang变量，即客户端的语言。
     * Get the $clientLang var.
     * 
     * @access public
     * @return string
     */
    public function getClientLang()
    {
        return $this->clientLang;
    }

    /**
     * 设置客户端使用的主题，判断逻辑与客户端的语言相同。
     * 主题的css和图片文件应该存放在www/theme/$themeName路径。
     *
     * Set the theme the client user using. The logic is same as the clientLang.
     * The css and images files of an theme should saved at www/theme/$themeName
     *
     * @param   string $theme   
     * @access  public
     * @return  void
     */
    public function setClientTheme($theme = '')
    {
        if(!empty($theme))
        {
            $this->clientTheme = $theme;
        }
        elseif(isset($_COOKIE['theme']))
        {
            $this->clientTheme = $_COOKIE['theme'];
        }    
        elseif(isset($this->config->client->theme))
        {
            $this->clientTheme = $this->config->client->theme;
        }    

        if(!empty($this->clientTheme))
        {
            $this->clientTheme = strtolower($this->clientTheme);
            if(!isset($this->lang->themes[$this->clientTheme])) $this->clientTheme = $this->config->default->theme;
        }    
        else
        {
            $this->clientTheme = $this->config->default->theme;
        }
        setcookie('theme', $this->clientTheme, $this->config->cookieLife, $this->config->webRoot);
        if(!isset($_COOKIE['theme'])) $_COOKIE['theme'] = $this->clientTheme;
    }

    /**
     * 获取$clientTheme变量。
     * Get the $clientTheme var. 
     *
     * @access public
     * @return string
     */
    public function getClientTheme()
    {
        return $this->config->webRoot . 'theme/' . $this->clientTheme . '/';
    }

    /**
     * 获取$webRoot，即应用的路径。
     * Get the $webRoot var.
     * 
     * @access public
     * @return string
     */
    public function getWebRoot()
    {
        return $this->config->webRoot;
    }

    //-------------------- 请求相关的方法(Request related methods) --------------------//

    /**
     * 解析本次请求的入口方法，根据请求的类型(PATH_INFO GET)，调用相应的方法。
     * The entrance of parseing request. According to the requestType, call related methods.
     * 
     * @access public
     * @return void
     */
    public function parseRequest()
    {
        if(isGetUrl())
        {
            if($this->config->requestType == 'PATH_INFO2') define('FIX_PATH_INFO2', true);
            $this->config->requestType = 'GET';
        }

        if($this->config->requestType == 'PATH_INFO' or $this->config->requestType == 'PATH_INFO2')
        {
            $this->parsePathInfo();
            $this->setRouteByPathInfo();
        }
        elseif($this->config->requestType == 'GET')
        {
            $this->parseGET();
            $this->setRouteByGET();
        }
        else
        {
            $this->triggerError("The request type {$this->config->requestType} not supported", __FILE__, __LINE__, $exit = true);
        }
    }

    /**
     * PATH_INFO方式解析，获取$URI和$viewType。
     * Parse PATH_INFO, get the $URI and $viewType.
     * 
     * @access public
     * @return void
     */
    public function parsePathInfo()
    {
        $pathInfo = $this->getPathInfo();
        if(trim($pathInfo, '/') == trim($this->config->webRoot, '/')) $pathInfo = '';
        if(!empty($pathInfo))
        {
            $dotPos = strrpos($pathInfo, '.');
            if($dotPos)
            {
                $this->URI      = substr($pathInfo, 0, $dotPos);
                $this->viewType = substr($pathInfo, $dotPos + 1);
                if(strpos($this->config->views, ',' . $this->viewType . ',') === false)
                {
                    $this->viewType = $this->config->default->view;
                }
            }
            else
            {
                $this->URI      = $pathInfo;
                $this->viewType = $this->config->default->view;
            }
        }
        else
        {
            $this->viewType = $this->config->default->view;
        }
    }

    /**
     * 从$_SERVER或者$_ENV全局变量根据pathinfo变量名获取$PATH_INFO值。
     * PATH_INFO的变量名几乎都是'PATH_INFO'，但也有可能是ORIG_PATH_INFO。
     * 
     * Get $PATH_INFO from $_SERVER or $_ENV by the pathinfo var name.
     * Mostly, the var name of PATH_INFO is PATH_INFO, but may be ORIG_PATH_INFO.
     *
     * @access  public
     * @return  string the PATH_INFO
     */
    public function getPathInfo()
    {
        if(isset($_SERVER['PATH_INFO']))
        {
            $value = $_SERVER['PATH_INFO'];
        }
        elseif(isset($_SERVER['ORIG_PATH_INFO']))
        {
            $value = $_SERVER['ORIG_PATH_INFO'];
        }
        else
        {
            $value = @getenv('PATH_INFO');
            if(empty($value)) $value = @getenv('ORIG_PATH_INFO');
            if(strpos($value, $_SERVER['SCRIPT_NAME']) !== false) $value = str_replace($_SERVER['SCRIPT_NAME'], '', $value);
        }

        if(strpos($value, '?') === false) return trim($value, '/');
        $value = parse_url($value);
        return trim($value['path'], '/');
    }

    /**
     * GET请求方式解析，获取$URI和$viewType。
     * Parse GET, get $URI and $viewType.
     *
     * @access public
     * @return void
     */
    public function parseGET()
    {
        if(isset($_GET[$this->config->viewVar]))
        {
            $this->viewType = $_GET[$this->config->viewVar]; 
            if(strpos($this->config->views, ',' . $this->viewType . ',') === false) $this->viewType = $this->config->default->view;
        }
        else
        {
            $this->viewType = $this->config->default->view;
        }
        $this->URI = $_SERVER['REQUEST_URI'];
    }

    /**
     * 获取$URL。
     * Get the $URL.
     * 
     * @param  bool $full  true, the URI contains the webRoot, else only hte URI.
     * @access public
     * @return string
     */
    public function getURI($full = false)
    {
        if($full and $this->config->requestType == 'PATH_INFO')
        {
            if($this->URI) return $this->config->webRoot . $this->URI . '.' . $this->viewType;
            return $this->config->webRoot;
        }
        return $this->URI;
    }

    /**
     * 获取$vewType变量。
     * Get the $viewType var.
     * 
     * @access public
     * @return string
     */
    public function getViewType()
    {
        return $this->viewType;
    }

    //-------------------- 路由相关方法(Routing related methods) --------------------//

    /**
     * 加载common模块。
     *  
     *  common模块比较特别，它会执行几乎每次请求都需要执行的操作，例如：
     *  打开session，检查权限等等。
     *  加载完$lang, $config, $dbh后，需要在入口文件(www/index.php)中手动调用该方法。
     *
     * Load the common module
     *
     *  The common module is a special module, which can be used to do some common things. For examle:
     *  start session, check priviledge and so on.
     *  This method should called manually in the router file(www/index.php) after the $lang, $config, $dbh loaded.
     *
     * @access public
     * @return object|bool  the common control object or false if not exits.
     */
    public function loadCommon()
    {
        $this->setModuleName('common');
        $commonModelFile = helper::setModelFile('common');
        if(file_exists($commonModelFile))
        {
            helper::import($commonModelFile);
            if(class_exists('extcommonModel'))
            {
                return new extcommonModel();
            }
            elseif(class_exists('commonModel'))
            {
                return new commonModel();
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * 设置要被调用的模块名。
     * Set the name of the module to be called.
     * 
     * @param   string $moduleName  the module name
     * @access  public
     * @return  void
     */
    public function setModuleName($moduleName = '')
    {
        $moduleName = strip_tags(urldecode(strtolower($moduleName)));
        if(!preg_match('/^[a-zA-Z0-9]+$/', $moduleName)) $this->triggerError("The modulename '$moduleName' illegal. ", __FILE__, __LINE__, $exit = true);
        $this->moduleName = $moduleName;
    }

    /**
     * 设置要被调用的控制器文件。
     * Set the control file of the module to be called.
     * 
     * @param   bool    $exitIfNone     没有找到该控制器文件的情况：如果该参数为true，则终止程序；如果为false，则打印错误日志
     *                                  If control file not foundde, how to do. True, die the whole app. false, log error.
     * @access  public
     * @return  bool
     */
    public function setControlFile($exitIfNone = true)
    {
        $this->controlFile = $this->moduleRoot . $this->moduleName . DS . 'control.php';
        if(!is_file($this->controlFile))
        {
            $this->triggerError("the control file $this->controlFile not found.", __FILE__, __LINE__, $exitIfNone);
            return false;
        }
        return true;
    }

    /**
     * 设置要被调用的方法名。
     * Set the name of the method calling.
     * 
     * @param string $methodName 
     * @access public
     * @return void
     */
    public function setMethodName($methodName = '')
    {
        $methodName = strip_tags(urldecode(strtolower($methodName)));
        if(!preg_match('/^[a-zA-Z0-9]+$/', $methodName)) $this->triggerError("The methodname '$methodName' illegal. ", __FILE__, __LINE__, $exit = true);
        $this->methodName = $methodName;
    }

    /**
     * 获取一个模块的路径。
     * Get the path of one module.
     * 
     * @param  string $appName    the app name
     * @param  string $moduleName    the module name
     * @access public
     * @return string the module path
     */
    public function getModulePath($appName = '', $moduleName = '')
    {
        if($moduleName == '') $moduleName = $this->moduleName;
        if(!preg_match('/^[a-zA-Z0-9]+$/', $moduleName)) $this->triggerError("The modulename '$moduleName' illegal. ", __FILE__, __LINE__, $exit = true);
        $modulePath = $this->getModuleRoot($appName) . strtolower(trim($moduleName)) . DS;

        return $modulePath;
    }

    /**
     * 获取一个模块的扩展路径。
     * Get extension path of one module.
     * 
     * @param   string $appName        the app name
     * @param   string $moduleName     the module name
     * @param   string $ext            the extension type, can be control|model|view|lang|config
     * @access  public
     * @return  string the extension path.
     */
    public function getModuleExtPath($appName, $moduleName, $ext)
    {
        if(!preg_match('/^[a-zA-Z0-9]+$/', $moduleName) or !preg_match('/^[a-zA-Z0-9]+$/', $ext)) $this->triggerError("The modulename '$moduleName' or ext '$ext' illegal. ", __FILE__, __LINE__, $exit = true);
        $paths = array();
        $paths['common'] = $this->getModulePath($appName, $moduleName) . 'ext' . DS . $ext . DS;
        $paths['site']   = empty($this->siteCode) ? '' : $this->getModulePath($appName, $moduleName) . 'ext' . DS . '_' . $this->siteCode . DS . $ext . DS;
        return $paths;
    }

    /**
     * 设置请求方法的扩展文件。
     * Set the action extension file.
     * 
     * @access  public
     * @return  bool
     */
    public function setActionExtFile()
    {
        $moduleExtPaths = $this->getModuleExtPath('', $this->moduleName, 'control');

        $this->extActionFile = '';
        if($moduleExtPaths['site']) $this->extActionFile = $moduleExtPaths['site'] . $this->methodName . '.php';
        if(empty($this->extActionFile) or !file_exists($this->extActionFile)) $this->extActionFile = $moduleExtPaths['common'] . $this->methodName . '.php';

        return file_exists($this->extActionFile);
    }

    /**
     * 设置路由(PATH_INFO 方式)：
     * 1.设置模块名；
     * 2.设置方法名；
     * 3.设置控制器文件。
     *
     * Set the route according to PATH_INFO.
     * 1. set the module name.
     * 2. set the method name.
     * 3. set the control file.
     *
     * @access public
     * @return void
     */
    public function setRouteByPathInfo()
    {
        if(!empty($this->URI))
        {
            /*
             * 根据$requestFix分割符，分割网址。
             * There's the request seperator, split the URI by it.
             **/
            if(strpos($this->URI, $this->config->requestFix) !== false)
            {
                $items = explode($this->config->requestFix, $this->URI);
                $this->setModuleName($items[0]);
                $this->setMethodName($items[1]);
            }    
            /*
             * 如果网址中没有分隔符，使用默认的方法。
             * No reqeust seperator, use the default method name.
             **/
            else
            {
                $this->setModuleName($this->URI);
                $this->setMethodName($this->config->default->method);
            }
        }
        else
        {    
            $this->setModuleName($this->config->default->module);   // 使用默认模块 use the default module.
            $this->setMethodName($this->config->default->method);   // 使用默认方法 use the default method.
        }
        $this->setControlFile();
    }

    /**
     * 设置路由(GET 方式)：
     * 1.设置模块名；
     * 2.设置方法名；
     * 3.设置控制器文件。
     *
     * Set the route according to GET.
     * 1. set the module name.
     * 2. set the method name.
     * 3. set the control file.
     *
     * @access public
     * @return void
     */
    public function setRouteByGET()
    {
        $moduleName = isset($_GET[$this->config->moduleVar]) ? strtolower($_GET[$this->config->moduleVar]) : $this->config->default->module;
        $methodName = isset($_GET[$this->config->methodVar]) ? strtolower($_GET[$this->config->methodVar]) : $this->config->default->method;
        $this->setModuleName($moduleName);
        $this->setControlFile();
        $this->setMethodName($methodName);
    }

    /**
     * 加载一个模块：
     * 1. 引入控制器文件或扩展的方法文件；
     * 2. 创建control对象；
     * 3. 解析url，得到请求的参数；
     * 4. 使用call_user_function_array调用相应的方法。
     *
     * Load a module.
     * 1. include the control file or the extension action file.
     * 2. create the control object.
     * 3. set the params passed in through url.
     * 4. call the method by call_user_function_array
     * 
     * @access public
     * @return bool|object  if the module object of die.
     */
    public function loadModule()
    {
        $moduleName = $this->moduleName;
        $methodName = $this->methodName;

        /* 
         * 引入该模块的control文件。
         * Include the control file of the module.
         **/
        $file2Included = $this->setActionExtFile() ? $this->extActionFile : $this->controlFile;
        chdir(dirname($file2Included));
        include $file2Included;

        /*
         * 设置control的类名。
         * Set the class name of the control.
         **/
        $className = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
        if(!class_exists($className)) $this->triggerError("the control $className not found", __FILE__, __LINE__, $exit = true);

        /*
         * 创建control类的实例。
         * Create a instance of the control.
         **/
        $module = new $className();
        if(!method_exists($module, $methodName)) $this->triggerError("the module $moduleName has no $methodName method", __FILE__, __LINE__, $exit = true);
        $this->control = $module;

        /* include default value for module*/
        $defaultValueFiles = glob($this->getTmpRoot() . "defaultvalue/*.php");
        if($defaultValueFiles) foreach($defaultValueFiles as $file) include $file;

        /* 
         * 使用反射机制获取函数参数的默认值。
         * Get the default settings of the method to be called using the reflecting. 
         *
         * */
        $defaultParams = array();
        $methodReflect = new reflectionMethod($className, $methodName);
        foreach($methodReflect->getParameters() as $param)
        {
            $name = $param->getName();

            $default = '_NOT_SET';
            if(isset($paramDefaultValue[$className][$methodName][$name]))
            {
                $default = $paramDefaultValue[$className][$methodName][$name];
            }
            elseif($param->isDefaultValueAvailable())
            {
                $default = $param->getDefaultValue();
            }

            $defaultParams[$name] = $default;
        }

        /** 
         * 根据PATH_INFO或者GET方式设置请求的参数。
         * Set params according PATH_INFO or GET.
         */
        if($this->config->requestType != 'GET')
        {
            $this->setParamsByPathInfo($defaultParams);
        }
        else
        {
            $this->setParamsByGET($defaultParams);
        }

        /* 调用该方法   Call the method. */
        call_user_func_array(array($module, $methodName), $this->params);
        return $module;
    }

    /**
     * 设置请求的参数(PATH_INFO 方式)。
     * Set the params by PATH_INFO.
     * 
     * @param   array $defaultParams the default settings of the params.
     * @access  public
     * @return  void
     */
    public function setParamsByPathInfo($defaultParams = array())
    {
        /* 分割URI。 Spit the URI. */
        $items     = explode($this->config->requestFix, $this->URI);
        $itemCount = count($items);
        $params    = array();

        /** 
         * 前两项为模块名和方法名，参数从下标2开始。
         * The first two item is moduleName and methodName. So the params should begin at 2.
         **/
        for($i = 2; $i < $itemCount; $i ++)
        {
            $key = key($defaultParams);     // Get key from the $defaultParams.
            $params[$key] = $items[$i];
            next($defaultParams);
        }

        $this->params = $this->mergeParams($defaultParams, $params);
    }

    /**
     * 设置请求的参数(GET 方式)。
     * Set the params by GET.
     * 
     * @param   array $defaultParams the default settings of the params.
     * @access  public
     * @return  void
     */
    public function setParamsByGET($defaultParams)
    {
        /* Unset moduleVar, methodVar, viewVar and session 变量， 剩下的作为参数。 */
        /* Unset the moduleVar, methodVar, viewVar and session var, all the left are the params. */
        unset($_GET[$this->config->moduleVar]);
        unset($_GET[$this->config->methodVar]);
        unset($_GET[$this->config->viewVar]);
        unset($_GET[$this->config->sessionVar]);
        $this->params = $this->mergeParams($defaultParams, $_GET);
    }

    /**
     * 合并请求的参数和默认参数，这样就可以省略已经有默认值的参数了。
     * Merge the params passed in and the default params. Thus the params which have default values needn't pass value, just like a function.
     *
     * @param   array $defaultParams     the default params defined by the method.
     * @param   array $passedParams      the params passed in through url.
     * @access  public
     * @return  array the merged params.
     */
    public function mergeParams($defaultParams, $passedParams)
    {
        /* Check params from URL. */
        foreach($passedParams as $param => $value)
        {
            if(preg_match('/[^a-zA-Z0-9_\.]/', $param)) die('Bad Request!');
            if(preg_match('/[^a-zA-Z0-9=_,`#+\^\/\.%\|\x7f-\xff]/', trim($value))) die('Bad Request!');
        }

        unset($passedParams['onlybody']);
        unset($passedParams['HTTP_X_REQUESTED_WITH']);
        $passedParams = array_values($passedParams);
        $i = 0;
        foreach($defaultParams as $key => $defaultValue)
        {
            if(isset($passedParams[$i]))
            {
                $defaultParams[$key] = strip_tags(urldecode($passedParams[$i]));
            }
            else
            {
                if($defaultValue === '_NOT_SET') $this->triggerError("The param '$key' should pass value. ", __FILE__, __LINE__, $exit = true);
            }
            $i ++;
        }

        return $defaultParams;
    }

    /**
     * 获取$moduleName变量。
     * Get the $moduleName var.
     * 
     * @access public
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * 获取$controlFile变量。
     * Get the $controlFile var.
     * 
     * @access public
     * @return string
     */
    public function getControlFile()
    {
        return $this->controlFile;
    }

    /**
     * 获取$methodName变量。
     * Get the $methodName var.
     * 
     * @access public
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * 获取$param变量。
     * Get the $param var.
     * 
     * @access public
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    //-------------------- 常用的工具方法(Tool methods) ------------------//

    /**
     * 从类库中加载一个类文件。
     *
     * Load a class file.
     *
     * @param   string $className  the class name
     * @param   bool   $static     statis class or not
     * @access  public
     * @return  object|bool the instance of the class or just true.
     */
    public function loadClass($className, $static = false)
    {
        $className = strtolower($className);

        /* 搜索$coreLibRoot(Search in $coreLibRoot) */
        $classFile = $this->coreLibRoot . $className;
        if(is_dir($classFile)) $classFile .= DS . $className;
        $classFile .= '.class.php';
        if(!helper::import($classFile)) $this->triggerError("class file $classFile not found", __FILE__, __LINE__, $exit = true);

        /* 如果是静态调用，则返回(If staitc, return) */
        if($static) return true;

        /* 实例化该类(Instance it) */
        global $$className;
        if(!class_exists($className)) $this->triggerError("the class $className not found in $classFile", __FILE__, __LINE__, $exit = true);
        if(!is_object($$className)) $$className = new $className();
        return $$className;
    }

    /**
     * 加载模块的config文件，返回全局$config对象。
     * 如果该模块是common，加载$configRoot的配置文件，其他模块则加载其模块的配置文件。
     *
     * Load config and return it as the global config object.
     * If the module is common, search in $configRoot, else in $modulePath.
     *
     * @param   string $moduleName     module name
     * @param   string $appName        app name
     * @param   bool   $exitIfNone     exit or not
     * @access  public
     * @return  object|bool the config object or false.
     */
    public function loadConfig($moduleName, $appName = '', $exitIfNone = true)
    {
        global $config;
        if(!is_object($config)) $config = new config();
        if(!isset($config->$moduleName)) $config->$moduleName = new stdclass();

        $extConfigFiles = array();

        /*
         * 设置主配置文件和扩展配置文件。
         * Set the main config file and extension config file.
         * */
        if($moduleName == 'common')
        {
            $mainConfigFile = $this->configRoot . 'config.php';
            $myConfig       = $this->configRoot . 'my.php';
            if(is_file($myConfig)) $extConfigFiles[] = $myConfig;
        }
        else
        {
            $mainConfigFile = $this->getModulePath($appName, $moduleName) . 'config.php';

            /* Get config extension. */
            $extConfigPath        = $this->getModuleExtPath($appName, $moduleName, 'config');
            $commonExtConfigFiles = helper::ls($extConfigPath['common'], '.php');
            $siteExtConfigFiles   = helper::ls($extConfigPath['site'], '.php');
            $extConfigFiles       = array_merge($commonExtConfigFiles, $siteExtConfigFiles);
        }

        /* 设置引用的文件(Set the files to include) */
        if(!is_file($mainConfigFile))
        {
            if($exitIfNone) self::triggerError("config file $mainConfigFile not found", __FILE__, __LINE__, true);
            if(empty($extConfigFiles) and !isset($config->system->$moduleName) and !isset($config->personal->$moduleName)) return false;  //  and no extension file or extension in db, exit.
            $configFiles = $extConfigFiles;
        }
        else
        {
            $configFiles = array_merge(array($mainConfigFile), $extConfigFiles);
        }

        static $loadedConfigs = array();
        foreach($configFiles as $configFile)
        {
            if(in_array($configFile, $loadedConfigs)) continue;
            include $configFile;
            $loadedConfigs[] = $configFile;
        }

        if($moduleName == 'common')
        {
            $this->config = $config;
            $this->setSiteCode();
            if(!isset($config->site)) $config->site = new stdclass();
            $config->site->code = $this->siteCode;

            if(!empty($config->multi))
            {
                $multiConfigFile = $this->configRoot . "multi.php";
                if(is_file($multiConfigFile)) include $multiConfigFile;
            }

            if(!empty($this->siteCode))
            {
                $siteConfigFile = $this->configRoot . "sites/{$this->siteCode}.php";
                if(is_file($siteConfigFile)) include $siteConfigFile;
            }
        }

        /* Merge from the db configs. */
        if($moduleName != 'common' and isset($config->system->$moduleName)) helper::mergeConfig($config->system->$moduleName, $moduleName);
        if($moduleName != 'common' and isset($config->personal->$moduleName)) helper::mergeConfig($config->personal->$moduleName, $moduleName);

        $this->config = $config;

        return $config;
    }

    /**
     * 向客户端输出配置参数，客户端可以根据这些参数实现和调整请求的逻辑。
     * Export the config params to the client, thus the client can adjust it's logic according the config.
     * 
     * @access public
     * @return void
     */
    public function exportConfig()
    {
        $view = new stdclass();
        $view->version     = $this->config->version;
        $view->requestType = $this->config->requestType;
        $view->requestFix  = $this->config->requestFix;
        $view->moduleVar   = $this->config->moduleVar;
        $view->methodVar   = $this->config->methodVar;
        $view->viewVar     = $this->config->viewVar;
        $view->sessionVar  = $this->config->sessionVar;

        $this->session->set('rand', mt_rand(0, 10000));
        $view->sessionName = session_name();
        $view->sessionID   = session_id();
        $view->rand        = $this->session->rand;
        $view->expiredTime = ini_get('session.gc_maxlifetime');
        $view->serverTime  = time();

        $view->ip          = gethostbyname($_SERVER['HTTP_HOST']);
        $view->name        = isset($this->config->socket->name) ? $this->config->socket->name : '';
        $view->port        = isset($this->config->socket->port) ? $this->config->socket->port : '';
        echo json_encode($view);
    }

    /**
     * 加载语言文件，返回全局$lang对象。
     * Load lang and return it as the global lang object.
     * 
     * @param   string $moduleName     the module name
     * @param   string $appName     the app name
     * @access  public
     * @return  bool|ojbect the lang object or false.
     */
    public function loadLang($moduleName, $appName = '')
    {
        $modulePath   = $this->getModulePath($appName, $moduleName);
        $mainLangFile = $modulePath . 'lang' . DS . $this->clientLang . '.php';
        $extLangPath        = $this->getModuleExtPath($appName, $moduleName, 'lang');
        $commonExtLangFiles = helper::ls($extLangPath['common'] . $this->clientLang, '.php');
        $siteExtLangFiles   = helper::ls($extLangPath['site'] . $this->clientLang, '.php');
        $extLangFiles       = array_merge($commonExtLangFiles, $siteExtLangFiles);

        /* 设置引用的文件(Set the files to include). */
        if(!is_file($mainLangFile))
        {
            if(empty($extLangFiles)) return false;  // 没有扩展文件，返回false(Return false if no extension file).
            $langFiles = $extLangFiles;
        }
        else
        {
            $langFiles = array_merge(array($mainLangFile), $extLangFiles);
        }

        global $lang;
        if(!is_object($lang)) $lang = new language();

        static $loadedLangs = array();
        foreach($langFiles as $langFile)
        {
            if(in_array($langFile, $loadedLangs)) continue;
            include $langFile;
            $loadedLangs[] = $langFile;
        }

        /* Merge from the db lang. */
        if($moduleName != 'common' and isset($lang->db->custom[$moduleName]))
        {
            foreach($lang->db->custom[$moduleName] as $section => $fields)
            {
                if(isset($lang->{$moduleName}->{$section}['']))
                {
                    $nullKey   = '';
                    $nullValue = $lang->{$moduleName}->{$section}[$nullKey]; 
                }
                elseif(isset($lang->{$moduleName}->{$section}[0]))
                {
                    $nullKey   = 0;
                    $nullValue = $lang->{$moduleName}->{$section}[0]; 
                }
                unset($lang->{$moduleName}->{$section});

                if(isset($nullKey))$lang->{$moduleName}->{$section}[$nullKey] = $nullValue;
                foreach($fields as $key => $value) $lang->{$moduleName}->{$section}[$key] = $value;
                unset($nullKey);
                unset($nullValue);
            }
        }

        $this->lang = $lang;
        return $lang;
    }

    /**
     * 连接数据库。
     * Connect to database.
     * 
     * @access public
     * @return void
     */
    public function connectDB()
    {
        global $config, $dbh, $slaveDBH;
        if(!isset($config->installed) or !$config->installed) return;

        if(isset($config->db->host))      $this->dbh      = $dbh      = $this->connectByPDO($config->db);
        if(isset($config->slaveDB->host)) $this->slaveDBH = $slaveDBH = $this->connectByPDO($config->slaveDB);
    }

    /**
     * 使用PDO连接数据库。
     * Connect database by PDO.
     * 
     * @param  object    $params    the database params.
     * @access public
     * @return object|bool
     */
    public function connectByPDO($params)
    {
        if(!isset($params->driver)) self::triggerError('no pdo driver defined, it should be mysql or sqlite', __FILE__, __LINE__, $exit = true);
        if(!isset($params->user)) return false;
        if($params->driver == 'mysql')
        {
            $dsn = "mysql:host={$params->host}; port={$params->port}; dbname={$params->name}";
        }    
        try 
        {
            $dbh = new PDO($dsn, $params->user, $params->password, array(PDO::ATTR_PERSISTENT => $params->persistant));
            $dbh->exec("SET NAMES {$params->encoding}");

            /*
             * 如果系统是Linux，开启仿真预处理和缓冲查询。
             * If run on linux, set emulatePrepare and bufferQuery to true.
             **/
            if(!isset($params->emulatePrepare) and PHP_OS == 'Linux') $params->emulatePrepare = true;
            if(!isset($params->bufferQuery) and PHP_OS == 'Linux')    $params->bufferQuery = true;

            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if(isset($params->strictMode) and $params->strictMode == false) $dbh->exec("SET @@sql_mode= ''");
            if(isset($params->emulatePrepare)) $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, $params->emulatePrepare);
            if(isset($params->bufferQuery))    $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, $params->bufferQuery);

            return $dbh;
        }
        catch (PDOException $exception)
        {
            self::triggerError($exception->getMessage(), __FILE__, __LINE__, $exit = true);
        }
    }

    //-------------------- 错误处理方法(Error methods) ------------------//

    /**
     * 程序停止时执行的函数。
     * The shutdown handler.
     * 
     * @access public
     * @return void
     */
    public function shutdown()
    {
        /* 如果debug模式开启，保存sql语句(If debug on, save sql queries) */
        if(!empty($this->config->debug)) $this->saveSQL();

        /* 
         * 发现错误，保存到日志中。
         * If any error occers, save it.
         * */
        if(!function_exists('error_get_last')) return;
        $error = error_get_last();
        if($error) $this->saveError($error['type'], $error['message'], $error['file'], $error['line']);
    }

    /**
     * 触发一个错误。
     * Trigger an error.
     * 
     * @param string    $message    错误信息      error message
     * @param string    $file       所在文件      the file error occers
     * @param int       $line       错误行        the line error occers
     * @param bool      $exit       是否停止程序  exit the program or not
     * @access public
     * @return void
     */
    public function triggerError($message, $file, $line, $exit = false)
    {
        /* 设置错误信息(Set the error info) */
        $log = "ERROR: $message in $file on line $line";
        if(isset($_SERVER['SCRIPT_URI'])) $log .= ", request: $_SERVER[SCRIPT_URI]";; 
        $trace = debug_backtrace();
        extract($trace[0]);
        extract($trace[1]);
        $log .= ", last called by $file on line $line through function $function.\n";

        /* 触发错误(Trigger the error) */
        trigger_error($log, $exit ? E_USER_ERROR : E_USER_WARNING);
    }

    /**
     * 保存错误信息。
     * Save error info.
     * 
     * @param  int    $level 
     * @param  string $message 
     * @param  string $file 
     * @param  int    $line 
     * @access public
     * @return void
     */
    public function saveError($level, $message, $file, $line)
    {
        if(empty($this->config->debug)) return true;

        /*
         * 删除设定时间之前的日志。
         * Delete the log before the set time.
         **/
        if(mt_rand(0, 1) == 1)
        {
            $logDays = isset($this->config->framework->logDays) ? $this->config->framework->logDays : 14;
            $dayTime = time() - $logDays * 24 * 3600;
            foreach(glob($this->getLogRoot() . '*') as $logFile)
            {
                if(filemtime($logFile) <= $dayTime) unlink($logFile);
            }
        }

        /* 
         * 忽略该错误：Redefining already defined constructor。
         * Skip the error: Redefining already defined constructor.
         **/
        if(strpos($message, 'Redefining') !== false) return true;

        /* 
         * 设置错误信息。
         * Set the error info.
         **/
        $errorLog  = "\n" . date('H:i:s') . " $message in <strong>$file</strong> on line <strong>$line</strong> ";
        $errorLog .= "when visiting <strong>" . $this->getURI() . "</strong>\n";

        /* 
         * 为了安全起见，对公网环境隐藏脚本路径。
         * If the ip is pulic, hidden the full path of scripts.
         */
        if(!defined('IN_SHELL') and !($this->server->server_addr == '127.0.0.1' or filter_var($this->server->server_addr, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false))
        {
            $errorLog  = str_replace($this->getBasePath(), '', $errorLog);
        }

        /* 保存到日志文件(Save to log file) */
        $errorFile = $this->getLogRoot() . 'php.' . date('Ymd') . '.log.php';
        if(!is_file($errorFile)) file_put_contents($errorFile, "<?php\n die();\n?>\n");

        $fh = fopen($errorFile, 'a');
        if($fh) fwrite($fh, strip_tags($errorLog)) and fclose($fh);

        /* 
         * 如果debug > 1，显示warning, notice级别的错误。
         * If the debug > 1, show warning, notice error.
         **/
        if($level == E_NOTICE or $level == E_WARNING or $level == E_STRICT or $level == 8192) // 8192: E_DEPRECATED
        {
            if(!empty($this->config->debug) and $this->config->debug > 1)
            {
                $cmd  = "vim +$line $file";
                $size = strlen($cmd);
                echo "<pre class='alert alert-danger'>$message: ";
                echo "<input type='text' value='$cmd' size='$size' style='border:none; background:none;' onclick='this.select();' /></pre>";
            }
        }

        /* 
         * 如果是严重错误，停止程序。
         * If error level is serious, die.
         * */
        if($level == E_ERROR or $level == E_PARSE or $level == E_CORE_ERROR or $level == E_COMPILE_ERROR or $level == E_USER_ERROR)
        {
            if(empty($this->config->debug)) die();
            if(PHP_SAPI == 'cli') die($errorLog);

            $htmlError  = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head>";
            $htmlError .= "<body>" . nl2br($errorLog) . "</body></html>";
            die($htmlError);
        }
    }

    /**
     * 保存sql语句。
     * Save the sql.
     * 
     * @access public
     * @return void
     */
    public function saveSQL()
    {
        if(!$this->config->debug) return true;
        if(!class_exists('dao')) return;

        $sqlLog = $this->getLogRoot() . 'sql.' . date('Ymd') . '.log.php';
        if(!is_file($sqlLog)) file_put_contents($sqlLog, "<?php\n die();\n?>\n");

        $fh = @fopen($sqlLog, 'a');
        if(!$fh) return false;
        fwrite($fh, date('Ymd H:i:s') . ": " . $this->getURI() . "\n");
        foreach(dao::$querys as $query) fwrite($fh, "  $query\n");
        fwrite($fh, "\n");
        fclose($fh);
    }
}

/**
 * config类。
 * The config class.
 * 
 * @package framework
 */
class config
{ 
    /**
     * 设置成员变量，成员可以是'db.user'类似的格式。
     * Set the value of a member. the member can be the format like db.user.
     * 
     * <code>
     * <?php
     * $config->set('db.user', 'wwccss'); 
     * ?>
     * </code>
     * @param   string  $key    the key of the member
     * @param   mixed   $value  the value
     * @access  public
     * @return  void
     */
    public function set($key, $value)
    {
        helper::setMember('config', $key, $value);
    }
}

/**
 * lang类。
 * The lang class.
 * 
 * @package framework
 */
class language 
{
    /**
     * 设置成员变量，成员可以是'db.user'类似的格式。
     * Set the value of a member. the member can be the foramt like db.user.
     * 
     * <code>
     * <?php
     * $lang->set('version', '1.0); 
     * ?>
     * </code>
     * @param   string  $key    成员的键名，可以是father.child的形式。
     *                          the key of the member, can be father.child 
     * @param   mixed   $value  the value
     * @access  public
     * @return  void
     */
    public function set($key, $value)
    {
        helper::setMember('lang', $key, $value);
    }

    /**
     * 显示一个成员的值。
     * Show a member. 
     * 
     * @param   object $obj    the object
     * @param   string $key    the key
     * @access  public
     * @return  void
     */
    public function show($obj, $key)
    {
        $obj = (array)$obj;
        echo isset($obj[$key]) ? $obj[$key] : '';
    }
}

/**
 * 超级对象类，转化超级全局变量。
 * The super object class.
 * 
 * @package framework
 */
class super
{
    /**
     * 构造函数，设置超级变量名。
     * Construct, set the var scope.
     * 
     * @param   string $scope  the scope, can be server, post, get, cookie, session, global
     * @access  public
     * @return  void
     */
    public function __construct($scope)
    {
        $this->scope = $scope;
    }

    /**
     * 设置超级变量的成员值。
     * Set one member value. 
     * 
     * @param   string    the key
     * @param   mixed $value  the value
     * @access  public
     * @return  void
     */
    public function set($key, $value)
    {
        if($this->scope == 'post')
        {
            $_POST[$key] = $value;
        }
        elseif($this->scope == 'get')
        {
            $_GET[$key] = $value;
        }
        elseif($this->scope == 'server')
        {
            $_SERVER[$key] = $value;
        }
        elseif($this->scope == 'cookie')
        {
            $_COOKIE[$key] = $value;
        }
        elseif($this->scope == 'session')
        {
            $_SESSION[$key] = $value;
        }
        elseif($this->scope == 'env')
        {
            $_ENV[$key] = $value;
        }
        elseif($this->scope == 'global')
        {
            $GLOBALS[$key] = $value;
        }
    }

    /**
     * 超级变量的魔术方法，比如用$post->key访问$_POST['key']。
     * The magic get method.
     * 
     * @param  string $key    the key
     * @access public
     * @return mixed|bool     return the value of the key or false.
     */
    public function __get($key)
    {
        if($this->scope == 'post')
        {
            if(isset($_POST[$key])) return $_POST[$key];
            return false;
        }
        elseif($this->scope == 'get')
        {
            if(isset($_GET[$key])) return $_GET[$key];
            return false;
        }
        elseif($this->scope == 'server')
        {
            if($key == 'ajax') return isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? true : false;
            if(isset($_SERVER[$key])) return $_SERVER[$key];
            $key = strtoupper($key);
            if(isset($_SERVER[$key])) return $_SERVER[$key];
            return false;
        }
        elseif($this->scope == 'cookie')
        {
            if(isset($_COOKIE[$key])) return $_COOKIE[$key];
            return false;
        }
        elseif($this->scope == 'session')
        {
            if(isset($_SESSION[$key])) return $_SESSION[$key];
            return false;
        }
        elseif($this->scope == 'env')
        {
            if(isset($_ENV[$key])) return $_ENV[$key];
            return false;
        }
        elseif($this->scope == 'global')
        {
            if(isset($GLOBALS[$key])) return $GLOBALS[$key];
            return false;
        }
        else
        {
            return false;
        }
    }

    /**
     * 打印变量的详细结构。
     * Print the structure.
     * 
     * @access public
     * @return void
     */
    public function a()
    {
        if($this->scope == 'post')    a($_POST);
        if($this->scope == 'get')     a($_GET);
        if($this->scope == 'server')  a($_SERVER);
        if($this->scope == 'cookie')  a($_COOKIE);
        if($this->scope == 'session') a($_SESSION);
        if($this->scope == 'env')     a($_ENV);
        if($this->scope == 'global')  a($GLOBALS);
    }
}

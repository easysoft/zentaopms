<?php
/**
 * The router, config and lang class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * The router class.
 * 
 * @package framework
 */
class router
{
    /**
     * The directory seperator.
     * 
     * @var string
     * @access private
     */
    private $pathFix;

    /**
     * The base path of the ZenTaoPMS framework.
     *
     * @var string
     * @access private
     */
    private $basePath;

    /**
     * The root directory of the framwork($this->basePath/framework)
     * 
     * @var string
     * @access private
     */
    private $frameRoot;

    /**
     * The root directory of the core library($this->basePath/lib)
     * 
     * @var string
     * @access private
     */
    private $coreLibRoot;

    /**
     * The root directory of the app.
     * 
     * @var string
     * @access private
     */
    private $appRoot;

    /**
     * The root directory of the app library($this->appRoot/lib).
     * 
     * @var string
     * @access private
     */
    private $appLibRoot;

    /**
     * The root directory of temp.
     * 
     * @var string
     * @access private
     */
    private $tmpRoot;

    /**
     * The root directory of cache.
     * 
     * @var string
     * @access private
     */
    private $cacheRoot;

    /**
     * The root directory of log.
     * 
     * @var string
     * @access private
     */
    private $logRoot;

    /**
     * The root directory of config.
     * 
     * @var string
     * @access private
     */
    private $configRoot;

    /**
     * The root directory of module.
     * 
     * @var string
     * @access private
     */
    private $moduleRoot;

    /**
     * The root directory of theme.
     * 
     * @var string
     * @access private
     */
    private $themeRoot;

    /**
     * The lang of the client user.
     * 
     * @var string
     * @access private
     */
    private $clientLang;

    /**
     * The theme of the client user.
     * 
     * @var string
     * @access private
     */
    private $clientTheme;

    /**
     * The control object of current module.
     * 
     * @var object
     * @access public
     */
    public $control;

    /**
     * The module name
     * 
     * @var string
     * @access private
     */
    private $moduleName;

    /**
     * The control file of the module current visiting.
     * 
     * @var string
     * @access private
     */
    private $controlFile;

    /**
     * The name of the method current visiting.
     * 
     * @var string
     * @access private
     */
    private $methodName;

    /**
     * The action extension file of current method.
     * 
     * @var string
     * @access private
     */
    private $extActionFile;

    /**
     * The URI.
     * 
     * @var string
     * @access private
     */
    private $URI;

    /**
     * The params passed in through url.
     * 
     * @var array
     * @access private
     */
    private $params;

    /**
     * The view type.
     * 
     * @var string
     * @access private
     */
    private $viewType;

    /**
     * The global $config object.
     * 
     * @var object
     * @access public
     */
    public $config;

    /**
     * The global $lang object.
     * 
     * @var object
     * @access public
     */
    public $lang;

    /**
     * The global $dbh object, the database connection handler.
     * 
     * @var object
     * @access private
     */
    public $dbh;

    /**
     * The slave database handler.
     * 
     * @var object
     * @access private
     */
    public $slaveDBH;

    /**
     * The $post object, used to access the $_POST var.
     * 
     * @var ojbect
     * @access public
     */
    public $post;

    /**
     * The $get object, used to access the $_GET var.
     * 
     * @var ojbect
     * @access public
     */
    public $get;

    /**
     * The $session object, used to access the $_SESSION var.
     * 
     * @var ojbect
     * @access public
     */
    public $session;

    /**
     * The $server object, used to access the $_SERVER var.
     * 
     * @var ojbect
     * @access public
     */
    public $server;

    /**
     * The $cookie object, used to access the $_COOKIE var.
     * 
     * @var ojbect
     * @access public
     */
    public $cookie;

    /**
     * The $global object, used to access the $_GLOBAL var.
     * 
     * @var ojbect
     * @access public
     */
    public $global;

    /**
     * The construct function.
     * 
     * Prepare all the paths, classes, super objects and so on.
     * Notice: 
     * 1. You should use the createApp() method to get an instance of the router.
     * 2. If the $appRoot is empty, the framework will comput the appRoot according the $appName
     *
     * @param string $appName   the name of the app 
     * @param string $appRoot   the root path of the app
     * @access protected
     * @return void
     */
    protected function __construct($appName = 'demo', $appRoot = '')
    {
        $this->setPathFix();
        $this->setBasePath();
        $this->setFrameRoot();
        $this->setCoreLibRoot();
        $this->setAppRoot($appName, $appRoot);
        $this->setAppLibRoot();
        $this->setTmpRoot();
        $this->setCacheRoot();
        $this->setLogRoot();
        $this->setConfigRoot();
        $this->setModuleRoot();
        $this->setThemeRoot();

        $this->setSuperVars();

        $this->loadConfig('common');
        $this->setDebug();

        $this->connectDB();

        $this->setTimezone();
        $this->setClientLang();
        $this->loadLang('common');
        $this->setClientTheme();

        $this->loadClass('front',  $static = true);
        $this->loadClass('filter', $static = true);
        $this->loadClass('dao',    $static = true);
    }

    /**
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
    public static function createApp($appName = 'demo', $appRoot = '', $className = 'router')
    {
        if(empty($className)) $className = __CLASS__;
        return new $className($appName, $appRoot);
    }

    //-------------------- path related methods --------------------//

    /**
     * Set the path directory.
     * 
     * @access protected
     * @return void
     */
    protected function setPathFix()
    {
        $this->pathFix = DIRECTORY_SEPARATOR;
    }
    
    /**
     * Set the base path.
     *
     * @access protected
     * @return void
     */
    protected function setBasePath()
    {
        $this->basePath = realpath(dirname(dirname(__FILE__))) . $this->pathFix;
    }
    
    /**
     * Set the frame root.
     * 
     * @access protected
     * @return void
     */
    protected function setFrameRoot()
    {
        $this->frameRoot = $this->basePath . 'framework' . $this->pathFix;
    }

    /**
     * Set the core library root.
     * 
     * @access protected
     * @return void
     */
    protected function setCoreLibRoot()
    {
        $this->coreLibRoot = $this->basePath . 'lib' . $this->pathFix;
    }

    /**
     * Set the app root.
     *
     * @param string $appName 
     * @param string $appRoot 
     * @access protected
     * @return void
     */
    protected function setAppRoot($appName = 'demo', $appRoot = '')
    {
        if(empty($appRoot))
        {
            $this->appRoot = $this->basePath . 'app' . $this->pathFix . $appName . $this->pathFix;
        }
        else
        {
            $this->appRoot = realpath($appRoot) . $this->pathFix;
        }
        if(!is_dir($this->appRoot)) $this->error("The app you call not noud in {$this->appRoot}", __FILE__, __LINE__, $exit = true);
    }

    /**
     * Set the app lib root.
     * 
     * @access protected
     * @return void
     */
    protected function setAppLibRoot()
    {
        $this->appLibRoot = $this->appRoot . 'lib' . $this->pathFix;
    }

    /**
     * Set the tmp root.
     * 
     * @access protected
     * @return void
     */
    protected function setTmpRoot()
    {
        $this->tmpRoot = $this->appRoot . 'tmp' . $this->pathFix;
    }

    /**
     * Set the cache root.
     * 
     * @access protected
     * @return void
     */
    protected function setCacheRoot()
    {
        $this->cacheRoot = $this->tmpRoot . 'cache' . $this->pathFix;
    }

    /**
     * Set the log root.
     * 
     * @access protected
     * @return void
     */
    protected function setLogRoot()
    {
        $this->logRoot = $this->tmpRoot . 'log' . $this->pathFix;
    }

    /**
     * Set the config root.
     * 
     * @access protected
     * @return void
     */
    protected function setConfigRoot()
    {
        $this->configRoot = $this->appRoot . 'config' . $this->pathFix;
    }

    /**
     * Set the module root.
     * 
     * @access protected
     * @return void
     */
    protected function setModuleRoot()
    {
        $this->moduleRoot = $this->appRoot . 'module' . $this->pathFix;
    }

    /**
     * Set the theme root.
     * 
     * @access protected
     * @return void
     */
    protected function setThemeRoot()
    {
        $this->themeRoot = $this->appRoot . 'www' . $this->pathFix . 'theme' . $this->pathFix;
    }

    /**
     * Set the super vars.
     * 
     * @access protected
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
     * set Debug 
     * 
     * @access public
     * @return void
     */
    public function setDebug()
    {
        if(isset($this->config->debug) and $this->config->debug)
        {
            error_reporting(E_ALL & ~ E_STRICT);
            register_shutdown_function('saveSQL');
        }
    }

    /**
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
     * Get the $pathFix var
     * 
     * @access public
     * @return string
     */
    public function getPathFix()
    {
        return $this->pathFix;
    }

    /**
     * Get the $basePath var
     * 
     * @access public
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
    
    /**
     * Get the $frameRoot var
     * 
     * @access public
     * @return string
     */
    public function getFrameRoot()
    {
        return $this->frameRoot;
    }

    /**
     * Get the $coreLibRoot var
     * 
     * @access public
     * @return string
     */
    public function getCoreLibRoot()
    {
        return $this->coreLibRoot;
    }

    /**
     * Get the $appRoot var
     * 
     * @access public
     * @return string
     */
    public function getAppRoot()
    {
        return $this->appRoot;
    }
    
    /**
     * Get the $appLibRoot var
     * 
     * @access public
     * @return string
     */
    public function getAppLibRoot()
    {
        return $this->appLibRoot;
    }

    /**
     * Get the $tmpRoot var
     * 
     * @access public
     * @return string
     */
    public function getTmpRoot()
    {
        return $this->tmpRoot;
    } 

    /**
     * Get the $cacheRoot var
     * 
     * @access public
     * @return string
     */
    public function getCacheRoot()
    {
        return $this->cacheRoot;
    } 

    /**
     * Get the $logRoot var
     * 
     * @access public
     * @return string
     */
    public function getLogRoot()
    {
        return $this->logRoot;
    } 

    /**
     * Get the $configRoot var
     * 
     * @access public
     * @return string
     */
    public function getConfigRoot()
    {
        return $this->configRoot;
    }

    /**
     * Get the $moduleRoot var
     * 
     * @access public
     * @return string
     */
    public function getModuleRoot()
    {
        return $this->moduleRoot;
    }

    /**
     * Get the $themeRoot var
     * 
     * @access public
     * @return string
     */
    public function getThemeRoot()
    {
        return $this->themeRoot;
    }

    //-------------------- Client environment related functions --------------------//

    /**
     * Set the language used by the client user.
     * 
     * Using the order of method $lang param, session, cookie, browser and last the default lang.
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
     * Set the them the client user usering. The logic is same as the clientLang.
     *
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
        }    
        else
        {
            $this->clientTheme = $this->config->default->theme;
        }
        setcookie('theme', $this->clientTheme, $this->config->cookieLife, $this->config->webRoot);
        if(!isset($_COOKIE['theme'])) $_COOKIE['theme'] = $this->clientTheme;
    }

    /**
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
     * Get the $webRoot var.
     * 
     * @access public
     * @return string
     */
    public function getWebRoot()
    {
        return $this->config->webRoot;
    }

    //-------------------- Request related methods. --------------------//

    /**
     * The entrance of parseing request. According to the requestType, call related methods.
     * 
     * @access public
     * @return void
     */
    public function parseRequest()
    {
        if($this->config->requestType == 'PATH_INFO')
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
            $this->error("The request type {$this->config->requestType} not supported", __FILE__, __LINE__, $exit = true);
        }
    }

    /**
     * Parse PATH_INFO, get the $URI and $viewType.
     * 
     * @access public
     * @return void
     */
    public function parsePathInfo()
    {
        $pathInfo = $this->getPathInfo('PATH_INFO');
        if(empty($pathInfo)) $pathInfo = $this->getPathInfo('ORIG_PATH_INFO');
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
     * Get $PATH_INFO from $_SERVER or $_ENV by the pathinfo var name.
     *
     * Mostly, the var name of PATH_INFO is  PATH_INFO, but may be ORIG_PATH_INFO.
     * 
     * @param   string  $varName    PATH_INFO, ORIG_PATH_INFO
     * @access  private
     * @return  string the PATH_INFO
     */
    private function getPathInfo($varName)
    {
        $value = @getenv($varName);
        if(isset($_SERVER[$varName])) $value = $_SERVER[$varName];
        return trim($value, '/');
    }

    /**
     * Parse GET, get $URI and $viewType.
     * 
     * @access private
     * @return void
     */
    private function parseGET()
    {
        if(isset($_GET[$this->config->viewVar]))
        {
            $this->viewType = $_GET[$this->config->viewVar]; 
            if(strpos($this->config->views, ',' . $this->viewType . ',') === false)
            {
                $this->viewType = $this->config->default->view;
            }
        }
        else
        {
            $this->viewType = $this->config->default->view;
        }
        $this->URI = $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Get the $URL
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
     * Get the $viewType var.
     * 
     * @access public
     * @return string
     */
    public function getViewType()
    {
        return $this->viewType;
    }

    //-------------------- Routing related methods.--------------------//

    /**
     * Load the common module
     *
     *  The common module is a special module, which can be used to do some common things. For examle:
     *  start session, check priviledge and so on.
     *  This method should called manually in the router file(www/index.php) after the $lang, $config, $dbh loadde.
     *
     * @access public
     * @return object|bool  the common control object or false if not exits.
     */
    public function loadCommon()
    {
        $this->setModuleName('common');
        if($this->setControlFile($exitIfNone = false))
        {
            include $this->controlFile;
            if(class_exists('common'))
            {
                return new common();
            }    
            else
            {
                return false;
            }
        }
    }

    /**
     * Set the name of the module to be called.
     * 
     * @param   string $moduleName  the module name
     * @access  public
     * @return  void
     */
    public function setModuleName($moduleName = '')
    {
        $this->moduleName = strtolower($moduleName);
    }

    /**
     * Set the control file of the module to be called.
     * 
     * @param   bool    $exitIfNone     If control file not foundde, how to do. True, die the whole app. false, log error.
     * @access  public
     * @return  bool
     */
    public function setControlFile($exitIfNone = true)
    {
        $this->controlFile = $this->moduleRoot . $this->moduleName . $this->pathFix . 'control.php';
        if(!is_file($this->controlFile))
        {
            $this->error("the control file $this->controlFile not found.", __FILE__, __LINE__, $exitIfNone);
            return false;
        }
        return true;
    }
    
    /**
     * Set the name of the method calling.
     * 
     * @param string $methodName 
     * @access public
     * @return void
     */
    public function setMethodName($methodName = '')
    {
        $this->methodName = strtolower($methodName);
    }

    /**
     * Get the path of one module.
     * 
     * @param  string $moduleName    the module name
     * @access public
     * @return string the module path
     */
    public function getModulePath($moduleName = '')
    {
        if($moduleName == '') $moduleName = $this->moduleName;
        return $this->getModuleRoot() . strtolower(trim($moduleName)) . $this->pathFix;
    }

    /**
     * Get extension path of one module.
     * 
     * @param   string $moduleName     the module name
     * @param   string $ext            the extension type, can be control|model|view|lang|config
     * @access  public
     * @return  string the extension path.
     */
    public function getModuleExtPath($moduleName, $ext)
    {
        return $this->getModuleRoot() . strtolower(trim($moduleName)) . $this->pathFix . 'ext' . $this->pathFix . $ext . $this->pathFix;
    }

    /**
     * Set the action extension file.
     * 
     * @access  public
     * @return  bool
     */
    public function setActionExtFile()
    {
        $moduleExtPath = $this->getModuleExtPath($this->moduleName, 'control');
        $this->extActionFile = $moduleExtPath . $this->methodName . '.php';
        return file_exists($this->extActionFile);
    }

    /**
     * Set the route according to PATH_INFO.
     * 
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
            /* There's the request seperator, split the URI by it. */
            if(strpos($this->URI, $this->config->requestFix) !== false)
            {
                $items = explode($this->config->requestFix, $this->URI);
                $this->setModuleName($items[0]);
                $this->setMethodName($items[1]);
            }    
            /* No reqeust seperator, use the default method name. */
            else
            {
                $this->setModuleName($this->URI);
                $this->setMethodName($this->config->default->method);
            }
        }
        else
        {    
            $this->setModuleName($this->config->default->module);   // use the default module.
            $this->setMethodName($this->config->default->method);   // use the default method.
        }
        $this->setControlFile();
    }

    /**
     * Set the route according to GET.
     * 
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
     * Load a module.
     *
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

        /* Include the contror file of the module. */
        $file2Included = $this->setActionExtFile() ? $this->extActionFile : $this->controlFile;
        chdir(dirname($file2Included));
        include $file2Included;

        /* Set the class name of the control. */
        $className = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
        if(!class_exists($className)) $this->error("the control $className not found", __FILE__, __LINE__, $exit = true);

        /* Create a instance of the control. */
        $module = new $className();
        if(!method_exists($module, $methodName)) $this->error("the module $moduleName has no $methodName method", __FILE__, __LINE__, $exit = true);
        $this->control = $module;

        /* include default value for module*/
        $defaultValueFiles = glob($this->getTmpRoot() . "defaultvalue/*.php");
        if($defaultValueFiles) foreach($defaultValueFiles as $file) include $file;

        /* Get the default setings of the method to be called useing the reflecting. */
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

        /* Set params according PATH_INFO or GET. */
        if($this->config->requestType == 'PATH_INFO')
        {
            $this->setParamsByPathInfo($defaultParams);
        }
        elseif($this->config->requestType == 'GET')
        {
            $this->setParamsByGET($defaultParams);
        }

        /* Call the method. */
        call_user_func_array(array(&$module, $methodName), $this->params);
        return $module;
    }

    /**
     * Set the params by PATH_INFO
     * 
     * @param   array $defaultParams the default settings of the params.
     * @access  public
     * @return  void
     */
    public function setParamsByPathInfo($defaultParams = array())
    {
        /* Spit the URI. */
        $items     = explode($this->config->requestFix, $this->URI);
        $itemCount = count($items);

        $params = array();
        /* The clean mode, only passed in values, no keys. */
        if($this->config->pathType == 'clean')
        {
            /* The first two item is moduleName and methodName. So the params should begin at 2.*/
            for($i = 2; $i < $itemCount; $i ++)
            {
                $key = key($defaultParams);     // Get key from the $defaultParams.
                $params[$key] = $items[$i];
                next($defaultParams);
            }
        }
        /* The full mode, both key and value passed in. */
        elseif($this->config->pathType == 'full')
        {
            for($i = 2; $i < $itemCount; $i += 2)
            {
                $key   = $items[$i];
                $value = $items[$i + 1];
                $params[$key] = $value;
            }
        }
        $this->params = $this->mergeParams($defaultParams, $params);
    }

    /**
     * Set the params by GET.
     * 
     * @param   array $defaultParams the default settings of the params.
     * @access  public
     * @return  void
     */
    public function setParamsByGET($defaultParams)
    {
        /* Unset the moduleVar, methodVar, viewVar and session var, all the left are the params. */
        unset($_GET[$this->config->moduleVar]);
        unset($_GET[$this->config->methodVar]);
        unset($_GET[$this->config->viewVar]);
        unset($_GET[$this->config->sessionVar]);
        $this->params = $this->mergeParams($defaultParams, $_GET);
    }

    /**
     * Merge the params passed in and the default params. Thus the params which have default values needn't pass value, just like a function.
     *
     * @param   array $defaultParams     the default params defined by the method.
     * @param   array $passedParams      the params passed in through url.
     * @access  private
     * @return  array the merged params.
     */
    private function mergeParams($defaultParams, $passedParams)
    {
        /* If the not strict mode, the keys of passed params and defaaul params msut be the same. */
        if(!isset($this->config->strictParams) or $this->config->strictParams == false) 
        {
            $passedParams = array_values($passedParams);
            $i = 0;
            foreach($defaultParams as $key => $defaultValue)
            {
                if(isset($passedParams[$i]))
                {
                    $defaultParams[$key] = $passedParams[$i];
                }
                else
                {
                    if($defaultValue === '_NOT_SET') $this->error("The param '$key' should pass value. ", __FILE__, __LINE__, $exit = true);
                }
                $i ++;
            }
        }
        /* If in strict mode, the keys of the passed params must be the same with the default params, but order can be different. */
        else
        {
            foreach($defaultParams as $key => $defaultValue)
            {
                if(isset($passedParams[$key]))
                {
                    $defaultParams[$key] = $passedParams[$key];
                }
                else
                {
                    if($defaultValue === '_NOT_SET') $this->error("The param '$key' should pass value. ", __FILE__, __LINE__, $exit = true);
                }
            }
        }
        return $defaultParams;
    }
 
    /**
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
     * Get the $param var.
     * 
     * @access public
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    //-------------------- Tool methods.------------------//
    
    /**
     * The error handler.
     * 
     * @param string    $message    error message
     * @param string    $file       the file error occers
     * @param int       $line       the line error occers
     * @param bool      $exit       exit the program or not
     * @access public
     * @return void
     */
    public function error($message, $file, $line, $exit = false)
    {
        /* Log the error info. */
        $log = "ERROR: $message in $file on line $line";
        if(isset($_SERVER['SCRIPT_URI'])) $log .= ", request: $_SERVER[SCRIPT_URI]";; 
        $trace = debug_backtrace();
        extract($trace[0]);
        extract($trace[1]);
        $log .= ", last called by $file on $line through function $function.";
        error_log($log);

        /* If exit, output the error. */
        if($exit)
        {
            if($this->config->debug) die("<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>$log</body></html>");
            die();
        }
    }

    /**
     * Load a class file.
     * 
     * First search in $appLibRoot, then $coreLibRoot.
     *
     * @param   string $className  the class name
     * @param   bool   $static     statis class or not
     * @access  public
     * @return  object|bool the instance of the class or just true.
     */
    public function loadClass($className, $static = false)
    {
        $className = strtolower($className);

        /* Search in $appLibRoot. */
        $classFile = $this->appLibRoot . $className;
        if(is_dir($classFile)) $classFile .= $this->pathFix . $className;
        $classFile .= '.class.php';

        if(!helper::import($classFile))
        {
            /* Search in $coreLibRoot. */
            $classFile = $this->coreLibRoot . $className;
            if(is_dir($classFile)) $classFile .= $this->pathFix . $className;
            $classFile .= '.class.php';
            if(!helper::import($classFile)) $this->error("class file $classFile not found", __FILE__, __LINE__, $exit = true);
        }

        /* If staitc, return. */
        if($static) return true;

        /* Instance it. */
        global $$className;
        if(!class_exists($className)) $this->error("the class $className not found in $classFile", __FILE__, __LINE__, $exit = true);
        if(!is_object($$className)) $$className = new $className();
        return $$className;
    }

    /**
     * Load config and return it as the global config object.
     * 
     * If the module is common, search in $configRoot, else in $modulePath.
     *
     * @param   string $moduleName     module name
     * @param   bool  $exitIfNone     exit or not
     * @access  public
     * @return  object|bool the config object or false.
     */
    public function loadConfig($moduleName, $exitIfNone = true)
    {
        global $config;
        if(!is_object($config)) $config = new config();

        $extConfigFiles = array();

        /* Set the main config file and extension config file. */
        if($moduleName == 'common')
        {
            $mainConfigFile = $this->configRoot . 'config.php';
        }
        else
        {
            $mainConfigFile = $this->getModulePath($moduleName) . 'config.php';
            $extConfigPath  = $this->getModuleExtPath($moduleName, 'config');
            $extConfigFiles = helper::ls($extConfigPath, '.php');
        }

        /* Set the files to include. */
        if(!is_file($mainConfigFile))
        {
            if($exitIfNone) self::error("config file $mainConfigFile not found", __FILE__, __LINE__, true);
            if(empty($extConfigFiles) and !isset($config->system->$moduleName)) return false;  //  and no extension file or extension in db, exit.
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

        /* Merge from the db configs. */
        if($moduleName != 'common' and isset($config->system->$moduleName))
        {
            foreach($config->system->$moduleName as $item)
            {
                if($item->section)
                {
                    if(!isset($config->{$moduleName}->{$item->section})) $config->{$moduleName}->{$item->section} = new stdclass();
                    $config->{$moduleName}->{$item->section}->{$item->key} = $item->value;
                }
                else
                {
                    if(!$item->section) $config->{$moduleName}->{$item->key} = $item->value;
                }
            }
        }

        $this->config = $config;

        return $config;
    }

    /**
     * Export the config params to the client, thus the client can adjust it's logic according the config.
     * 
     * @access public
     * @return void
     */
    public function exportConfig()
    {
        $view->version     = $this->config->version;
        $view->requestType = $this->config->requestType;
        $view->pathType    = $this->config->pathType;
        $view->requestFix  = $this->config->requestFix;
        $view->moduleVar   = $this->config->moduleVar;
        $view->methodVar   = $this->config->methodVar;
        $view->viewVar     = $this->config->viewVar;
        $view->sessionVar  = $this->config->sessionVar;
        echo json_encode($view);
    }
    
    /**
     * Load lang and return it as the global lang object.
     * 
     * @param   string $moduleName     the module name
     * @access  public
     * @return  bool|ojbect the lang object or false.
     */
    public function loadLang($moduleName)
    {
        $modulePath   = $this->getModulePath($moduleName);
        $mainLangFile = $modulePath . 'lang' . $this->pathFix . $this->clientLang . '.php';
        $extLangPath  = $this->getModuleExtPath($moduleName, 'lang');
        $extLangFiles = helper::ls($extLangPath . $this->clientLang, '.php');

        /* Set the files to includ. */
        if(!is_file($mainLangFile))
        {
            if(empty($extLangFiles)) return false;  // also no extension file.
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

        $this->lang = $lang;
        return $lang;
    }

    /**
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
     * Connect database by PDO.
     * 
     * @param  object    $params    the database params.
     * @access private
     * @return object|bool
     */
    private function connectByPDO($params)
    {
        if(!isset($params->driver)) self::error('no pdo driver defined, it should be mysql or sqlite', __FILE__, __LINE__, $exit = true);
        if(!isset($params->user)) return false;
        if($params->driver == 'mysql')
        {
            $dsn = "mysql:host={$params->host}; port={$params->port}; dbname={$params->name}";
        }    
        try 
        {
            $dbh = new PDO($dsn, $params->user, $params->password, array(PDO::ATTR_PERSISTENT => $params->persistant));
            $dbh->exec("SET NAMES {$params->encoding}");

            /* If run on linux, set emulatePrepare and bufferQuery to true. */
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
            self::error($exception->getMessage(), __FILE__, __LINE__, $exit = true);
        }
    }
}

/**
 * The config class.
 * 
 * @package framework
 */
class config
{ 
    /**
     * Set the value of a member. the member can be the foramt like db.user.
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
 * The lang class.
 * 
 * @package framework
 */
class language 
{
    /**
     * Set the value of a member. the member can be the foramt like db.user.
     * 
     * <code>
     * <?php
     * $lang->set('version', '1.0); 
     * ?>
     * </code>
     * @param   string  $key    the key of the member, can be father.child
     * @param   mixed   $value  the value
     * @access  public
     * @return  void
     */
    public function set($key, $value)
    {
        helper::setMember('lang', $key, $value);
    }

    /**
     * Show a member 
     * 
     * @param   object $obj    the object
     * @param   string $key    the key
     * @access  public
     * @return  void
     */
    public function show($obj, $key)
    {
        $obj = (array)$obj;
        if(isset($obj[$key])) echo $obj[$key]; else echo '';
    }
}

/**
 * The super object class.
 * 
 * @package framework
 */
class super
{
    /**
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
            $GLOBAL[$key] = $value;
        }
    }

    /**
     * The magic get method.
     * 
     * @param  string $key    the key
     * @access public
     * @return mixed|bool return the value of the key or false.
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
        if($this->scope == 'global')  a($GLOBAL);
    }
}

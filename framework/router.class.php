<?php
/**
 * The router, config and lang class file of ZenTaoPHP.
 *
 * ZenTaoPHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * ZenTaoPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoPHP.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPHP
 * @version     $Id: router.class.php 133 2010-09-11 07:22:48Z wwccss $
 * @link        http://www.zentao.net
 */
/**
 * 路由类，也是整个框架最核心的类。
 * 
 * @package ZenTaoPHP
 */
class router
{
    /**
     * 文件系统的路径分隔符。
     * 
     * @var string
     * @access private
     */
    private $pathFix;

    /**
     * 应用的基准路径。
     *
     * @var string
     * @access private
     */
    private $basePath;

    /**
     * 框架基类文件所在的路径。
     * 
     * @var string
     * @access private
     */
    private $frameRoot;

    /**
     * 框架所带的library目录。
     * 
     * @var string
     * @access private
     */
    private $coreLibRoot;

    /**
     * 当前应用程序所在的目录。
     * 
     * @var string
     * @access private
     */
    private $appRoot;

    /**
     * 应用程序的library目录。
     * 
     * @var string
     * @access private
     */
    private $appLibRoot;

    /**
     * 临时文件所在的目录
     * 
     * @var string
     * @access private
     */
    private $tmpRoot;

    /**
     * 缓存文件所在的根目录。
     * 
     * @var string
     * @access private
     */
    private $cacheRoot;

    /**
     * 日志文件所在的目录。
     * 
     * @var string
     * @access private
     */
    private $logRoot;

    /**
     * 配置文件所在的根目录。
     * 
     * @var string
     * @access private
     */
    private $configRoot;

    /**
     * 各个模块所在的根目录。
     * 
     * @var string
     * @access private
     */
    private $moduleRoot;

    /**
     * 主题文件所在的根目录。
     * 
     * @var string
     * @access private
     */
    private $themeRoot;

    /**
     * 用户所使用的语言。
     * 
     * @var string
     * @access private
     */
    private $clientLang;

    /**
     * 用户所使用的主题。
     * 
     * @var string
     * @access private
     */
    private $clientTheme;

    /**
     * 当前需要加载的模块名称。
     * 
     * @var string
     * @access private
     */
    private $moduleName;

    /**
     * 当前模块的扩展目录。
     * 
     * @var string
     * @access private
     */
    private $moduleExtRoot;

    /**
     * 当前模块所对应的控制器文件。
     * 
     * @var string
     * @access private
     */
    private $controlFile;

    /**
     * 需要调用的方法。
     * 
     * @var string
     * @access private
     */
    private $methodName;

    /**
     * 当前模块所对应的派生出来的action文件。
     * 
     * @var string
     * @access private
     */
    private $extActionFile;

    /**
     * 当前请求的URI。
     * 
     * @var string
     * @access private
     */
    private $URI;

    /**
     * 要传递给给调用方法的参数。
     * 
     * @var array
     * @access private
     */
    private $params;

    /**
     * 视图格式。
     * 
     * @var string
     * @access private
     */
    private $viewType;

    /**
     * 配置对象。
     * 
     * @var string
     * @access private
     */
    public $config;

    /**
     * 语言对象。
     * 
     * @var string
     * @access private
     */
    public $lang;

    /**
     * 数据库访问对象。
     * 
     * @var string
     * @access private
     */
    public $dbh;

    /**
     * POST对象。
     * 
     * @var ojbect
     * @access public
     */
    public $post;

    /**
     * GET对象。
     * 
     * @var ojbect
     * @access public
     */
    public $get;

    /**
     * session对象。
     * 
     * @var ojbect
     * @access public
     */
    public $session;

    /**
     * server对象。
     * 
     * @var ojbect
     * @access public
     */
    public $server;

    /**
     * cookie对象。
     * 
     * @var ojbect
     * @access public
     */
    public $cookie;


    /**
     * global对象。
     * 
     * @var ojbect
     * @access public
     */
    public $global;

    /**
     * 构造函数。
     * 
     * 主要完成各个路径变量的设置。注意，该构造函数为私有函数，应当使用createApp方法来实例化路由对象。
     *
     * @param string $appName   应用的名称，如果没有指定$appRoot变量，系统会根据$appName来计算应用的根目录。
     * @param string $appRoot   应用所在的根目录。
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
    }

    /**
     * 生成一个应用。
     * 
     * <code>
     * <?php
     * $demo = router::createApp('demo');
     * ?>
     * 或者指定demo应用所在的目录。
     * <?php
     * $demo = router::createApp('demo', '/home/app/demo');
     * ?>
     * </code>
     * @param string $appName   应用的名称
     * @param string $appRoot   应用所在的根目录，可以为空。
     * @param string $className 对象名称，当从router派生一个子类，然后调用该方法时，可以指定该参数。
     * @static
     * @access public
     * @return void
     */
    public static function createApp($appName = 'demo', $appRoot = '', $className = 'router')
    {
        if(empty($className)) $className = __CLASS__;
        return new $className($appName, $appRoot);
    }

    //-------------------- 路径相关的方法。--------------------//

    /**
     * 设置路径分隔符，方便调用。
     * 
     * @access protected
     * @return void
     */
    protected function setPathFix()
    {
        $this->pathFix = DIRECTORY_SEPARATOR;
    }
    
    /**
     * 设置整个框架所在的根目录。
     *
     * @access protected
     * @return void
     */
    protected function setBasePath()
    {
        $this->basePath = realpath(dirname(dirname(__FILE__))) . $this->pathFix;
    }
    
    /**
     * 设置框架核心类文件所在的根目录。
     * 
     * @access protected
     * @return void
     */
    protected function setFrameRoot()
    {
        $this->frameRoot = $this->basePath . 'framework' . $this->pathFix;
    }

    /**
     * 设置coreLib文件的根目录。
     * 
     * @access protected
     * @return void
     */
    protected function setCoreLibRoot()
    {
        $this->coreLibRoot = $this->basePath . 'lib' . $this->pathFix;
    }

    /**
     * 设置应用程序所在的根目录。
     *
     * 默认情况下面根据appName来进行计算，如果指定了appRoot，直接用之。
     * 通过这种机制，框架和应用可以分开部署。
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
     * 设置appLib文件的根目录。
     * 
     * @access protected
     * @return void
     */
    protected function setAppLibRoot()
    {
        $this->appLibRoot = $this->appRoot . 'lib' . $this->pathFix;
    }

    /**
     * 设置临时文件所在的目录。
     * 
     * @access protected
     * @return void
     */
    protected function setTmpRoot()
    {
        $this->tmpRoot = $this->appRoot . 'tmp' . $this->pathFix;
    }

    /**
     * 设置缓存文件所在的根目录。
     * 
     * @access protected
     * @return void
     */
    protected function setCacheRoot()
    {
        $this->cacheRoot = $this->tmpRoot . 'cache' . $this->pathFix;
    }

    /**
     * 设置日志文件所在的目录。
     * 
     * @access protected
     * @return void
     */
    protected function setLogRoot()
    {
        $this->logRoot = $this->tmpRoot . 'log' . $this->pathFix;
    }

    /**
     * 设置配置文件所在的根目录。
     * 
     * @access protected
     * @return void
     */
    protected function setConfigRoot()
    {
        $this->configRoot = $this->appRoot . 'config' . $this->pathFix;
    }

    /**
     * 设置module所在的根目录。
     * 
     * @access protected
     * @return void
     */
    protected function setModuleRoot()
    {
        $this->moduleRoot = $this->appRoot . 'module' . $this->pathFix;
    }

    /**
     * 设置客户端主题文件所在的根目录。
     * 
     * @access protected
     * @return void
     */
    protected function setThemeRoot()
    {
        $this->themeRoot = $this->appRoot . 'www' . $this->pathFix . 'theme' . $this->pathFix;
    }

    /**
     * 设置超全局变量。
     * 
     * @access protected
     * @return void
     */
    public function setSuperVars()
    {
        if(isset($this->config->super2OBJ) and $this->config->super2OBJ)
        {
            $this->post    = new super('post');
            $this->get     = new super('get');
            $this->server  = new super('server');
            $this->cookie  = new super('cookie');
            $this->session = new super('session');
            $this->global  = new super('global');
        }
    }

    /* 设置debug级别。*/
    public function setDebug()
    {
        isset($this->config->debug) and $this->config->debug ? error_reporting(E_ALL & ~ E_STRICT) : error_reporting(0);
    }

    /* 设置时区。*/
    public function setTimezone()
    {
        if(isset($this->config->timezone)) date_default_timezone_set($this->config->timezone);
    }

    /**
     * 返回路径分隔符。
     * 
     * @access public
     * @return string
     */
    public function getPathFix()
    {
        return $this->pathFix;
    }

    /**
     * 返回整个框架的所在的目录。
     * 
     * @access public
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
    
    /**
     * 返回框架核心类文件所在的根目录。
     * 
     * @access public
     * @return string
     */
    public function getFrameRoot()
    {
        return $this->frameRoot;
    }

    /**
     * 返回lib文件所在的根目录。
     * 
     * @access public
     * @return string
     */
    public function getCoreLibRoot()
    {
        return $this->coreLibRoot;
    }

    /**
     * 返回应用程序所在的根目录。
     * 
     * @access public
     * @return string
     */
    public function getAppRoot()
    {
        return $this->appRoot;
    }
    
    /**
     * 返回appLib文件所在的根目录。
     * 
     * @access public
     * @return string
     */
    public function getAppLibRoot()
    {
        return $this->appLibRoot;
    }

    /**
     * 返回临时文件所在的目录。
     * 
     * @access public
     * @return string
     */
    public function getTmpRoot()
    {
        return $this->tmpRoot;
    } 

    /**
     * 返回缓存文件所在的根目录。
     * 
     * @access public
     * @return string
     */
    public function getCacheRoot()
    {
        return $this->cacheRoot;
    } 

    /**
     * 返回日志文件所在的目录。
     * 
     * @access public
     * @return string
     */
    public function getLogRoot()
    {
        return $this->logRoot;
    } 

    /**
     * 返回配置文件所在的根目录。
     * 
     * @access public
     * @return string
     */
    public function getConfigRoot()
    {
        return $this->configRoot;
    }

    /**
     * 返回模块文件所在的根目录。
     * 
     * @access public
     * @return string
     */
    public function getModuleRoot()
    {
        return $this->moduleRoot;
    }

    /**
     * 返回主题文件所在的根目录。
     * 
     * @access public
     * @return string
     */
    public function getThemeRoot()
    {
        return $this->themeRoot;
    }

    //-------------------- 客户端环境设置。--------------------//

    /**
     * 设置客户端所使用的语言。
     * 
     * 如果手工指定了语言的选项，则以手工指定为主。
     * 然后再查找session里面是否有登记，
     * 然后再看cookie里面是否有登记。
     * 然后再查看浏览器支持的语言，
     * 如果通过上面取出的语言选项和系统支持的不匹配，则使用默认的语言。
     *
     * @param   string $lang  形如zh-cn|zh-tw|zh-hk|en。
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
            $this->clientLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ','));
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
        setcookie('lang', $this->clientLang, $this->config->cookieLife, $this->config->cookiePath);
    }

    /**
     * 返回客户端使用的语言。
     * 
     * @access public
     * @return string
     */
    public function getClientLang()
    {
        return $this->clientLang;
    }

    /**
     * 设置客户端所使用的主题。逻辑同setClientLang();
     *
     * 主题风格所对应的样式表、图片等文件应当存放在www/theme/，分目录存放。目录的名字就是风格的名字。
     * 
     * @param   string $theme   主题风格。
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
            if(strpos($this->config->themes, $this->clientTheme) === false)
            {
                $this->clientTheme = $this->config->default->theme;
            }
        }    
        else
        {
            $this->clientTheme = $this->config->default->theme;
        }
        setcookie('theme', $this->clientTheme, $this->config->cookieLife, $this->config->cookiePath);
    }

    /**
     * 返回客户端所使用的主题。
     * 
     * @access public
     * @return string
     */
    public function getClientTheme()
    {
        return $this->config->webRoot . 'theme/' . $this->clientTheme . '/';
    }

    /**
     * 返回web的根目录。
     * 
     * @access public
     * @return string
     */
    public function getWebRoot()
    {
        return $this->config->webRoot;
    }

    //-------------------- 解析请求。--------------------//

    /**
     * 处理请求，分为PATH_INFO和GET两种模式。
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
     * 从请求中得出PATH_INFO信息。 
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
            $dotPos = strpos($pathInfo, '.');
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
     * 辅助函数：从env或者_SERVER变量中获取某个PATH_INFO的变种。
     * 
     * @param   string  $varName     目前支持PATH_INFO
     * @access  private
     * @return  string
     */
    private function getPathInfo($varName)
    {
        $value = @getenv($varName);
        if(isset($_SERVER[$varName])) $value = $_SERVER[$varName];
        return trim($value, '/');
    }

    /**
     * 解析通过GET方式传递过来的参数。
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
     * 返回当前请求的URI。
     * 
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
     * 返回当前请求的viewType。
     * 
     * @access public
     * @return string
     */
    public function getViewType()
    {
        return $this->viewType;
    }

    //-------------------- 路由相关的方法。--------------------//

    /**
     * 加载公共的common模块。
     *
     * 该公共模块可以来完成一些公用的任务，比如启动session，进行用户权限验证等。
     * 该方法没有自动调用，如果需要，可以在router文件中自己加入该逻辑。
     * 但需要注意的是该方法调用应当在lang, config, dbh等对象加载完成之后。
     *
     * @access public
     * @return object
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
     * 设置要调用的模块。
     * 
     * @param   string $moduleName  模块名字。
     * @access  public
     * @return  void
     */
    public function setModuleName($moduleName = '')
    {
        $this->moduleName = strtolower($moduleName);
    }

    /**
     * 设置要加载的控制器文件。
     * 
     * @param   bool    $exitIfNone     如果没有发现控制器文件，是否退出。默认值true。
     * @access  public
     * @return  bool
     */
    public function setControlFile($exitIfNone = true)
    {
        $this->controlFile = $this->moduleRoot . $this->moduleName . $this->pathFix . 'control.php';
        if(!file_exists($this->controlFile))
        {
            $this->error("the control file $this->controlFile not found.", __FILE__, __LINE__, $exitIfNone);
            return false;
        }    
        return true;
    }
    
    /**
     * 设置要调用的方法。
     * 
     * @param string $methodName    调用的方法名。 
     * @access public
     * @return void
     */
    public function setMethodName($methodName = '')
    {
        $this->methodName = strtolower($methodName);
    }

    /* 获得某一个模块的根目录。*/
    public function getModulePath($moduleName = '')
    {
        if($moduleName == '') $moduleName = $this->moduleName;
        return $this->getModuleRoot() . strtolower(trim($moduleName)) . $this->pathFix;
    }

    /* 获得某一个模块，某种扩展的目录。ext可选值：control, model, view, lang, config。*/
    public function getModuleExtPath($moduleName, $ext)
    {
        return $this->getModuleRoot() . strtolower(trim($moduleName)) . $this->pathFix . 'opt' . $this->pathFix . $ext . $this->pathFix;
    }

    /**
     * 设置当前调用方法对应的扩展文件。
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
     * 根据PATH_INFO信息设置路由。
     * 
     * @access public
     * @return void
     */
    public function setRouteByPathInfo()
    {
        if(!empty($this->URI))
        {
            /* URL中含有参数信息。*/
            if(strpos($this->URI, $this->config->requestFix) !== false)
            {
                $items = explode($this->config->requestFix, $this->URI);
                $this->setModuleName($items[0]);
                $this->setMethodName($items[1]);
            }    
            else
            {
                $this->setModuleName($this->URI);
                $this->setMethodName($this->config->default->method); // 取默认的方法。
            }
        }
        else
        {    
            $this->setModuleName($this->config->default->module);   // 取默认的模块。
            $this->setMethodName($this->config->default->method);   // 取默认的方法。
        }
        $this->setControlFile();
    }

    /**
     * 通过GET变量来设置路由信息。
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
     * 加载模块。
     * 
     * @access public
     * @return void
     */
    public function loadModule()
    {
        $moduleName = $this->moduleName;
        $methodName = $this->methodName;

        /* 设置要加载的文件。*/
        $file2Included = $this->setActionExtFile() ? $this->extActionFile : $this->controlFile;
        chdir(dirname($file2Included));
        include $file2Included;

        /* 设置要调用的类的名称。*/
        $className = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
        if(!class_exists($className)) $this->error("the control $className not found", __FILE__, __LINE__, $exit = true);

        $module = new $className();
        if(!method_exists($module, $methodName)) $this->error("the module $moduleName has no $methodName method", __FILE__, __LINE__, $exit = true);

        /* 获取方法的参数定义。*/
        $defaultParams = array();
        $methodReflect = new reflectionMethod($className, $methodName);
        foreach($methodReflect->getParameters() as $param)
        {
            $name    = $param->getName();
            $default = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : '_NOT_SET';
            $defaultParams[$name] = $default;
        }

        /* 设置参数。*/
        if($this->config->requestType == 'PATH_INFO')
        {
            $this->setParamsByPathInfo($defaultParams);
        }
        elseif($this->config->requestType == 'GET')
        {
            $this->setParamsByGET($defaultParams);
        }

        /* 调用相应的方法。*/
        call_user_func_array(array(&$module, $methodName), $this->params);
        return $module;
    }

    /**
     * 通过PATH_INFO信息来设置需要传递给control类方法的参数。
     * 
     * @param   array $defaultParams    方法定义中默认值列表
     * @access  public
     * @return  void
     */
    public function setParamsByPathInfo($defaultParams = array())
    {
        /* 将请求串按照分割符分开。*/
        $items     = explode($this->config->requestFix, $this->URI);
        $itemCount = count($items);

        /**
         * items前面两个元素分别为moduleName和methodName，因此从第二个元素开始。
         * 分别为clean模式和full模式。
         */ 

        $params = array();
        if($this->config->pathType == 'clean')
        {
            for($i = 2; $i < $itemCount; $i ++)
            {
                $key = key($defaultParams);
                $params[$key] = $items[$i];
                next($defaultParams);
            }
        }
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
     * 通过GET变量设置需要传递给control类访问的参数。
     * 
     * @param   array   $defaultParams  方法定义中默认值列表。
     * @access  public
     * @return  void
     */
    public function setParamsByGET($defaultParams)
    {
        unset($_GET[$this->config->moduleVar]);
        unset($_GET[$this->config->methodVar]);
        unset($_GET[$this->config->viewVar]);
        unset($_GET[$this->config->sessionVar]);
        $this->params = $this->mergeParams($defaultParams, $_GET);
    }

    /**
     * 将方法定义中的默认值与用户请求中传递的值合并起来。
     *
     * @param   array $defaultParams     方法定义中的参数默认值列表。
     * @param   array $passedParams      用户请求中传递的参数列表。
     * @access  private
     * @return  array
     */
    private function mergeParams($defaultParams, $passedParams)
    {
        /* 如果参数传递是非严格模式，认为passedParams的顺序和defaultParams是一致的。*/
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
     * 返回当前调用的模块名称。
     * 
     * @access public
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * 返回control文件路径。
     * 
     * @access public
     * @return string
     */
    public function getControlFile()
    {
        return $this->controlFile;
    }

    /**
     * 返回当前调用的control的方法。
     * 
     * @access public
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * 返回当前传递的参数名。
     * 
     * @access public
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    //-------------------- 工具类方法。-------------------//

    /**
     * 错误处理函数。
     * 
     * @param string    $message    错误信息。
     * @param string    $file       发生错误的文件名。
     * @param int       $line       发生错误的行号。
     * @param bool      $exit       是否中止程序。
     * @access public
     * @return void
     */
    public function error($message, $file, $line, $exit = false)
    {
        /* 记录错误信息。*/
        $log = "ERROR: $message in $file on line $line";
        if(isset($_SERVER['SCRIPT_URI'])) $log .= ", request: $_SERVER[SCRIPT_URI]";; 
        $trace = debug_backtrace();
        extract($trace[0]);
        extract($trace[1]);
        $log .= ", last called by $file on $line through function $function.";
        error_log($log);

        /* 如果需要终止程序，则显示到终端用户的屏幕上。*/
        if($exit)
        {
            if($this->config->debug) die("<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>$log</body></html>");
            die();
        }
    }

    /**
     * 加载某一个类文件。
     * 
     * 该方法会首先尝试从appLibRoot下面查找，然后再到coreLibRoot下面查找。
     *
     * @param mixed $className  类名。 
     * @param mixed $static     是否为静态类。
     * @access public
     * @return object           以类名为名的对象。
     */
    public function loadClass($className, $static = false)
    {
        $className = strtolower($className);

        /* 先试着加载appLibRoot下面的类文件。*/
        $classFile = $this->appLibRoot . $className;
        if(is_dir($classFile)) $classFile .= $this->pathFix . $className;
        $classFile .= '.class.php';

        if(!file_exists($classFile)) 
        {
            /* 再试着加载coreLibRoot下面的类文件。*/
            $classFile = $this->coreLibRoot . $className;
            if(is_dir($classFile)) $classFile .= $this->pathFix . $className;
            $classFile .= '.class.php';
            if(!file_exists($classFile)) $this->error("class file $classFile not found", __FILE__, __LINE__, $exit = true);
        }

        helper::import($classFile);

        /* 如果是静态类的话，无需实例化，直接退出。*/
        if($static) return;

        /* 实例化，生成对象。*/
        global $$className;
        if(!class_exists($className)) $this->error("the class $className not found in $classFile", __FILE__, __LINE__, $exit = true);
        if(!is_object($$className)) $$className = new $className();
        return $$className;
    }

    /**
     * 加载配置文件，将其转换为对象，并返回作为全局的配置对象。
     * 
     * 如果模块的名字为common，则从配置的根目录查找，其他的模块则从模块路径下面查找。
     *
     * @param mixed $moduleName     模块的名字。
     * @param bool  $exitIfNone     如果主配置文件不存在，是否退出。
     * @access public
     * @return object
     */
    public function loadConfig($moduleName, $exitIfNone = true)
    {
        /* 设置模块对应的主配置文件和扩展配置文件。*/
        if($moduleName == 'common')
        {
            $mainConfigFile = $this->configRoot . 'config.php';
            $extConfigFiles = array();
        }
        else
        {
            $mainConfigFile = $this->moduleRoot . $moduleName . $this->pathFix . 'config.php';
            $extConfigPath  = $this->getModuleExtPath($moduleName, 'config');
            $extConfigFiles = helper::ls($extConfigPath, '.php');
        }

        /* 主配置文件不存在。*/
        if(!file_exists($mainConfigFile))
        {
            if($exitIfNone) self::error("config file $mainConfigFile not found", __FILE__, __LINE__, true);
            if(empty($extConfigFiles)) return;  // 没有扩展配置文件，退出。
            $configFiles = $extConfigFiles;
        }
        else
        {
            $configFiles = array_merge(array($mainConfigFile), $extConfigFiles);
        }
        
        global $config;
        if(!is_object($config)) $config = new config();
        static $loadedConfigs = array();
        foreach($configFiles as $configFile)
        {
            if(in_array($configFile, $loadedConfigs)) continue;
            include $configFile;
            $loadedConfigs[] = $configFile;
        }
        $this->config = $config;
        return $config;
    }

    /* 将系统的配置参数输出为json格式，方便客户端调用。*/
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
     * 加载语言文件，将其转换为对象，并返回作为全局的语言对象。
     * 
     * @param mixed $moduleName 
     * @access public
     * @return void
     */
    public function loadLang($moduleName)
    {
        $mainLangFile = $this->moduleRoot . $moduleName . $this->pathFix . 'lang' . $this->pathFix . $this->clientLang . '.php';
        $extLangPath  = $this->getModuleExtPath($moduleName, 'lang');
        $extLangFiles = helper::ls($extLangPath . $this->clientLang, '.php');

        /* 主配置文件不存在。*/
        if(!file_exists($mainLangFile))
        {
            if(empty($extLangFiles)) return;  // 没有扩展配置文件，退出。
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
     * 连接到数据库，返回$dbh对象。
     * 
     * @access public
     * @return object
     */
    public function connectDB()
    {
        global $config;
        if(!isset($config->db->driver)) self::error('no pdo driver defined, it should be mysql or sqlite', __FILE__, __LINE__, $exit = true);
        if($config->db->driver == 'mysql')
        {
            $dsn = "mysql:host={$config->db->host}; port={$config->db->port}; dbname={$config->db->name}";
        }    
        try 
        {
            $dbh = new PDO($dsn, $config->db->user, $config->db->password, array(PDO::ATTR_PERSISTENT => $config->db->persistant));
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->exec("SET NAMES {$config->db->encoding}");
            if(isset($config->db->strictMode) and $config->db->strictMode == false) $dbh->exec("SET @@sql_mode= ''");
            $this->dbh = $dbh;
            return $dbh;
        }
        catch (PDOException $exception)
        {
            self::error($exception->getMessage(), __FILE__, __LINE__, $exit = true);
        }
    }
}

/**
 * 配置类。
 * 
 * @package ZenTaoPHP
 */
class config
{ 
    /**
     * 设置对象的值。
     * 
     * <code>
     * <?php
     * $config->set('db.user', 'wwccss'); 
     * ?>
     * </code>
     * @param   string  $key    要设置的属性，可以是father.child的形式。
     * @param   mixed   $value  要设置的值。
     * @access  public
     * @return  void
     */
    public function set($key, $value)
    {
        helper::setMember('config', $key, $value);
    }
}

/**
 * 语言类。
 * 
 * @package ZenTaoPHP
 */
class language 
{
    /**
     * 设置对象的值。
     * 
     * <code>
     * <?php
     * $lang->set('version', '1.0版本'); 
     * ?>
     * </code>
     * @param   string  $key    要设置的属性，可以是father.child的形式。
     * @param   mixed   $value  要设置的值。
     * @access  public
     * @return  void
     */
    public function set($key, $value)
    {
        helper::setMember('lang', $key, $value);
    }

    /**
     * 打印某一个对象的属性。
     */
    public function show($obj, $key)
    {
        $obj = (array)$obj;
        if(isset($obj[$key])) echo $obj[$key]; else echo '';
    }
}

/**
 * 超全局变量类。
 * 
 * @package ZenTaoPHP
 */
class super
{
    /* 构造函数。*/
    public function __construct($scope)
    {
        $this->scope = $scope;
    }

    /* 设置属性。*/
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

    /* 魔术方法。*/
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
}


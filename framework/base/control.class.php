<?php
/**
 * ZenTaoPHP的baseControl类。
 * The baseControl class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * baseControl基类，所有模块的control类都派生于它。
 * The base class of control.
 *
 * @package framework
 */
class baseControl
{
    /**
     * 全局对象 $app。
     * The global $app object.
     * 
     * @var object
     * @access public
     */
    public $app;

    /**
     * 应用名称 $appName
     * The global $appName.
     * 
     * @var string
     * @access public
     */
    public $appName;

    /**
     * 全局对象 $config。
     * The global $config object.
     * 
     * @var object
     * @access public
     */
    public $config;

    /**
     * 全局对象 $lang。
     * The global $lang object.
     * 
     * @var object
     * @access public
     */
    public $lang;

    /**
     * 全局对象 $dbh，数据库连接句柄。
     * The global $dbh object, the database connection handler.
     * 
     * @var object
     * @access public
     */
    public $dbh;

    /**
     * $dao对象，封装SQL语句，方便数据库访问和更新。
     * The $dao object, used to access or update database.
     * 
     * @var object
     * @access public
     */
    public $dao;

    /**
     * $post对象，将$_POST数组改为对象，方便调用。
     * The $post object, used to access the $_POST var.
     * 
     * @var ojbect
     * @access public
     */
    public $post;

    /**
     * $get对象，将$_GET数组改为对象，方便调用。
     * The $get object, used to access the $_GET var.
     * 
     * @var ojbect
     * @access public
     */
    public $get;

    /**
     * $session对象，将$_SESSION数组改为对象，方便调用。
     * The $session object, used to access the $_SESSION var.
     * 
     * @var ojbect
     * @access public
     */
    public $session;

    /**
     * $server对象，将$_SERVER数组改为对象，方便调用。
     * The $server object, used to access the $_SERVER var.
     * 
     * @var ojbect
     * @access public
     */
    public $server;

    /**
     * $cookie对象，将$_COOKIE数组改为对象，方便调用。
     * The $cookie object, used to access the $_COOKIE var.
     * 
     * @var ojbect
     * @access public
     */
    public $cookie;

    /**
     * $global对象，将$_COOKIE数组改为对象，方便调用。
     * The $global object, used to access the $_GLOBAL var.
     * 
     * @var ojbect
     * @access public
     */
    public $global;

    /**
     * 当前模块的名称。
     * The name of current module.
     * 
     * @var string
     * @access public
     */
    public $moduleName;

    /**
     * $view用于存放从control传到view视图的数据。
     * The vars assigned to the view page.
     * 
     * @var object
     * @access public
     */
    public $view; 

    /**
     * 视图的类型，比如html json。
     * The type of the view, such html, json.
     * 
     * @var string
     * @access public
     */
    public $viewType;

    /**
     * 输出到浏览器的内容。
     * The content to display.
     * 
     * @var string
     * @access public
     */
    public $output;

    /**
     * 客户端设备
     * The client device.
     * 
     * @var string   
     * @access public
     */
    public $clientDevice;

    /**
     * 不同设备下视图文件的前缀。
     * The prefix of view file for mobile or PC. 
     * 
     * @var string   
     * @access public
     */
    public $devicePrefix;

    /**
     * 构造方法。 
     * 
     * 1. 将全局变量设为control类的成员变量，方便control的派生类调用； 
     * 2. 设置当前模块，读取该模块的model类；
     * 3. 初始化$view视图类。
     *
     * The construct function.
     *
     * 1. global the global vars, refer them by the class member such as $this->app.
     * 2. set the pathes of current module, and load it's model class.
     * 3. auto assign the $lang and $config to the view.
     * 
     * @param  string $moduleName 
     * @param  string $methodName 
     * @param  string $appName 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        /*
         * 将全局变量设为control类的成员变量，方便control的派生类调用。
         * Global the globals, and refer them to the class member.
         **/
        global $app, $config, $lang, $dbh, $common;
        $this->app      = $app;
        $this->config   = $config;
        $this->lang     = $lang;
        $this->dbh      = $dbh;
        $this->viewType = $this->app->getViewType();
        $this->appName  = $appName ? $appName : $this->app->getAppName();

        /*
         * 设置当前模块，读取该模块的model类。
         * Load the model file auto.
         **/
        $this->setModuleName($moduleName);
        $this->setMethodName($methodName);
        $this->loadModel($this->moduleName, $appName);
        $this->setDevicePrefix();

        /*
         * 初始化$view视图类。
         * Init the view vars.
         **/
        $this->view = new stdclass();
        $this->view->app     = $app;
        $this->view->lang    = $lang;
        $this->view->config  = $config;
        $this->view->common  = $common;
        $this->view->title   = '';

        /*
         * 设置超级变量，从$app引用过来。
         * Set super vars.
         **/
        $this->setSuperVars();
    }

    //-------------------- Model相关方法(Model related methods) --------------------//

    /* 
     * 设置模块名。 
     * Set the module name. 
     * 
     * @param   string  $moduleName  模块名，如果为空，则从$app中获取   The module name, if empty, get it from $app.
     * @access  public
     * @return  void
     */
    public function setModuleName($moduleName = '')
    {
        $this->moduleName = $moduleName ? strtolower($moduleName) : $this->app->getModuleName();
    }

    /* Set the method name.
     * 设置方法名。
     * 
     * @param   string  $methodName   方法名，如果为空，则从$app中获取   The method name, if empty, get it from $app.   
     * @access  public
     * @return  void
     */
    public function setMethodName($methodName = '')
    {
        $this->methodName = $methodName ? strtolower($methodName) : $this->app->getMethodName();
    }

    /**
     * 加载指定模块的model文件。
     * Load the model file of one module.
     * 
     * @param   string  $moduleName 模块名，如果为空，使用当前模块  The module name, if empty, use current module's name.
     * @param   string  $appName    The app name, if empty, use current app's name.
     * @access  public
     * @return  object|bool 如果没有model文件，返回false，否则返回model对象。 If no model file, return false. Else return the model object.
     */
    public function loadModel($moduleName = '', $appName = '')
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        if(empty($appName))    $appName    = $this->appName;
        $modelFile = helper::setModelFile($moduleName, $appName);

        /* 
         * 如果没有model文件，尝试加载config配置信息。
         * If no model file, try load config. 
         */
        if(!helper::import($modelFile)) 
        {
            $this->app->loadConfig($moduleName, $appName, false);
            $this->app->loadLang($moduleName, $appName);
            $this->dao = new dao();
            return false;
        }

        $modelClass = class_exists('ext' . $appName . $moduleName. 'model') ? 'ext' . $appName . $moduleName . 'model' : $appName . $moduleName . 'model';
        if(!class_exists($modelClass))
        {
            $modelClass = class_exists('ext' . $moduleName. 'model') ? 'ext' . $moduleName . 'model' : $moduleName . 'model';
            if(!class_exists($modelClass)) $this->app->triggerError(" The model $modelClass not found", __FILE__, __LINE__, $exit = true);
        }

        $this->$moduleName = new $modelClass($appName);
        $this->dao = $this->$moduleName->dao;
        return $this->$moduleName;
    }

    /**
     * 设置超级全局变量，方便直接引用。
     * Set the super vars.
     * 
     * @access public
     * @return void
     */
    public function setSuperVars()
    {
        $this->post    = $this->app->post;
        $this->get     = $this->app->get;
        $this->server  = $this->app->server;
        $this->session = $this->app->session;
        $this->cookie  = $this->app->cookie;
        $this->global  = $this->app->global;
    }

    /**
     * 为客户端是PC还是移动设备，设置视图文件前缀名。
     * Set the prefix of view file for mobile or PC.
     * 
     * @access public
     * @return void
     */
    public function setDevicePrefix()
    {
        $this->devicePrefix = zget($this->config->devicePrefix, $this->viewType, '');
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
        $this->clientDevice      = helper::getClientDevice();
        $this->app->clientDevice = $this->clientDevice;
    }

    //-------------------- 视图相关方法(View related methods) --------------------//

    /**
     * 设置视图文件，可以获取其他模块的视图文件。
     * Set the view file, thus can use fetch other module's page.
     * 
     * @param  string   $moduleName    module name
     * @param  string   $methodName    method name
     * @access public
     * @return string  the view file
     */
    public function setViewFile($moduleName, $methodName)
    {
        $moduleName = strtolower(trim($moduleName));
        $methodName = strtolower(trim($methodName));

        $modulePath  = $this->app->getModulePath($this->appName, $moduleName);
        $viewExtPath = $this->app->getModuleExtPath($this->appName, $moduleName, 'view');

        /* Set viewType to html when it is mhtml. */
        $viewType = $this->viewType == 'mhtml' ? 'html' : $this->viewType;

        /*
         * 主视图文件，扩展视图文件和钩子文件。
         * The main view file, extension view file and hook file.
         **/
        $mainViewFile = $modulePath . 'view' . DS . $this->devicePrefix . $methodName . '.' . $viewType . '.php';

        /* Extension view file. */
        $commonExtViewFile = $viewExtPath['common'] . $this->devicePrefix . $methodName . ".{$viewType}.php";
        $siteExtViewFile   = empty($viewExtPath['site']) ? '' : $viewExtPath['site'] . $this->devicePrefix . $methodName . ".{$viewType}.php";

        $viewFile = file_exists($commonExtViewFile) ? $commonExtViewFile : $mainViewFile;
        $viewFile = (!empty($siteExtViewFile) and file_exists($siteExtViewFile)) ? $siteExtViewFile : $viewFile;
        if(!is_file($viewFile)) $this->app->triggerError("the view file $viewFile not found", __FILE__, __LINE__, $exit = true);

        /* Extension hook file. */
        $commonExtHookFiles = glob($viewExtPath['common'] . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");
        $siteExtHookFiles   = empty($viewExtPath['site']) ? '' : glob($viewExtPath['site'] . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");
        $extHookFiles       = array_merge((array) $commonExtHookFiles, (array) $siteExtHookFiles);
        if(!empty($extHookFiles)) return array('viewFile' => $viewFile, 'hookFiles' => $extHookFiles);
        return $viewFile;
    }

    /**
     * 获取视图的扩展文件，在ext/view/目录下
     * Get the extension file of an view.
     * 
     * @param  string $viewFile 
     * @access public
     * @return string|bool  If extension view file exists, return the path. Else return fasle.
     */
    public function getExtViewFile($viewFile)
    {
        /**
         * 首先找sitecode下的扩展文件，如果没有，再找ext下的扩展文件。 
         * Find extViewFile in ext/_$siteCode/view first, then try ext/view/.
         */
        if($this->config->site->code)
        {
            $extPath     = dirname(dirname(realpath($viewFile))) . "/ext/_{$this->config->site->code}/view";
            $extViewFile = $extPath . basename($viewFile);

            if(file_exists($extViewFile))
            {
                helper::cd($extPath);
                return $extViewFile;
            }
        }

        $extPath = dirname(dirname(realpath($viewFile))) . '/ext/view/';
        $extViewFile = $extPath . basename($viewFile);
        if(file_exists($extViewFile))
        {
            helper::cd($extPath);
            return $extViewFile;
        }
        return false;
    }

    /**
     * 获取方法的css内容，common.css + 该方法的css。 
     * Get css code for a method. 
     * 
     * @param  string    $moduleName 
     * @param  string    $methodName 
     * @access public
     * @return string
     */
    public function getCSS($moduleName, $methodName)
    {
        $moduleName   = strtolower(trim($moduleName));
        $methodName   = strtolower(trim($methodName));

        $modulePath   = $this->app->getModulePath($this->appName, $moduleName);
        $cssExtPath   = $this->app->getModuleExtPath($this->appName, $moduleName, 'css') ;
        $cssMethodExt = $cssExtPath['common'] . $methodName . DS;
        $cssCommonExt = $cssExtPath['common'] . 'common' . DS;

        $css = '';
        $mainCssFile   = $modulePath . 'css' . DS . $this->devicePrefix . 'common.css';
        $methodCssFile = $modulePath . 'css' . DS . $this->devicePrefix . $methodName . '.css';
        if(file_exists($mainCssFile)) $css .= file_get_contents($mainCssFile);
        if(is_file($methodCssFile))   $css .= file_get_contents($methodCssFile);

        $cssExtFiles = glob($cssCommonExt . $this->devicePrefix . '*.css');
        if(!empty($cssExtFiles) and is_array($cssExtFiles))
        {
            foreach($cssExtFiles as $cssFile) $css .= file_get_contents($cssFile);
        }

        $cssExtFiles = glob($cssMethodExt . $this->devicePrefix . '*.css');
        if(!empty($cssExtFiles) and is_array($cssExtFiles))
        {
            foreach($cssExtFiles as $cssFile) $css .= file_get_contents($cssFile);
        }
        if(!empty($cssExtPath['site']))
        {
            $cssMethodExt = $cssExtPath['site'] . $methodName . DS;
            $cssCommonExt = $cssExtPath['site'] . 'common' . DS;
            $cssExtFiles = glob($cssCommonExt . $this->devicePrefix . '*.css');
            if(!empty($cssExtFiles) and is_array($cssExtFiles))
            {
                foreach($cssExtFiles as $cssFile) $css .= file_get_contents($cssFile);
            }

            $cssExtFiles = glob($cssMethodExt . $this->devicePrefix . '*.css');
            if(!empty($cssExtFiles) and is_array($cssExtFiles))
            {
                foreach($cssExtFiles as $cssFile) $css .= file_get_contents($cssFile);
            }
        }
        return $css;
    }

    /**
     * 获取方法的js，common.js + 该方法的js。
     * Get js code for a method. 
     * 
     * @param  string    $moduleName 
     * @param  string    $methodName 
     * @access public
     * @return string
     */
    public function getJS($moduleName, $methodName)
    {
        $moduleName  = strtolower(trim($moduleName));
        $methodName  = strtolower(trim($methodName));

        $modulePath  = $this->app->getModulePath($this->appName, $moduleName);
        $jsExtPath   = $this->app->getModuleExtPath($this->appName, $moduleName, 'js');
        $jsMethodExt = $jsExtPath['common'] . $methodName . DS;
        $jsCommonExt = $jsExtPath['common'] . 'common' . DS;

        $js = '';
        $mainJsFile   = $modulePath . 'js' . DS . $this->devicePrefix . 'common.js';
        $methodJsFile = $modulePath . 'js' . DS . $this->devicePrefix . $methodName . '.js';
        if(file_exists($mainJsFile))   $js .= file_get_contents($mainJsFile);
        if(is_file($methodJsFile))     $js .= file_get_contents($methodJsFile);

        $jsExtFiles = glob($jsCommonExt . $this->devicePrefix . '*.js');
        if(!empty($jsExtFiles) and is_array($jsExtFiles))
        {
            foreach($jsExtFiles as $jsFile) $js .= file_get_contents($jsFile);
        }

        $jsExtFiles = glob($jsMethodExt . $this->devicePrefix . '*.js');
        if(!empty($jsExtFiles) and is_array($jsExtFiles))
        {
            foreach($jsExtFiles as $jsFile) $js .= file_get_contents($jsFile);
        }

        if(!empty($jsExtPath['site']))
        {
            $jsMethodExt = $jsExtPath['site'] . $methodName . DS;
            $jsCommonExt = $jsExtPath['site'] . 'common' . DS;

            $jsExtFiles = glob($jsCommonExt . $this->devicePrefix . '*.js');
            if(!empty($jsExtFiles) and is_array($jsExtFiles))
            {
                foreach($jsExtFiles as $jsFile) $js .= file_get_contents($jsFile);
            }

            $jsExtFiles = glob($jsMethodExt . $this->devicePrefix . '*.js');
            if(!empty($jsExtFiles) and is_array($jsExtFiles))
            {
                foreach($jsExtFiles as $jsFile) $js .= file_get_contents($jsFile);
            }
        }
        return $js;
    }

    /**
     * 向$view传递一个变量。
     * Assign one var to the view vars.
     * 
     * @param   string  $name       the name.
     * @param   mixed   $value      the value.
     * @access  public
     * @return  void
     */
    public function assign($name, $value)
    {
        $this->view->$name = $value;
    }

    /**
     * 将之前打算输出的内容清空。
     * Clear the output.
     *
     * @access public
     * @return void
     */
    public function clear()
    {
        $this->output = '';
    }

    /**
     * 根据请求的视图类型，生成输出内容。
     * Parse view file. 
     *
     * @param  string $moduleName    module name, if empty, use current module.
     * @param  string $methodName    method name, if empty, use current method.
     * @access public
     * @return string the parsed result.
     */
    public function parse($moduleName = '', $methodName = '')
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        if(empty($methodName)) $methodName = $this->methodName;

        if($this->viewType == 'json')
        {
            $this->parseJSON($moduleName, $methodName);
        }
        else
        {
            $this->parseDefault($moduleName, $methodName);
        }
        return $this->output;
    }

    /**
     * 请求为json格式的处理逻辑。 
     * Parse json format.
     *
     * @param string $moduleName    module name
     * @param string $methodName    method name
     * @access public
     * @return void
     */
    public function parseJSON($moduleName, $methodName)
    {
        unset($this->view->app);
        unset($this->view->config);
        unset($this->view->lang);
        unset($this->view->header);
        unset($this->view->position);
        unset($this->view->moduleTree);

        $output['status'] = is_object($this->view) ? 'success' : 'fail';
        $output['data']   = json_encode($this->view);
        $output['md5']    = md5(json_encode($this->view));
        $this->output     = json_encode($output);
    }

    /**
     * 其他请求格式的处理逻辑，输出视图文件的内容。
     * Parse default html format.
     *
     * @param string $moduleName    module name
     * @param string $methodName    method name
     * @access public
     * @return void
     */
    public function parseDefault($moduleName, $methodName)
    {
        /* Set the view file. Fix it for php7. */
        $results  = $this->setViewFile($moduleName, $methodName);
        $viewFile = $results;
        if(is_array($results)) extract($results);

        /* Get css and js. */
        $css = $this->getCSS($moduleName, $methodName);
        $js  = $this->getJS($moduleName, $methodName);
        if($css) $this->view->pageCSS = $css;
        if($js)  $this->view->pageJS  = $js;

        /* Change the dir to the view file to keep the relative pathes work. */
        $currentPWD = getcwd();
        chdir(dirname($viewFile));

        extract((array)$this->view);
        ob_start();
        include $viewFile;
        if(isset($hookFiles)) foreach($hookFiles as $hookFile) if(file_exists($hookFile)) include $hookFile;
        $this->output .= ob_get_contents();
        ob_end_clean();

        /* At the end, chang the dir to the previous. */
        chdir($currentPWD);
    }

    /**
     * 获取一个方法的输出内容，这样我们可以在一个方法里获取其他模块方法的内容。
     * 如果模块名为空，则调用该模块、该方法；如果设置了模块名，调用指定模块指定方法。
     *
     * Get the output of one module's one method as a string, thus in one module's method, can fetch other module's content.
     * If the module name is empty, then use the current module and method. If set, use the user defined module and method.
     *
     * @param   string  $moduleName    module name.
     * @param   string  $methodName    method name.
     * @param   array   $params        params.
     * @access  public
     * @return  string  the parsed html.
     */
    public function fetch($moduleName = '', $methodName = '', $params = array(), $appName = '')
    {
        if($moduleName == '') $moduleName = $this->moduleName;
        if($methodName == '') $methodName = $this->methodName;
        if($appName == '')    $appName    = $this->appName;
        if($moduleName == $this->moduleName and $methodName == $this->methodName) 
        {
            $this->parse($moduleName, $methodName);
            return $this->output;
        }

        /*
         * 设置引用的文件和路径。
         * Set the pathes and files to included.
         **/
        $modulePath        = $this->app->getModulePath($appName, $moduleName);
        $moduleControlFile = $modulePath . 'control.php';
        $actionExtPath     = $this->app->getModuleExtPath($appName, $moduleName, 'control');

        $commonActionExtFile = $actionExtPath['common'] . strtolower($methodName) . '.php';
        $file2Included       = file_exists($commonActionExtFile) ? $commonActionExtFile : $moduleControlFile;
        if(!empty($actionExtPath['site']))
        {
            $siteActionExtFile = $actionExtPath['site'] . strtolower($methodName) . '.php';
            $file2Included     = file_exists($siteActionExtFile) ? $siteActionExtFile : $file2Included;
        }

        /* 加载控制器文件。 */
        /* Load the control file. */
        if(!is_file($file2Included)) $this->app->triggerError("The control file $file2Included not found", __FILE__, __LINE__, $exit = true);
        $currentPWD = getcwd();
        chdir(dirname($file2Included));
        if($moduleName != $this->moduleName) helper::import($file2Included);

        /* 设置调用的类名。 */
        /* Set the name of the class to be called. */
        $className = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
        if(!class_exists($className)) $this->app->triggerError(" The class $className not found", __FILE__, __LINE__, $exit = true);

        /* 解析参数，创建模块control对象。 */
        /* Parse the params, create the $module control object. */
        if(!is_array($params)) parse_str($params, $params);
        $module = new $className($moduleName, $methodName, $appName);

        /* 调用对应方法，使用ob方法获取输出内容。 */
        /* Call the method and use ob function to get the output. */
        ob_start();
        call_user_func_array(array($module, $methodName), $params);
        $output = ob_get_contents();
        ob_end_clean();

        /* 返回内容。 */
        /* Return the content. */
        unset($module);
        chdir($currentPWD);
        return $output;
    }

    /**
     * 向浏览器输出内容。
     * Print the content of the view. 
     *
     * @param   string  $moduleName    module name
     * @param   string  $methodName    method name
     * @access  public
     * @return  void
     */
    public function display($moduleName = '', $methodName = '')
    {
        if(empty($this->output)) $this->parse($moduleName, $methodName);
        echo $this->output;
    }
    /** 
     * 直接输出data数据，通常用于ajax请求中。
     * Send data directly, for ajax requests.
     *
     * @param  misc    $data 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function send($data, $type = 'json')
    {
        if($type != 'json') die();

        $data = (array) $data;
        if(helper::isAjaxRequest()) print(json_encode($data)) and die(helper::removeUTF8Bom(ob_get_clean()));

        /**
         * 响应非ajax的请求。
         * Response request not ajax. 
         **/

        if(isset($data['result']) and $data['result'] == 'success')
        {
            if(!empty($data['message'])) echo js::alert($data['message']);
            $locate = isset($data['locate']) ? $data['locate'] : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
            if(!empty($locate)) die(js::locate($locate));
            die(isset($data['message']) ? $data['message'] : 'success');
        }

        if(isset($data['result']) and $data['result'] == 'fail')
        {
            if(!empty($data['message']))
            {
                $message = json_decode(json_encode((array)$data['message']));
                foreach((array)$message as $item => $errors) $message->$item = implode(',', $errors);
                echo js::alert(strip_tags(implode(" ", (array) $message)));
                die(js::locate('back'));
            }
            die('fail');
        }
    }

    /**
     * 创建一个模块方法的链接。
     * Create a link to one method of one module.
     *
     * @param   string         $moduleName    module name
     * @param   string         $methodName    method name
     * @param   string|array   $vars          the params passed, can be array(key=>value) or key1=value1&key2=value2
     * @param   string         $viewType      the view type
     * @access  public
     * @return  string the link string.
     */
    public function createLink($moduleName, $methodName = 'index', $vars = array(), $viewType = '', $onlybody = false)
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        return helper::createLink($moduleName, $methodName, $vars, $viewType, $onlybody);
    }

    /**
     * 创建当前模块的一个方法链接。
     * Create a link to the inner method of current module.
     * 
     * @param   string         $methodName    method name
     * @param   string|array   $vars          the params passed, can be array(key=>value) or key1=value1&key2=value2
     * @param   string         $viewType      the view type
     * @access  public
     * @return  string  the link string.
     */
    public function inlink($methodName = 'index', $vars = array(), $viewType = '', $onlybody = false)
    {
        return helper::createLink($this->moduleName, $methodName, $vars, $viewType, $onlybody);
    }

    /**
     * 重定向到另一个页面。
     * Location to another page.
     * 
     * @param   string   $url   the target url.
     * @access  public
     * @return  void
     */
    public function locate($url)
    {
        header("location: $url");
        exit;
    }
}

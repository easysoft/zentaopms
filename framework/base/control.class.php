<?php
declare(strict_types=1);
/**
 * ZenTaoPHP的baseControl类。
 * The baseControl class file of ZenTaoPHP framework.
 *
 * @package framework
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
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
     * $dao对象，实现sql的拼装和执行。
     * The $dao object, used to join sql and excute sql.
     *
     * @var dao
     * @access public
     */
    public $dao;

    /**
     * $post对象，用户可以通过$this->post->key来引用$_POST变量。
     * The $post object, useer can access a post var by $this->post->key.
     *
     * @var object
     * @access public
     */
    public $post;

    /**
     * $get对象，用户可以通过$this->get->key来引用$_GET变量。
     * The $get object, useer can access a get var by $this->get->key.
     *
     * @var object
     * @access public
     */
    public $get;

    /**
     * $session对象，用户可以通过$this->session->key来引用$_SESSION变量。
     * The $session object, useer can access a session var by $this->session->key.
     *
     * @var object
     * @access public
     */
    public $session;

    /**
     * $server对象，用户可以通过$this->server->key来引用$_SERVER变量。
     * The $server object, useer can access a server var by $this->server->key.
     *
     * @var object
     * @access public
     */
    public $server;

    /**
     * $cookie对象，用户可以通过$this->cookie->key来引用$_COOKIE变量。
     * The $cookie object, useer can access a cookie var by $this->cookie->key.
     *
     * @var object
     * @access public
     */
    public $cookie;

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
     * 视图的类型，比如html, json。
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
     * 客户端设备。
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
     * 1. 将全局变量设为baseControl类的成员变量，方便baseControl的派生类调用；
     * 2. 设置当前模块，读取该模块的model类；
     * 3. 初始化$view视图类。
     *
     * The construct function.
     *
     * 1. global the global vars, refer them by the class member such as $this->app.
     * 2. set the paths of current module, and load it's model class.
     * 3. auto assign the $lang and $config to the view.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $appName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '', string $appName = '')
    {
        /*
         * 将全局变量设为baseControl类的成员变量，方便baseControl的派生类调用。
         * Global the globals, and refer them as a class member.
         */
        global $app, $config, $lang, $dbh, $common;
        $this->app      = $app;
        $this->config   = $config;
        $this->lang     = $lang;
        $this->dbh      = $dbh;
        $this->viewType = $this->app->getViewType();
        $this->appName  = $appName ?: $this->app->getAppName();

        /**
         * 设置当前模块，读取该模块的model类。
         * Load the model file auto.
         */
        $this->setModuleName($moduleName);
        $this->setMethodName($methodName);
        $this->loadModel($this->moduleName, $appName);

        /**
         * 检查用户是否登录，如果没有登录，跳转到登录页面。
         * Check the user has logon or not, if not, goto the login page.
         */
        if($this->config->installed && !in_array($this->moduleName, $this->config->openModules) && empty($this->app->user) && !$this->loadModel('common')->isOpenMethod($this->moduleName, $this->methodName))
        {
            $uri = $this->app->getURI(true);
            if($this->moduleName == 'message' and $this->methodName == 'ajaxgetmessage')
            {
                $uri = helper::createLink('my');
            }
            elseif(helper::isAjaxRequest())
            {
                helper::end(json_encode(array('result' => false, 'message' => $this->lang->error->loginTimeout)));
            }

            $referer = helper::safe64Encode($uri);
            helper::end(js::locate(helper::createLink('user', 'login', "referer=$referer")));
        }

        /**
         * 如果客户端是手机的话，视图文件增加m.前缀。
         * If the client is mobile, add m. as prefix for view file.
         */
        $this->setClientDevice();
        $this->setDevicePrefix();

        /**
         * 初始化$view视图类。
         * Init the view vars.
         */
        $this->view         = new stdclass();
        $this->view->app    = $app;
        $this->view->lang   = $lang;
        $this->view->config = $config;
        $this->view->common = $common;
        $this->view->title  = '';

        /**
         * 设置超级变量，从$app引用过来。
         * Set super vars.
         */
        $this->setSuperVars();

        /**
         * 读取当前模块的zen类。
         * Load the zen file auto.
         */
        $zenClass      = $this->moduleName . 'Zen';
        $selfClass     = static::class;
        $parentClasses = class_parents($this);
        if($selfClass != $zenClass && !isset($parentClasses[$zenClass])) $this->loadZen($this->moduleName, $appName);
    }

    //-------------------- Model相关方法(Model related methods) --------------------//

    /*
     * 设置模块名。
     * Set the module name.
     *
     * @param   string  $moduleName  模块名，如果为空，则从$app中获取. The module name, if empty, get it from $app.
     * @access  public
     * @return  void
     */
    public function setModuleName(string $moduleName = '')
    {
        $this->moduleName = $moduleName ? strtolower((string) $moduleName) : $this->app->getModuleName();
    }

    /**
     * 设置方法名。
     * Set the method name.
     *
     * @param  string $methodName 方法名，如果为空，则从$app中获取。The method name, if empty, get it from $app.
     * @access  public
     * @return  void
     */
    public function setMethodName(string $methodName = '')
    {
        $this->methodName = $methodName ? strtolower($methodName) : $this->app->getMethodName();
    }

    /**
     * 加载一个模块的model对象。加载完成后，使用$this->$moduleName来访问这个model对象。
     * 比如：loadModel('user')引入user模块的model实例对象，可以通过$this->user来访问它。
     *
     * Load the model object of one module. After loaded, can use $this->$moduleName to visit the model object.
     *
     * @param  string $moduleName 模块名，如果为空，使用当前模块。The module name, if empty, use current module's name.
     * @param  string $appName    应用名，如果为空，使用当前应用。The app name, if empty, use current app's name.
     * @access public
     * @return object|bool 如果没有model文件，返回false，否则返回model对象。If no model file, return false, else return the model object.
     */
    public function loadModel(string $moduleName = '', string $appName = ''): object|bool
    {
        $model = $this->app->loadTarget($moduleName, $appName);

        /**
         * 如果加载model失败，尝试加载config, lang配置信息。
         * If model is not loaded, try load config and lang.
         */
        if(!$model)
        {
            $this->app->loadModuleConfig($moduleName, $appName);
            $this->app->loadLang($moduleName, $appName);
            $this->dao = new dao();
            return false;
        }

        $this->{$moduleName} = $model;
        $this->dao           = $model->dao;
        $this->cache         = $model->cache;
        return $model;
    }

    /**
     * 加载一个模块的zen对象。加载完成后，使用$this->{$moduleName}Zen来访问这个zen对象。
     * 比如：loadZen('user')引入user模块的zen实例对象，可以通过$this->userZen来访问它。
     *
     * Load the zen object of one module. After loaded, can use $this->{$moduleName}Zen to visit the zen object.
     *
     * @param  string $moduleName 模块名，如果为空，使用当前模块。The module name, if empty, use current module's name.
     * @param  string $appName    应用名，如果为空，使用当前应用。The app name, if empty, use current app's name.
     * @access public
     * @return object|bool 如果没有zen文件，返回false，否则返回zen对象。If no zen file, return false, else return the zen object.
     */
    public function loadZen(string $moduleName = '', string $appName = ''): object|bool
    {
        $zen = $this->app->loadTarget($moduleName, $appName, 'zen');
        if(!$zen) return false;

        $zen->view = $this->view;

        $zenObjectName = $moduleName . 'Zen';
        $this->{$zenObjectName} = $zen;
        return $zen;
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
    }

    /**
     * 设置客户端的设备类型。
     * Set client device.
     *
     * @access public
     * @return void
     */
    public function setClientDevice()
    {
        $this->clientDevice = $this->app->clientDevice;
    }

    /**
     * 如果客户端是手机的话，视图文件增加m.前缀。
     * If the client is mobile, add m. as prefix for view file.
     *
     * @access public
     * @return void
     */
    public function setDevicePrefix()
    {
        $this->devicePrefix = zget($this->config->devicePrefix, $this->viewType, '');
    }

    //-------------------- 视图相关方法(View related methods) --------------------//

    /**
     * 设置视图文件：主视图文件，扩展视图文件， 站点扩展视图文件，以及钩子脚本。
     * Set view files: the main file, extension view file, site extension view file and hook files.
     *
     * @param  string $moduleName module name
     * @param  string $methodName method name
     * @param  string $viewDir
     * @access public
     * @return string  the view file
     */
    public function setViewFile(string $moduleName, string $methodName, string $viewDir = 'view')
    {
        $moduleName = strtolower(trim($moduleName));
        $methodName = strtolower(trim($methodName));

        $modulePath  = $this->app->getModulePath($this->appName, $moduleName);
        $viewExtPath = $this->app->getModuleExtPath($moduleName, $viewDir);

        $viewType     = $this->viewType == 'mhtml' ? 'html' : $this->viewType;
        $mainViewFile = $modulePath . $viewDir . DS . $this->devicePrefix . $methodName . '.' . $viewType . '.php';
        $viewFile     = $mainViewFile;

        if(!empty($viewExtPath))
        {
            $commonExtViewFile = $viewExtPath['common'] . $this->devicePrefix . $methodName . ".{$viewType}.php";
            $siteExtViewFile   = empty($viewExtPath['site']) ? '' : $viewExtPath['site'] . $this->devicePrefix . $methodName . ".{$viewType}.php";

            $viewFile = file_exists($commonExtViewFile) ? $commonExtViewFile : $mainViewFile;
            $viewFile = (!empty($siteExtViewFile) and file_exists($siteExtViewFile)) ? $siteExtViewFile : $viewFile;
            if(!is_file($viewFile)) $this->app->triggerError("the view file $viewFile not found", __FILE__, __LINE__, true);

            $commonExtHookFiles = glob($viewExtPath['common'] . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");
            $siteExtHookFiles   = empty($viewExtPath['site']) ? '' : glob($viewExtPath['site'] . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");
            $extHookFiles       = array_merge((array)$commonExtHookFiles, (array)$siteExtHookFiles);
        }

        if(!empty($extHookFiles)) return array('viewFile' => $viewFile, 'hookFiles' => $extHookFiles);
        return $viewFile;
    }

    /**
     * 获取某一个视图文件的扩展。
     * Get the extension file of an view.
     *
     * @param  string $viewFile
     * @access public
     * @return string|bool  If extension view file exists, return the path. Else return fasle.
     */
    public function getExtViewFile(string $viewFile): string|bool
    {
        /**
         * 首先找sitecode下的扩展文件，如果没有，再找ext下的扩展文件。
         * Find extViewFile in ext/_$siteCode/view first, then try ext/view/.
         */
        $moduleName = basename(dirname(realpath($viewFile), 2));
        $extPath    = $this->app->getModuleExtPath($moduleName, 'view');

        $checkedOrder = array('site', 'saas', 'custom', 'vision', 'xuan', 'common');
        $fileName     = basename($viewFile);
        foreach($checkedOrder as $checkedType)
        {
            if(!empty($extPath[$checkedType]))
            {
                $extViewFile = $extPath[$checkedType] . $fileName;
                if(file_exists($extViewFile))
                {
                    helper::cd($extPath[$checkedType]);
                    return $extViewFile;
                }
            }
        }

        return false;
    }

    /**
     * 获取适用于当前方法的css：该模块公用的css + 当前方法的css + 扩展的css。
     * Get css codes applied to current method: module common css + method css + extension css.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $suffix
     * @access public
     * @return string
     */
    public function getCSS(string $moduleName, string $methodName, string $suffix = ''): string
    {
        $moduleName = strtolower(trim($moduleName));
        $methodName = strtolower(trim($methodName));

        $modulePath = $this->app->getModulePath($this->appName, $moduleName);
        $cssExtPath = $this->app->getModuleExtPath($moduleName, 'css');

        $clientLang = $this->app->getClientLang();
        $notCNLang  = !str_contains('|zh-cn|zh-tw|', "|{$clientLang}|");

        $css          = '';
        $devicePrefix = $this->devicePrefix;
        $mainCssPath  = $modulePath . 'css' . DS;

        /* Common css file. like module/story/css/common.css. */
        $mainCssFile = $mainCssPath . $devicePrefix . "common{$suffix}.css";
        if(is_file($mainCssFile)) $css .= file_get_contents($mainCssFile);

        /* Common css file with lang. like module/story/css/common.en.css. */
        $mainCssLangFile = $mainCssPath . $devicePrefix . "common.{$clientLang}{$suffix}.css";
        if(!file_exists($mainCssLangFile) and $notCNLang) $mainCssLangFile = $mainCssPath . $devicePrefix . "common{$suffix}.en.css";
        if(is_file($mainCssLangFile)) $css .= file_get_contents($mainCssLangFile);

        /* Method css file. like module/story/css/create.css. */
        $methodCssFile = $mainCssPath . $devicePrefix . $methodName . "$suffix.css";
        if(is_file($methodCssFile)) $css .= file_get_contents($methodCssFile);

        /* Method css file with lang. like module/story/css/create.en.css. */
        $methodCssLangFile = $mainCssPath . $devicePrefix . "{$methodName}{$suffix}.{$clientLang}.css";
        if(!file_exists($methodCssLangFile) and $notCNLang) $methodCssLangFile = $mainCssPath . $devicePrefix . "{$methodName}{$suffix}.en.css";
        if(is_file($methodCssLangFile)) $css .= file_get_contents($methodCssLangFile);

        if(!empty($cssExtPath))
        {
            foreach($cssExtPath as $cssPath)
            {
                if(empty($cssPath)) continue;

                $cssMethodExt = $cssPath . $methodName . DS;
                $cssCommonExt = $cssPath . 'common' . DS;

                $cssExtFiles = glob($cssCommonExt . $devicePrefix . "*{$suffix}.css");
                if(!empty($cssExtFiles) and is_array($cssExtFiles)) $css .= $this->getExtCSS($cssExtFiles, $suffix);

                $cssExtFiles = glob($cssMethodExt . $devicePrefix . "*{$suffix}.css");
                if(!empty($cssExtFiles) and is_array($cssExtFiles)) $css .= $this->getExtCSS($cssExtFiles, $suffix);
            }
        }

        return $css;
    }

    /**
     * Get extension css and extension css with lang.
     *
     * @param  array  $files
     * @param  string $suffix
     * @access public
     * @return string
     */
    public function getExtCSS(array $files, string $suffix = ''): string
    {
        $clientLang = $this->app->getClientLang();
        $notCNLang  = !str_contains('|zh-cn|zh-tw|', "|{$clientLang}|");

        $filePairs = array();
        foreach($files as $cssFile)
        {
            $fileName             = basename((string) $cssFile);
            $filePairs[$fileName] = $cssFile;
        }

        $css       = '';
        $usedCodes = array();
        foreach($filePairs as $fileName => $cssFile)
        {
            if(preg_match('/^\w+\.css$/', $fileName))
            {
                /* Method extension css file. like module/story/ext/css/create/effort.css. */
                $css .= file_get_contents($cssFile);
                [$code] = explode('.', $fileName);
            }
            else
            {
                [$code] = explode('.', $fileName);
                if(isset($usedCodes[$code])) continue;
            }

            /* Method extension css file. like module/story/ext/css/create/effort.zh-cn.css. */
            if(isset($filePairs["{$code}{$suffix}.css"]))                   $css .= file_get_contents($filePairs["{$code}{$suffix}.css"]);
            if(isset($filePairs["{$code}.{$clientLang}{$suffix}.css"]))     $css .= file_get_contents($filePairs["{$code}.{$clientLang}{$suffix}.css"]);
            if($notCNLang and isset($filePairs["{$code}.en{$suffix}.css"])) $css .= file_get_contents($filePairs["{$code}.en{$suffix}.css"]);
            $usedCodes[$code] = $code;
        }

        return $css;
    }

    /**
     * 获取适用于当前方法的js：该模块公用的js + 当前方法的js + 扩展的js。
     * Get js codes applied to current method: module common js + method js + extension js.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return string
     */
    public function getJS(string $moduleName, string $methodName, string $suffix = ''): string
    {
        $moduleName = strtolower(trim($moduleName));
        $methodName = strtolower(trim($methodName));

        $modulePath = $this->app->getModulePath($this->appName, $moduleName);
        $jsExtPath  = $this->app->getModuleExtPath($moduleName, 'js');

        $js           = '';
        $mainJsFile   = $modulePath . 'js' . DS . $this->devicePrefix . "common{$suffix}.js";
        $methodJsFile = $modulePath . 'js' . DS . $this->devicePrefix . $methodName . $suffix . '.js';
        if(file_exists($mainJsFile)) $js .= file_get_contents($mainJsFile);
        if(is_file($methodJsFile)) $js .= file_get_contents($methodJsFile);

        if(!empty($jsExtPath))
        {
            foreach($jsExtPath as $jsPath)
            {
                if(empty($jsPath)) continue;

                $jsMethodExt = $jsPath . $methodName . DS;
                $jsCommonExt = $jsPath . 'common' . DS;

                $jsExtFiles = glob($jsCommonExt . $this->devicePrefix . "*{$suffix}.js");
                if(!empty($jsExtFiles) and is_array($jsExtFiles)) foreach($jsExtFiles as $jsFile) $js .= file_get_contents($jsFile);

                $jsExtFiles = glob($jsMethodExt . $this->devicePrefix . "*{$suffix}.js");
                if(!empty($jsExtFiles) and is_array($jsExtFiles)) foreach($jsExtFiles as $jsFile) $js .= file_get_contents($jsFile);
            }
        }

        return $js;
    }

    /**
     * 向$view传递一个变量。
     * Assign one var to the view vars.
     *
     * @param  string $name  the name.
     * @param  mixed  $value the value.
     * @access  public
     * @return  void
     */
    public function assign(string $name, $value)
    {
        $this->view->$name = $value;
    }

    /**
     * 清空$output。
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
     * 渲染视图文件。
     * Parse view file.
     *
     * @param  string $moduleName module name, if empty, use current module.
     * @param  string $methodName method name, if empty, use current method.
     * @access public
     * @return string the parsed result.
     */
    public function parse(string $moduleName = '', string $methodName = ''): string
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        if(empty($methodName)) $methodName = $this->methodName;

        if($this->viewType == 'json') $this->parseJSON($moduleName, $methodName);
        if($this->viewType != 'json') $this->parseDefault($moduleName, $methodName);

        return $this->output;
    }

    /**
     * 渲染json格式。
     * Parse json format.
     *
     * @param  string $moduleName module name
     * @param  string $methodName method name
     * @access public
     * @return void
     */
    public function parseJSON(string $moduleName, string $methodName)
    {
        $output = array();
        unset($this->view->app);
        unset($this->view->config);
        unset($this->view->lang);
        unset($this->view->header);
        unset($this->view->position);
        unset($this->view->moduleTree);
        unset($this->view->common);
        unset($this->view->pager->app);
        unset($this->view->pager->lang);

        $output['status'] = is_object($this->view) ? 'success' : 'fail';
        $output['data']   = json_encode($this->view);
        $output['md5']    = md5(json_encode($this->view));

        $this->output = json_encode($output);
    }

    /**
     * 默认渲染方法，适用于viewType = html的时候。
     * Default parse method when viewType != json, like html.
     *
     * @param  string $moduleName module name
     * @param  string $methodName method name
     * @access public
     * @return void
     */
    public function parseDefault(string $moduleName, string $methodName)
    {
        /**
         * 设置视图文件。(PHP7有一个bug，不能直接$viewFile = $this->setViewFile())。
         * Set viewFile. (Can't assign $viewFile = $this->setViewFile() directly because one php7's bug.)
         */
        $results = $this->setViewFile($moduleName, $methodName);

        $viewFile = $results;
        if(is_array($results)) extract($results);

        /**
         * 获得当前页面的CSS和JS。
         * Get css and js codes for current method.
         */
        $css = $this->getCSS($moduleName, $methodName);
        $js  = $this->getJS($moduleName, $methodName);
        if($css) $this->view->pageCSS = $css;
        if($js) $this->view->pageJS = $js;

        /**
         * 切换到视图文件所在的目录，以保证视图文件里面的include语句能够正常运行。
         * Change the dir to the view file to keep the relative paths work.
         */
        $currentPWD = getcwd();
        chdir(dirname($viewFile));

        /**
         * 使用extract和ob方法渲染$viewFile里面的代码。
         * Use extract and ob functions to eval the codes in $viewFile.
         */
        extract((array)$this->view);
        ob_start();
        include $viewFile;
        if(isset($hookFiles)) foreach($hookFiles as $hookFile) if(file_exists($hookFile)) include $hookFile;
        $this->output .= ob_get_contents();
        ob_end_clean();

        /**
         * 渲染完毕后，再切换回之前的路径。
         * At the end, chang the dir to the previous.
         */
        chdir($currentPWD);
    }

    /**
     * 获取一个方法的输出内容，这样我们可以在一个方法里获取其他模块方法的内容。
     * 如果模块名为空，则调用该模块、该方法；如果设置了模块名，调用指定模块指定方法。
     *
     * Get the output of one module's one method as a string, thus in one module's method, can fetch other module's content.
     * If the module name is empty, then use the current module and method. If set, use the user defined module and method.
     *
     * @param  string        $moduleName module name.
     * @param  string        $methodName method name.
     * @param  array|string  $params     params.
     * @access  public
     * @return  string  the parsed html.
     */
    public function fetch(string $moduleName = '', string $methodName = '', array|string $params = array(), string $appName = '')
    {
        /**
         * 如果模块名为空，则调用该模块、该方法。
         * If the module name is empty, then use the current module and method.
         */
        if($moduleName == '') $moduleName = $this->moduleName;
        if($methodName == '') $methodName = $this->methodName;
        if($appName == '') $appName = $this->appName;
        if($moduleName == $this->moduleName and $methodName == $this->methodName)
        {
            $this->parse($moduleName, $methodName);
            return $this->output;
        }

        $currentModuleName = $this->moduleName;
        $currentMethodName = $this->methodName;
        $currentParams     = $this->app->getParams();

        /**
         * 设置调用指定模块的指定方法。
         * chang the dir to the previous.
         */
        $this->app->setModuleName($moduleName);
        $this->app->setMethodName($methodName);
        $this->app->setControlFile();

        $fetchParams = array();
        if(!is_array($params))
        {
            parse_str($params, $params);
            $defaultParams     = $this->app->getDefaultParams();
            $this->app->params = empty($defaultParams) ? array() : $this->app->mergeParams($defaultParams, $params);

            /*
             * 设置要fetch的方法的参数类型。
             * Set fetch method param type.
             */
            $paramKeys = array_keys($this->app->params);
            $keyIndex  = 0;
            foreach($params as $param => $value)
            {
                if(empty($paramKeys[$keyIndex])) break;

                $paramKey = $paramKeys[$keyIndex];
                settype($value, gettype($this->app->params[$paramKey]));

                $fetchParams[] = $value;
                $keyIndex ++;
            }
        }
        else
        {
            $params            = array_values($params);
            $fetchParams       = $params;
            $this->app->params = $params;
        }

        $currentPWD = getcwd();

        /**
         * 设置引用的文件和路径。
         * Set the paths and files to included.
         */
        $modulePath        = $this->app->getModulePath($appName, $moduleName);
        $moduleControlFile = $modulePath . 'control.php';
        $actionExtPath     = $this->app->getModuleExtPath($moduleName, 'control');
        $file2Included     = $moduleControlFile;

        if(!empty($actionExtPath))
        {
            /**
             * 设置公共扩展。
             * set common extension.
             */
            $file2Included = $moduleControlFile;

            if(!empty($actionExtPath['common']))
            {
                $commonActionExtFile = $actionExtPath['common'] . strtolower((string) $methodName) . '.php';
                if(file_exists($commonActionExtFile)) $file2Included = $commonActionExtFile;
            }

            if(!empty($actionExtPath['xuan']))
            {
                $commonActionExtFile = $actionExtPath['xuan'] . strtolower((string) $methodName) . '.php';
                if(file_exists($commonActionExtFile)) $file2Included = $commonActionExtFile;
            }

            if(!empty($actionExtPath['vision']))
            {
                $commonActionExtFile = $actionExtPath['vision'] . strtolower((string) $methodName) . '.php';
                if(file_exists($commonActionExtFile)) $file2Included = $commonActionExtFile;
            }

            $commonActionExtFile = $actionExtPath['custom'] . strtolower((string) $methodName) . '.php';
            if(file_exists($commonActionExtFile)) $file2Included = $commonActionExtFile;

            if(!empty($actionExtPath['saas']))
            {
                $commonActionExtFile = $actionExtPath['saas'] . strtolower((string) $methodName) . '.php';
                if(file_exists($commonActionExtFile)) $file2Included = $commonActionExtFile;
            }

            if(!empty($actionExtPath['site']))
            {
                /**
                 * 设置站点扩展。
                 * every site has it's extension.
                 */
                $siteActionExtFile = $actionExtPath['site'] . strtolower((string) $methodName) . '.php';
                $file2Included     = file_exists($siteActionExtFile) ? $siteActionExtFile : $file2Included;
            }
        }

        /**
         * 加载控制器文件。
         * Load the control file.
         */
        if(!is_file($file2Included)) $this->app->triggerError("The control file $file2Included not found", __FILE__, __LINE__, true);

        chdir(dirname($file2Included));
        helper::import($file2Included);

        /**
         * 设置调用的类名。
         * Set the name of the class to be called.
         */
        $className = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
        if(!class_exists($className)) $this->app->triggerError(" The class $className not found", __FILE__, __LINE__, true);

        /**
         * 解析参数，创建模块control对象。
         * Parse the params, create the $module control object.
         */
        $module           = new $className($moduleName, $methodName, $appName);
        $module->viewType = $this->viewType;

        /**
         * 调用对应方法，使用ob方法获取输出内容。
         * Call the method and use ob function to get the output.
         */
        ob_start();
        call_user_func_array(array($module, $methodName), $fetchParams);
        $output = ob_get_contents();
        ob_end_clean();

        unset($module);

        /**
         * 切换回之前的模块和方法。
         * Chang the module、method to the previous.
         */
        $this->app->setModuleName($currentModuleName);
        $this->app->setMethodName($currentMethodName);
        $this->app->params = $currentParams;

        chdir($currentPWD);

        /**
         * 返回内容。
         * Return the content.
         */
        return $output;
    }

    /**
     * 向浏览器输出内容。
     * Print the content of the view.
     *
     * @param  string $moduleName module name
     * @param  string $methodName method name
     * @access  public
     * @return  void
     */
    public function display(string $moduleName = '', string $methodName = '')
    {
        if($this->viewType === 'html' && (!isset($_GET['zin']) || $_GET['zin'] != '0'))
        {
            if(empty($moduleName)) $moduleName = $this->moduleName;
            if(empty($methodName)) $methodName = $this->methodName;
            $modulePath   = $this->app->getModulePath($this->appName, $moduleName);
            $viewType     = $this->viewType == 'mhtml' ? 'html' : $this->viewType;
            $mainViewFile = $modulePath . 'ui' . DS . $this->devicePrefix . strtolower($methodName) . '.' . $viewType . '.php';

            if($moduleName != 'index') $this->app->loadModuleConfig('index');
            if(!in_array("{$moduleName}-{$methodName}", $this->config->index->oldPages)) return $this->render($moduleName, $methodName);
        }

        if(empty($this->output)) $this->parse($moduleName, $methodName);

        echo $this->output;
    }

    /**
     * 向浏览器输出内容。
     * Print the content of the view.
     *
     * @param  string $moduleName module name
     * @param  string $methodName method name
     * @access  public
     * @return  void
     */
    public function render($moduleName = '', $methodName = '')
    {
        if(isset($_GET['zin']) && $_GET['zin'] == '0')
        {
            $this->display($moduleName, $methodName);
            return;
        }

        if(empty($moduleName)) $moduleName = $this->moduleName;
        if(empty($methodName)) $methodName = $this->methodName;

        /* Load zin lib */
        $this->app->loadClass('zin', true);
        \zin\loadConfig();

        /**
         * 设置视图文件。(PHP7有一个bug，不能直接$viewFile = $this->setViewFile())。
         * Set viewFile. (Can't assign $viewFile = $this->setViewFile() directly because one php7's bug.)
         */
        $results = $this->setViewFile($moduleName, $methodName, 'ui');

        $viewFile = $results;
        if(is_array($results)) extract($results);

        /**
         * 获得当前页面的CSS和JS。
         * Get css and js codes for current method.
         */
        $css = $this->getCSS($moduleName, $methodName, '.ui');
        $js  = $this->getJS($moduleName, $methodName, '.ui');
        if($css) $this->view->pageCSS = $css;
        if($js)  $this->view->pageJS = $js;

        /**
         * 切换到视图文件所在的目录，以保证视图文件里面的include语句能够正常运行。
         * Change the dir to the view file to keep the relative paths work.
         */
        $currentPWD = getcwd();
        chdir(dirname($viewFile));

        /**
         * Set zin context data
         */
        \zin\zin::$globalRenderList = array();
        \zin\zin::$enabledGlobalRender = true;
        \zin\zin::$rendered = false;
        \zin\zin::$rawContentCalled = false;

        \zin\zin::$data = (array)$this->view;
        \zin\zin::$data['zinDebug'] = array();
        if($this->config->debug && $this->config->debug >= 2 && $this->config->installed)
        {
            \zin\zin::$data['zinDebug']['trace'] = $this->app->loadClass('trace')->getTrace();
        }

        /**
         * 使用extract和ob方法渲染$viewFile里面的代码。
         * Use extract and ob functions to eval the codes in $viewFile.
         */
        extract(\zin\zin::$data);

        /* 将 hooks 文件添加到当前 context 中。 */
        if(!empty($hookFiles)) \zin\context::current()->addHookFiles($hookFiles);

        /* 加载 common.field.php 和 method.field.php。 */
        $commonFieldFile = dirname($viewFile) . DS . 'common.field.php';
        $methodFieldFile = dirname($viewFile) . DS . $methodName . '.field.php';
        include $commonFieldFile;
        include $methodFieldFile;

        ob_start();
        include $viewFile;

        if(!\zin\zin::$rendered) \zin\render();
        $content = ob_get_clean();

        ob_start();
        echo $content;

        /**
         * 渲染完毕后，再切换回之前的路径。
         * At the end, chang the dir to the previous.
         */
        chdir($currentPWD);
    }

    /**
     * 直接输出data数据，通常用于ajax请求中。
     * Send data directly, for ajax requests.
     *
     * @param  mixed  $data
     * @param  string $type
     * @access public
     * @return void
     */
    public function send($data, string $type = 'json')
    {
        if($type != 'json') return helper::end();

        $data = (array)$data;

        /* Make sure locate in this tab. */
        global $lang;
        $moduleName = $this->app->rawModule;
        $notSameTab = isset($lang->navGroup->{$moduleName}) && $lang->navGroup->{$moduleName} != $this->app->tab;
        $hasLocate  = isset($data['locate']) && $data['locate'][0] == '/';
        if($notSameTab && $hasLocate && !helper::inOnlyBodyMode())
        {
            $data['locate'] .= "#app={$this->app->tab}";
        }

        if(helper::isAjaxRequest() || $this->viewType == 'json')
        {
            /* Process for zh-cn in json. */
            foreach($data as $key => $value)
            {
                if(!is_string($value)) continue;

                /* Retain ["] for json encode when value is jsoned string. */
                $data[$key] = str_replace('%22', '"', urlencode($value));
            }

            if(defined('RUN_MODE') && in_array(RUN_MODE, array('api', 'xuanxuan')))
            {
                print(urldecode(json_encode($data)));
                $response = helper::removeUTF8Bom(ob_get_clean());
                return helper::end($response);
            }

            /* Zand will use ob_get_clean() to print, so cannot clean so early. */
            // $obLevel = ob_get_level();
            // for($i = 0; $i < $obLevel; $i++) ob_end_clean();

            $response = helper::removeUTF8Bom(urldecode(json_encode($data)));
            $this->app->outputXhprof();
            return helper::end($response);
        }

        /**
         * 响应非ajax的请求。
         * Response request not ajax.
         */
        if(isset($data['result']) && $data['result'] == 'success')
        {
            if(!empty($data['message'])) echo js::alert($data['message']);
            $locate = $data['locate'] ?? $_SERVER['HTTP_REFERER'] ?? '';
            if(!empty($locate)) return helper::end(js::locate($locate));
            return helper::end($data['message'] ?? 'success');
        }

        if(isset($data['result']) && $data['result'] == 'fail')
        {
            if(!empty($data['message']))
            {
                if(is_string($data['message']))
                {
                    echo js::alert($data['message']);
                    $locate = $data['locate'] ?? $_SERVER['HTTP_REFERER'] ?? '';
                    if (!empty($locate)) return helper::end(js::locate($locate));
                    return helper::end($data['message'] ?? 'fail');
                }

                $message = json_decode(json_encode($data['message']), true);
                foreach($message as $item => $errors) $message[$item] = implode(',', $errors);
                return helper::end(js::alert(strip_tags(implode('\n', $message))));
            }
            return helper::end('fail');
        }
    }

    /**
     * return error json
     *
     * @param  mixed       $error
     * @param  string|bool $locate
     * @return void
     */
    public function sendError(mixed $error, string|bool $locate = false)
    {
        $result = array('result' => 'fail');
        if($locate)
        {
            if(empty($error)) $error = $this->lang->error->accessDenied;
            $result['load'] = array('alert' => $error);

            if(is_string($locate)) $result['load']['locate'] = $locate;
        }
        else
        {
            $result['message'] = $error;
        }
        return $this->send($result);
    }

    /**
     * send success json
     *
     * @param  array $data
     * @return void
     */
    public function sendSuccess(array $data = array())
    {
        $data['result'] = 'success';
        if(!isset($data['message'])) $data['message'] = $this->lang->saveSuccess;

        if(!isset($data['closeModal']) && helper::isAjaxRequest('modal'))
        {
            $data['closeModal'] = true;
            if(isset($data['load']) and $data['load'] !== true) unset($data['load']);
        }

        return $this->send($data);
    }

    /**
     * 创建一个模块方法的链接。
     * Create a link to one method of one module.
     *
     * @param  string       $moduleName module name
     * @param  string       $methodName method name
     * @param  string|array $vars       the params passed, can be array(key=>value) or key1=value1&key2=value2
     * @param  string       $viewType   the view type
     * @param  bool         $onlybody   remove header and footer or not in iframe
     * @access  public
     * @return  string the link string.
     */
    public function createLink(string $moduleName, string $methodName = 'index', string|array $vars = array(), string $viewType = '', bool $onlybody = false): string
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        return helper::createLink($moduleName, $methodName, $vars, $viewType, $onlybody);
    }

    /**
     * 创建当前模块的一个方法链接。
     * Create a link to the inner method of current module.
     *
     * @param  string       $methodName method name
     * @param  string|array $vars       the params passed, can be array(key=>value) or key1=value1&key2=value2
     * @param  string       $viewType   the view type
     * @param  bool         $onlybody   remove header and footer or not in iframe
     * @access  public
     * @return  string  the link string.
     */
    public function inlink(string $methodName = 'index', string|array $vars = array(), string $viewType = '', bool $onlybody = false)
    {
        return helper::createLink($this->moduleName, $methodName, $vars, $viewType, $onlybody);
    }

    /**
     * 重定向到另一个页面。
     * Location to another page.
     *
     * @param  string $url the target url.
     * @access  public
     * @return  void
     */
    public function locate(string $url)
    {
        helper::header('location', $url);
        helper::end();
    }
}

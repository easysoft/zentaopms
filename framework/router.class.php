<?php
/**
 * 此文件包括ZenTaoPHP框架的三个类：router, config, lang。
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
 * router类。
 * The router class.
 *
 * @package framework
 */
include dirname(__FILE__) . '/base/router.class.php';
class router extends baseRouter
{
    /**
     * 请求的原始模块名。
     * The requestd module name parsed from a URL.
     *
     * @var string
     * @access public
     */
    public $rawModule;

    /**
     * 请求的原始方法名。
     * The requested method name parsed from a URL.
     *
     * @var string
     * @access public
     */
    public $rawMethod;

    /**
     * 请求的原始参数。
     * The requested params parsed from a URL.
     *
     * @var array
     * @access public
     */
    public $rawParams;

    /**
     * 原始URI 
     * 
     * @var string   
     * @access public
     */
    public $rawURI;

    /**
     * 标记是否是工作流
     * Whether the tag is a workflow
     *
     * @var bool
     * @access public
     */
    public $isFlow = false;

    /**
     * Get the $moduleRoot var.
     * 
     * @param  string $appName 
     * @access public
     * @return string
     */
    public function getModuleRoot($appName = '')
    {
        return $this->moduleRoot;
    }

    /**
     * Merge system and translated langs.
     *
     * @param   string $lang  zh-cn|zh-tw|en
     * @access  public
     * @return  void
     */
    public function setClientLang($lang = '')
    {
        if($this->dbh)
        {
            $langs = $this->dbh->query('SELECT value FROM' . TABLE_CONFIG . "WHERE `owner`='system' AND `module`='common' AND `section`='global' AND `key`='langs'")->fetch();
            $langs = empty($langs) ? array() : json_decode($langs->value, true);
            foreach($langs as $langKey => $langData) $this->config->langs[$langKey] = $langData['name'];
        }
        return parent::setClientLang($lang);
    }

    /**
     * 企业版部分功能是从然之合并过来的。然之代码中调用loadLang方法时传递了一个非空的appName，在禅道中会导致错误。
     * 把appName设置为空来避免这个错误。
     * Some codes merged from ranzhi called the function loadLang with a non-empty appName which causes an error in zentao.
     * Set the value of appName to empty to avoid this error.
     *
     * @param   string $moduleName     the module name
     * @param   string $appName     the app name
     * @access  public
     * @return  bool|object the lang object or false.
     */
    public function loadLang($moduleName, $appName = '')
    {
        global $lang;
        if(!is_object($lang)) $lang = new language();

        $appName = '';

        /* Set productCommon and projectCommon for flow. */
        if($moduleName == 'common')
        {
            $productProject = $storyRequirement = $hourPoint = false;
            if($this->dbh and !empty($this->config->db->name))
            {
                global $config;
                if(!isset($config->global)) $config->global = new stdclass();
                $flow = $this->dbh->query('SELECT value FROM' . TABLE_CONFIG . "WHERE `owner`='system' AND `module`='common' AND `key`='flow'")->fetch();
                $config->global->flow = $flow ? $flow->value : 'full';

                $commonSettings = array();
                try
                {
                    $commonSettings = $this->dbh->query('SELECT `key`, value FROM' . TABLE_CONFIG . "WHERE `owner`='system' AND `module`='custom' and `key` in ('productProject','URAndSR','URSRName','storyRequirement','hourPoint')")->fetchAll();
                }
                catch (PDOException $exception) 
                {
                    $repairCode = '|1034|1035|1194|1195|1459|';
                    $errorInfo = $exception->errorInfo;
                    $errorCode = $errorInfo[1];
                    $errorMsg  = $errorInfo[2];
                    $message   = $exception->getMessage();
                    if(strpos($repairCode, "|$errorCode|") !== false or ($errorCode == '1016' and strpos($errorMsg, 'errno: 145') !== false) or strpos($message, 'repair') !== false)
                    {
                        if(isset($config->framework->autoRepairTable) and $config->framework->autoRepairTable)
                        {
                            header("location: " . $config->webRoot . 'checktable.php');
                            exit;
                        }
                    }
                }
            }

            $productCommon = $storyCommon = $hourCommon = 0;
            $projectCommon = empty($this->config->isINT) ? 0 : 1;

            foreach($commonSettings as $setting)
            {
                if($setting->key == 'productProject') list($productCommon, $projectCommon) = explode('_',  $setting->value);
                if($setting->key == 'storyRequirement') $storyCommon = $setting->value;
                if($setting->key == 'hourPoint') $hourCommon    = $setting->value;
                if($setting->key == 'URAndSR') $URAndSR = $setting->value;
                if($setting->key == 'URSRName')
                {
                    $URSRName = json_decode($setting->value, true);
                    if(isset($URSRName['urCommon'][$this->clientLang])) $lang->urCommon = $URSRName['urCommon'][$this->clientLang];
                    if(isset($URSRName['srCommon'][$this->clientLang])) $lang->srCommon = $URSRName['srCommon'][$this->clientLang];
                }
            }

            $config->storyCommon = $storyCommon;

            /* Set productCommon, projectCommon, storyCommon and hourCommon. Default english lang. */
            $lang->productCommon = isset($this->config->productCommonList[$this->clientLang][(int)$productCommon]) ? $this->config->productCommonList[$this->clientLang][(int)$productCommon] : $this->config->productCommonList['en'][(int)$productCommon];
            $lang->projectCommon = isset($this->config->projectCommonList[$this->clientLang][(int)$projectCommon]) ? $this->config->projectCommonList[$this->clientLang][(int)$projectCommon] : $this->config->projectCommonList['en'][(int)$projectCommon];
            $lang->storyCommon   = isset($this->config->storyCommonList[$this->clientLang][(int)$storyCommon])     ? $this->config->storyCommonList[$this->clientLang][(int)$storyCommon]     : $this->config->storyCommonList['en'][(int)$storyCommon];
            $lang->hourCommon    = isset($this->config->hourPointCommonList[$this->clientLang][(int)$hourCommon])  ? $this->config->hourPointCommonList[$this->clientLang][(int)$hourCommon]  : $this->config->hourPointCommonList['en'][(int)$hourCommon];

            if($storyCommon == 0 and isset($URAndSR))
            {
                $config->URAndSR = $URAndSR;
                if(!empty($URAndSR) and !empty($lang->srCommon)) $lang->storyCommon = $lang->srCommon;
            }
        }

        /* When module is custom then reset storyCommon. */
        if($moduleName == 'custom')
        {
            global $config;
            $lang->storyCommon   = isset($this->config->storyCommonList[$this->clientLang][(int)$config->storyCommon])     ? $this->config->storyCommonList[$this->clientLang][(int)$config->storyCommon]     : $this->config->storyCommonList['en'][(int)$config->storyCommon];
        }

        parent::loadLang($moduleName, $appName);

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
        return $lang;
    }

    /**
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
        $fatalLevel[E_ERROR]      = E_ERROR;
        $fatalLevel[E_PARSE]      = E_PARSE;
        $fatalLevel[E_CORE_ERROR] = E_CORE_ERROR;
        $fatalLevel[E_USER_ERROR] = E_USER_ERROR;
        if(isset($fatalLevel[$level])) $this->config->debug = true;
        parent::saveError($level, $message, $file, $line);
    }

    /**
     * 企业版部分功能是从然之合并过来的。然之代码中调用loadModuleConfig方法时传递了一个非空的appName，在禅道中会导致错误。
     * 把appName设置为空来避免这个错误。
     * Some codes merged from ranzhi called the function loadModuleConfig with a non-empty appName which causes an error in zentao.
     * Set the value of appName to empty to avoid this error.
     *
     * @param   string $moduleName     module name
     * @param   string $appName        app name
     * @param   bool   $exitIfNone     exit or not
     * @access  public
     * @return  object|bool the config object or false.
     */
    public function loadModuleConfig($moduleName, $appName = '')
    {
        global $config;

        $appName = '';

        if($config and (!isset($config->$moduleName) or !is_object($config->$moduleName))) $config->$moduleName = new stdclass();

        /* 初始化数组。Init the variables. */
        $extConfigFiles       = array();
        $commonExtConfigFiles = array();
        $siteExtConfigFiles   = array();

        /* 先获得模块的主配置文件。Get the main config file for current module first. */
        $mainConfigFile = $this->getModulePath($appName, $moduleName) . 'config.php';

        /* 查找扩展配置文件。Get extension config files. */
        if($config->framework->extensionLevel > 0) $extConfigPath = $this->getModuleExtPath($appName, $moduleName, 'config');
        if($config->framework->extensionLevel >= 1 and !empty($extConfigPath['common'])) $commonExtConfigFiles = helper::ls($extConfigPath['common'], '.php');
        if($config->framework->extensionLevel == 2 and !empty($extConfigPath['site']))   $siteExtConfigFiles   = helper::ls($extConfigPath['site'], '.php');
        $extConfigFiles = array_merge($commonExtConfigFiles, $siteExtConfigFiles);

        /* 将主配置文件和扩展配置文件合并在一起。Put the main config file and extension config files together. */
        $configFiles = array_merge(array($mainConfigFile), $extConfigFiles);

        /* 加载每一个配置文件。Load every config file. */
        static $loadedConfigs = array();
        foreach($configFiles as $configFile)
        {
            if(in_array($configFile, $loadedConfigs)) continue;
            if(file_exists($configFile)) include $configFile;
            $loadedConfigs[] = $configFile;
        }

        /* 加载数据库中与本模块相关的配置项。Merge from the db configs. */
        if($moduleName != 'common')
        {
            if(isset($config->system->$moduleName))   $this->mergeConfig($config->system->$moduleName, $moduleName);
            if(isset($config->personal->$moduleName)) $this->mergeConfig($config->personal->$moduleName, $moduleName);
        }
    }

    /**
     * The alias for loadModuleConfig.
     *
     * @param  string $moduleName
     * @param  string $appName
     * @access public
     * @return void
     */
    public function loadConfig($moduleName, $appName = '')
    {
        return parent::loadModuleConfig($moduleName);
    }

    /**
     * Export config.
     *
     * @access public
     * @return void
     */
    public function exportConfig()
    {
        ob_start();
        parent::exportConfig();
        $view = ob_get_contents();
        ob_end_clean();

        $view = json_decode($view);
        $view->rand = $this->session->random;
        $this->session->set('rand', $this->session->random);
        echo json_encode($view);
    }

    /**
     * 检查请求的模块和方法是否应该调用工作流引擎进行处理。
     * Check if the requested module and method should call the workflow engine for processing.
     *
     * 处理逻辑：
     * Processing logic:
     * 1、如果当前版本不是企业版，或者当前请求处于安装模式或升级模式，调用父类方法并返回。
     * 1. If the current version is not the enterprise version, or if the current request is in install mode or upgrade mode, call the parent class method and return.
     *
     * 2、如果当前请求的模块在TABLE_WORKFLOW表中不存在，调用父类方法并返回。
     * 2. If the currently requested module does not exist in the TABLE_WORKFLOW table, call the parent class method and return.
     *
     * 3、如果当前请求的模块在TABLE_WORKFLOW表中存在并且是内置模块，并且请求的方法名是browselabel，则修改请求的模块名为flow，修改请求的方法名为browse，重新设置URI参数，调用父类方法并返回。
     * 3. If the currently requested module exists in the TABLE_WORKFLOW table and is a built-in module, and the requested method name is
     * browselabel, rename the module of the request to flow and the method of the request to browse, and reset the URI, call the parent class method and return.
     *
     * 4、如果不满足3中的条件但当前请求的方法在TABLE_WORKFLOWACTION表中存在，且方法扩展类型为重写，则修改请求的模块名为flow，方法名根据5中的规则修改，重新设置URI参数，调用父类方法并返回。
     * 4. If the condition of 3 is not satisfied but the currently requested method exists in the TABLE_WORKFLOWACTION table, and the method
     * extension type is overwrite, rename the module of the request to flow, and rename the method of the request according to the rule in 5.
     * Then reset the URI, call the parent class method and return.
     *
     * 5、如果当前请求的方法名为browse、create、edit、view、delete、export中任意一个，则方法名不变，否则方法名改为operate。
     * 5. If the currently requested method is named any one of browse, create, edit, view, delete, or export, the method name is unchanged, otherwise the method name is changed to operate.
     *
     * @param   bool    $exitIfNone     没有找到该控制器文件的情况：如果该参数为true，则终止程序；如果为false，则打印错误日志
     *                                  The controller file was not found: if the parameter is true, the program is terminated;
     *                                                                     if false, the error log is printed. 
     * @access  public
     * @return  bool
     */
    public function setControlFile($exitIfNone = true)
    {
        /* Set raw module and method name for fetch control. */
        if(empty($this->rawModule)) $this->rawModule = $this->moduleName;
        if(empty($this->rawMethod)) $this->rawMethod = $this->methodName;

        /* If is not a biz version or is in install mode or in in upgrade mode, call parent method. */
        if(!isset($this->config->bizVersion) or defined('IN_INSTALL') or defined('IN_UPGRADE')) return parent::setControlFile($exitIfNone);

        /* Check if the requested module is defined in workflow. */
        $flow = $this->dbh->query("SELECT * FROM " . TABLE_WORKFLOW . " WHERE `module` = '$this->moduleName'")->fetch();
        if(!$flow) return parent::setControlFile($exitIfNone);
        if($flow->status != 'normal') die("<html><head><meta charset='utf-8'></head><body>{$this->lang->flowNotRelease}</body></html>");

        /**
         * 工作流中配置的标签应该请求browse方法，而某些内置流程本身包含browse方法。在这里处理请求的时候会无法区分是内置的browse方法还是工作
         * 流标签的browse方法，为了避免此类冲突，在工作流中配置出的标签请求的方法改为browseLabel，在设置控制器文件时需要将其重设为browse。
         * Tags configured in the workflow should request the browse method, and some built-in processes themselves contain the browse
         * method. When processing a request here, it is impossible to distinguish between the built-in browse method and the browse
         * method of the workflow tag. In order to avoid such conflicts, the method of configuring the label request in the workflow
         * is changed to browseLabel, which needs to be reset to browse when setting the controller file.
         */
        if($flow->buildin && $this->methodName == 'browselabel')
        {
            $this->rawModule = $this->moduleName;
            $this->rawMethod = 'browse';
            $this->isFlow    = true;

            $moduleName = 'flow';
            $methodName = 'browse';

            $this->setFlowURI($moduleName, $methodName);
        }
        else
        {
            $action = $this->dbh->query("SELECT * FROM " . TABLE_WORKFLOWACTION . " WHERE `module` = '$this->moduleName' AND `action` = '$this->methodName'")->fetch();
            if(zget($action, 'extensionType') == 'override')
            {
                $this->rawModule = $this->moduleName;
                $this->rawMethod = $this->methodName;
                $this->isFlow    = true;

                $this->loadModuleConfig('workflowaction');

                $moduleName = 'flow';
                $methodName = $this->methodName;
                /*
                 * 工作流中除了内置方法外的方法，如果是批量操作调用batchOperate方法，其它操作调用operate方法来执行。
                 * In addition to the built-in methods in the workflow, if the batch operation calls the batchOperate method, other operations call the operate method to execute.
                 */
                if(!in_array($this->methodName, $this->config->workflowaction->default->actions))
                {
                    if($action->type == 'single') $methodName = 'operate';
                    if($action->type == 'batch')  $methodName = 'batchOperate';
                }

                $this->setFlowURI($moduleName, $methodName);
            }
        }

        /* Call method of parent. */
        return parent::setControlFile($exitIfNone);
    }

    /**
     * 把请求的URI重设成工作流引擎可以解析的URI。
     * Reset the requested URI to a URI that the workflow engine can resolve.
     *
     * e.g. /$module-browse-search-1.html   =>  /flow-browse-$module-search-1.html
     *      /$module-create.html            =>  /flow-create-$module.html
     *      /$module-edit-1.html            =>  /flow-edit-$module-1.html
     *      /$module-view-1.html            =>  /flow-view-$module-1.html
     *      /$module-delete-1.html          =>  /flow-delete-$module-1.html
     *      /$module-close-1.html           =>  /flow-operate-$module-close-1.html
     *
     *      /index.php?m=$module&f=browse&mode=search&label=1   =>  /index.php?m=flow&f=browse&module=$module&mode=search&label=1
     *      /index.php?m=$module&f=create&id=1                  =>  /index.php?m=flow&f=create&module=$module&$id=1
     *      /index.php?m=$module&f=edit&id=1                    =>  /index.php?m=flow&f=edit&module=$module&$id=1
     *      /index.php?m=$module&f=view&id=1                    =>  /index.php?m=flow&f=view&module=$module&$id=1
     *      /index.php?m=$module&f=delete&id=1                  =>  /index.php?m=flow&f=delete&module=$module&$id=1
     *      /index.php?m=$module&f=close&id=1                   =>  /index.php?m=flow&f=operate&module=$module&action=close&$id=1
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function setFlowURI($moduleName, $methodName)
    {
        $this->rawURI = $this->URI;

        $this->setModuleName($moduleName);
        $this->setMethodName($methodName);

        if($this->config->requestType != 'GET')
        {
            /* e.g. $this->URI = /$module-close-1.html. */
            $params = explode($this->config->requestFix, $this->URI); // $params = array($module, 'close', 1);

            /* Remove module and method. */
            $params = array_slice($params, 2); // $params = array(1);

            /* Prepend other params. */
            if($methodName == 'operate')      array_unshift($params, $this->rawMethod); // $params = array('close', 1);
            if($methodName == 'batchOperate') array_unshift($params, $this->rawMethod); // $params = array('close', 1);
            array_unshift($params, $this->rawModule);                                   // $params = array($module, 'close', 1);
            array_unshift($params, $methodName);                                        // $params = array('operate', $module, 'close', 1);
            array_unshift($params, $moduleName);                                        // $params = array('flow', 'operate', $module, 'close', 1);

            $this->URI = implode($this->config->requestFix, $params);                   // $this->URI = flow-operate-$module-close-1.html;
        }
        else
        {
            /* Extract $path and $query from $params. */
            /* e.g. $tshi->URI = /index.php?m=$module&f=browse&mode=search&label=1. */
            $params = parse_url($this->URI);    // $params = array('path' => '/index.php', 'query' => m=$module&f=browse&mode=search&label=1;
            extract($params);                   // $path = '/index.php'; $query = 'm=$module&f=browse&mode=search&label=1';
            parse_str($query, $params);         // $params = array('m' => $module, 'f' => 'browse', 'mode' = 'search', 'label' => 1);

            /* Remove module and method. */
            unset($params[$this->config->moduleVar]);   // $params = array('f' => 'browse', 'mode' => 'search', 'label' => 1);
            unset($params[$this->config->methodVar]);   // $params = array('mode' => 'search', 'label' => 1);

            $params = array_reverse($params);           // $params = array('label' => 1, 'mode' => 'search');

            /* Prepend other params. */
            $params['module']                 = $this->rawModule;   // $param = array('label' => 1, 'mode' => 'search', 'module' => $module);
            $params[$this->config->methodVar] = $methodName;        // $param = array('label' => 1, 'mode' => 'search', 'module' => $module, 'f' => 'browse');
            $params[$this->config->moduleVar] = $moduleName;        // $param = array('label' => 1, 'mode' => 'search', 'module' => $module, 'f' => 'browse', 'm' => 'flow');

            $params = array_reverse($params);   // $params = array('m' => 'flow', 'f' => 'browse', 'module' => $module, 'mode' => 'search', 'label' => 1);

            $this->URI = $path . '?' . http_build_query($params);   // $this->URI = '/index.php?m=flow&f=browse&module=$module&mode=search&label=1';
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
        parent::parsePathInfo();

        if($this->get->display == 'card') $this->viewType = 'xhtml';
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
        parent::parseGET();

        if($this->get->display == 'card') $this->viewType = 'xhtml';
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
        $URI = !empty($this->rawURI) ? $this->rawURI : $this->URI;
        if($full and $this->config->requestType == 'PATH_INFO')
        {
            if($URI) return $this->config->webRoot . $URI . '.' . $this->viewType;
            return $this->config->webRoot;
        }
        return $URI;
    }

    /**
     * 如果$this->isFlow的值为true，说明这个请求需要工作流引擎来处理，则要根据工作流引擎的需要重新设置参数。
     * If the values of $this->isFlow is true, indicating that the request needs to be processed
     * by the workflow engine, the parameters are reset according to the needs of the workflow engine.
     *
     * @param   array $defaultParams     the default params defined by the method.
     * @param   array $passedParams      the params passed in through url.
     * @access  public
     * @return  array the merged params.
     */
    public function mergeParams($defaultParams, $passedParams)
    {
        /* If the isFlow is true, reset the passed params. */
        if($this->isFlow)
        {
            $passedParams = array_reverse($passedParams);

            /* 如果请求的方法名不是browse、create、edit、view、delete、export中的任何一个，则需要添加action参数来传递请求的方法名。 */
            /* If the requested method name is not any of browse, create, edit, view, delete, or export, you need to add an action parameter to pass the requested method name. */
            if(isset($this->config->workflowaction->default->actions) and !in_array($this->rawMethod, $this->config->workflowaction->default->actions)) $passedParams['action'] = $this->rawMethod;
            /* 添加module参数来传递请求的模块名。 */
            /* Add the module parameter to pass the requested module name. */
            $passedParams['module'] = $this->rawModule;

            $passedParams = array_reverse($passedParams);
        }

        /* display参数用来标记请求是否来自禅道客户端的卡片展示页面，此处应该删掉以避免对方法调用产生影响。 */
        /* The display parameter is used to mark whether the request comes from the card display page of the ZenTao client. It should be deleted here to avoid affecting the method call. */
        unset($passedParams['display']);

        $this->rawParams = parent::mergeParams($defaultParams, $passedParams);
        return $this->rawParams;
    }
}

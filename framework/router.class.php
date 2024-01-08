<?php declare(strict_types=1);
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
include __DIR__ . '/base/router.class.php';
class router extends baseRouter
{
    /**
     * 系统是否正在安装中。
     * Whether is installing.
     *
     * @var array
     * @access public
     */
    public $installing = false;

    /**
     * 系统是否正在升级中。
     * Whether is upgrading.
     *
     * @var array
     * @access public
     */
    public $upgrading = false;

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
     * Fetch的模块名。
     * The fetched module name.
     *
     * @var string
     * @access public
     */
    public $fetchModule;

    /**
     * Check whether app is serving.
     * @access public
     * @return bool
     */
    public function isServing()
    {
        return !$this->installing && !$this->upgrading;
    }

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
            $langs = $this->dbQuery('SELECT `value` FROM ' . TABLE_CONFIG . " WHERE `owner`='system' AND `module`='common' AND `section`='global' AND `key`='langs'")->fetch();
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
     * @param   string $moduleName  the module name
     * @param   string $appName     the app name
     * @access  public
     * @return  bool|object the lang object or false.
     */
    public function loadLang($moduleName, $appName = ''): bool|object
    {
        global $lang;
        if(!is_object($lang)) $lang = new language();

        /* Set productCommon and projectCommon for flow. */
        if($moduleName == 'common') $this->setCommonLang();

        parent::loadLang($moduleName, $appName);

        /* Replace main nav lang. */
        if($moduleName == 'common' and $this->dbh and !empty($this->config->db->name))
        {
            $customMenus = array();
            try
            {
                $customMenus = $this->dbQuery('SELECT * FROM' . TABLE_LANG . "WHERE `module`='common' AND `section`='mainNav' AND `lang`='{$this->clientLang}' AND `vision`='{$this->config->vision}'")->fetchAll();
            }
            catch(PDOException){}

            foreach($customMenus as $menu)
            {
                $menuKey = $menu->key;
                if(isset($lang->mainNav->$menuKey)) $lang->mainNav->$menuKey = zget($lang->navIcons, $menuKey, '') . " {$menu->value}" . substr((string) $lang->mainNav->$menuKey, strpos((string) $lang->mainNav->$menuKey, '|'));
            }
        }

        /* Merge from the db lang. */
        if($moduleName != 'common' and isset($lang->db->custom[$moduleName]))
        {
            foreach($lang->db->custom[$moduleName] as $section => $fields)
            {
                if(in_array($section, array('featureBar', 'moreSelects')))
                {
                    foreach($fields as $featureBarMethod => $featureBarValues)
                    {
                        foreach($featureBarValues as $featureBarKey => $featureBarValue)
                        {
                            if(is_array($featureBarValue))
                            {
                                foreach($featureBarValue as $key => $value) $lang->{$moduleName}->{$section}[$featureBarMethod][$featureBarKey][$key] = $value;
                            }
                            else
                            {
                                $lang->{$moduleName}->{$section}[$featureBarMethod][$featureBarKey] = $featureBarValue;
                            }
                        }
                    }
                }
                else
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
                    foreach($fields as $key => $value)
                    {
                        if($section == 'priList' and $key > 0 and trim((string) $value) === '') continue; // Fix bug #23538.

                        if(!isset($lang->{$moduleName})) $lang->{$moduleName} = new stdclass();
                        if(!isset($lang->{$moduleName}->{$section})) $lang->{$moduleName}->{$section} = array();
                        $lang->{$moduleName}->{$section}[$key] = $value;
                    }
                    unset($nullKey);
                    unset($nullValue);
                }
            }
        }

        return $lang;
    }

    /**
     * Set common lang.
     *
     * @access public
     * @return void
     */
    public function setCommonLang()
    {
        if(defined('COMMONLANGSETTED')) return true;
        define('COMMONLANGSETTED', true);

        if(!defined('ITERATION_KEY'))     define('ITERATION_KEY', 0);
        if(!defined('SPRINT_KEY'))        define('SPRINT_KEY', 1);
        if(!defined('PRODUCT_KEY'))       define('PRODUCT_KEY', 0);
        if(!defined('PROJECT_KEY'))       define('PROJECT_KEY', 0);
        if(!defined('STORYPOINT_KEY'))    define('STORYPOINT_KEY', 1);
        if(!defined('FUNCTIONPOINT_KEY')) define('FUNCTIONPOINT_KEY', 2);

        global $lang, $app, $config;
        $sprintConcept  = $hourPoint = false;
        $commonSettings = array();
        /* Get config from DB. */
        if($this->dbh and !empty($this->config->db->name))
        {
            if(!isset($config->global)) $config->global = new stdclass();
            $config->global->flow = 'full';

            try
            {
                $commonSettings = $this->dbQuery('SELECT `section`, `key`, `value` FROM' . TABLE_CONFIG . "WHERE `owner`='system' AND (`module`='custom' or `module`='common') and `key` in ('sprintConcept', 'hourPoint', 'URSR', 'mode', 'URAndSR', 'scoreStatus', 'disabledFeatures', 'closedFeatures')")->fetchAll();
            }
            catch (PDOException $exception)
            {
                helper::checkDB2Repair($exception);
            }
        }

        $hourKey = $planKey = $URSR = $URAndSR = 0;

        $mode             = 'ALM';
        $score            = '0';
        $projectKey       = ITERATION_KEY;
        $disabledFeatures = '';
        $closedFeatures   = '';

        foreach($commonSettings as $setting)
        {
            if($setting->key == 'sprintConcept')                                 $projectKey       = $setting->value;
            if($setting->key == 'hourPoint')                                     $hourKey          = $setting->value;
            if($setting->key == 'URSR')                                          $URSR             = $setting->value;
            if($setting->key == 'URAndSR')                                       $URAndSR          = $setting->value;
            if($setting->key == 'mode' and $setting->section == 'global')        $mode             = $setting->value;
            if($setting->key == 'scoreStatus' and $setting->section == 'global') $score            = $setting->value;
            if($setting->key == 'disabledFeatures')                              $disabledFeatures = $setting->value;
            if($setting->key == 'closedFeatures')                                $closedFeatures   = $setting->value;
        }

        /* Lite Version is compatible with classic modes */
        if($config->vision == 'lite') $mode = 'ALM';

        /* Record system mode. */
        $config->systemMode = $mode;

        $config->disabledFeatures = $disabledFeatures . ',' . $closedFeatures;

        /* Record system score.*/
        $config->systemScore = $score;

        /* Record hour unit. */
        $config->hourUnit = 'h';
        if($hourKey == STORYPOINT_KEY)    $config->hourUnit = 'sp';
        if($hourKey == FUNCTIONPOINT_KEY) $config->hourUnit = 'fp';

        $iterationKey = $projectKey;

        /* Set productCommon, projectCommon and hourCommon. Default english lang. */
        $lang->productCommon   = $this->config->productCommonList[$this->clientLang][PRODUCT_KEY];
        $lang->projectCommon   = $this->config->projectCommonList[$this->clientLang][PROJECT_KEY];
        $lang->iterationCommon = $this->config->executionCommonList[$this->clientLang][(int)$iterationKey] ?? $this->config->executionCommonList['en'][(int)$iterationKey];
        $lang->executionCommon = $this->config->executionCommonList[$this->clientLang][(int)$projectKey] ?? $this->config->executionCommonList['en'][(int)$projectKey];
        $lang->hourCommon      = $this->config->hourPointCommonList[$this->clientLang][(int)$hourKey] ?? $this->config->hourPointCommonList['en'][(int)$hourKey];

        /* User preference init. */
        $config->URSR          = $URSR;
        $config->URAndSR       = ($URAndSR and !str_contains(",{$config->disabledFeatures},", ',productUR,'));
        $config->programLink   = 'program-browse';
        $config->productLink   = 'product-all';
        $config->projectLink   = 'project-browse';
        $config->executionLink = 'execution-task';

        /* Get user preference. */
        $account     = $this->session->user->account ?? '';
        $userSetting = array();
        if($this->dbh and !empty($this->config->db->name) and $account)
        {
            $sql         = new sql();
            $account     = $sql->quote($account);
            $userSetting = $this->dbQuery('SELECT `key`, `value` FROM ' . TABLE_CONFIG . " WHERE `owner`= $account AND `module`='common' and `key` in ('programLink', 'productLink', 'projectLink', 'executionLink', 'URSR')")->fetchAll();
        }

        foreach($userSetting as $setting)
        {
             if($setting->key == 'URSR')          $config->URSR          = $setting->value;
             if($setting->key == 'programLink')   $config->programLink   = $setting->value;
             if($setting->key == 'productLink')   $config->productLink   = $setting->value;
             if($setting->key == 'projectLink')   $config->projectLink   = $setting->value;
             if($setting->key == 'executionLink') $config->executionLink = $setting->value;
        }

        $lang->URCommon = '';
        $lang->SRCommon = '';
        if($this->dbh and !empty($this->config->db->name))
        {
            $productProject = $this->dbQuery('SELECT `value` FROM ' . TABLE_CONFIG . "WHERE `owner`='system' AND `module`='custom' AND `key`='productProject'")->fetch();
            if(is_string($productProject))
            {
                list($productCommon, $projectCommon) = explode('_', $productProject);
                $lang->productCommon = isset($this->config->productCommonList[$this->clientLang][(int)$productCommon]) ? $this->config->productCommonList[$this->clientLang][(int)$productCommon] : $this->config->productCommonList['en'][0];
            }
            if(!$this->upgrading)
            {
                /* Get story concept in project and product. */
                $clientLang = $this->clientLang == 'zh-tw' ? 'zh-cn' : $this->clientLang;
                $URSRList   = $this->dbQuery('SELECT `key`, `value` FROM' . TABLE_LANG . "WHERE `module` = 'custom' and `section` = 'URSRList' and `lang` = '{$clientLang}'")->fetchAll();
                if(empty($URSRList)) $URSRList = $this->dbQuery('SELECT `key`, `value` FROM' . TABLE_LANG . "WHERE module = 'custom' and `section` = 'URSRList' and `key` = '{$config->URSR}'")->fetchAll();

                /* Get UR pairs and SR pairs. */
                $URPairs  = array();
                $SRPairs  = array();
                foreach($URSRList as $id => $value)
                {
                    $URSR = json_decode((string) $value->value);
                    $URPairs[$value->key] = $URSR->URName;
                    $SRPairs[$value->key] = $URSR->SRName;
                }

                /* Set default story concept and init UR and SR concept. */
                $lang->URCommon = $URPairs[$config->URSR] ?? reset($URPairs);
                $lang->SRCommon = $SRPairs[$config->URSR] ?? reset($SRPairs);
            }

            /* Replace common lang. */
            $customMenus = array();
            try
            {
                $customMenus = $this->dbQuery('SELECT * FROM' . TABLE_LANG . "WHERE `module`='common' AND `lang`='{$this->clientLang}' AND `section`='' AND `vision`='{$config->vision}'")->fetchAll();
            }
            catch(PDOException){}
            foreach($customMenus as $menu) if(isset($lang->{$menu->key})) $lang->{$menu->key} = $menu->value;
        }
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
        $fatalLevel = array();
        $fatalLevel[E_ERROR]      = E_ERROR;
        $fatalLevel[E_PARSE]      = E_PARSE;
        $fatalLevel[E_CORE_ERROR] = E_CORE_ERROR;
        $fatalLevel[E_USER_ERROR] = E_USER_ERROR;
        if(isset($fatalLevel[$level]) && (!isset($this->config->debug) || !$this->config->debug)) $this->config->debug = true;
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
     * @access  public
     * @return  void
     */
    public function loadModuleConfig($moduleName, $appName = '')
    {
        $extConfigPath = array();
        global $config;
        if($config and (!isset($config->$moduleName) or !is_object($config->$moduleName))) $config->$moduleName = new stdclass();

        /* 初始化数组。Init the variables. */
        $extConfigFiles       = array();
        $commonExtConfigFiles = array();
        $visionExtConfigFiles = array();
        $siteExtConfigFiles   = array();

        /* 先获得模块的主配置文件。Get the main config file for current module first. */
        $mainConfigFile = $this->getModulePath($appName, $moduleName) . 'config.php';

        /* 获取 config 目录的配置文件。Get config files from config directory. */
        $configDirFiles = helper::ls($this->getModulePath($appName, $moduleName) . DS . 'config', '.php');

        /* 查找扩展配置文件。Get extension config files. */
        if($config->framework->extensionLevel > 0) $extConfigPath = $this->getModuleExtPath($moduleName, 'config');
        if($config->framework->extensionLevel >= 1)
        {
            if(!empty($extConfigPath['common'])) $commonExtConfigFiles = helper::ls($extConfigPath['common'], '.php');
            if(!empty($extConfigPath['xuan']))   $commonExtConfigFiles = array_merge($commonExtConfigFiles, helper::ls($extConfigPath['xuan'], '.php'));
            if(!empty($extConfigPath['vision'])) $commonExtConfigFiles = array_merge($commonExtConfigFiles, helper::ls($extConfigPath['vision'], '.php'));
            if(!empty($extConfigPath['saas']))   $commonExtConfigFiles = array_merge($commonExtConfigFiles, helper::ls($extConfigPath['saas'], '.php'));
            if(!empty($extConfigPath['custom'])) $commonExtConfigFiles = array_merge($commonExtConfigFiles, helper::ls($extConfigPath['custom'], '.php'));
        }
        if($config->framework->extensionLevel == 2 and !empty($extConfigPath['site'])) $siteExtConfigFiles = helper::ls($extConfigPath['site'], '.php');
        $extConfigFiles = array_merge($commonExtConfigFiles, $configDirFiles, $siteExtConfigFiles);

        /* 将主配置文件和扩展配置文件合并在一起。Put the main config file and extension config files together. */
        $configFiles = array_merge(array($mainConfigFile), $configDirFiles, $extConfigFiles);

        /* 加载每一个配置文件。Load every config file. */
        foreach($configFiles as $configFile)
        {
            if(in_array($configFile, self::$loadedConfigs)) continue;
            if(file_exists($configFile)) include $configFile;
            self::$loadedConfigs[] = $configFile;
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
        return $this->loadModuleConfig($moduleName);
    }

    /**
     * Export config.
     *
     * @access public
     * @return string
     */
    public function exportConfig()
    {
        $view = json_decode(parent::exportConfig());
        $view->rand = $view->random;
        $this->session->set('rand', $view->rand);

        return json_encode($view);
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
        if($this->config->edition == 'open' or $this->installing or $this->upgrading) return parent::setControlFile($exitIfNone);

        /* Check if the requested module is defined in workflow. */
        $flow = $this->dbQuery("SELECT * FROM " . TABLE_WORKFLOW . " WHERE `module` = '$this->moduleName'")->fetch();
        if(!$flow) return parent::setControlFile($exitIfNone);
        if($flow->status != 'normal') helper::end("<html><head><meta charset='utf-8'></head><body>{$this->lang->flowNotRelease}</body></html>");

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
            $action = $this->dbQuery("SELECT * FROM " . TABLE_WORKFLOWACTION . " WHERE `module` = '$this->moduleName' AND `action` = '$this->methodName' AND `vision` = '{$this->config->vision}'")->fetch();
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
        $query = null;
        $path = null;
        $this->rawURI = $this->uri;

        $this->setModuleName($moduleName);
        $this->setMethodName($methodName);

        if($this->config->requestType != 'GET')
        {
            /* e.g. $this->uri = /$module-close-1.html. */
            $params = explode($this->config->requestFix, (string) $this->uri);       // $params = array($module, 'close', 1);

            /* Remove module and method. */
            $params = array_slice($params, 2);                              // $params = array(1);

            if($moduleName == 'flow' and $methodName == 'browse')
            {
                $mode = 'browse';
                if(count($params) > 0 and !is_numeric($params[0])) $mode = array_shift($params);
                array_unshift($params, $mode);
            }

            array_unshift($params, $methodName);                            // $params = array('operate', 1);
            array_unshift($params, $moduleName);                            // $params = array('flow', 'operate', 1);

            $this->uri = implode($this->config->requestFix, $params);       // $this->uri = flow-operate-1.html;
        }
        else
        {
            /* Extract $path and $query from $params. */
            /* e.g. $tshi->URI = /index.php?m=$module&f=close&id=1. */
            $params = parse_url((string) $this->uri);                        // $params = array('path' => '/index.php', 'query' => m=$module&f=close&id=1;
            extract($params);                                       // $path = '/index.php'; $query = 'm=$module&f=close&id=1';
            parse_str((string) $query, $params);                             // $params = array('m' => $module, 'f' => 'close', 'id' => 1);

            /* Remove module and method. */
            unset($params[$this->config->moduleVar]);               // $params = array('f' => 'close', 'id' => 1);
            unset($params[$this->config->methodVar]);               // $params = array('id' => 1);

            $params = array_reverse($params);                       // $params = array('id' => 1);

            if($moduleName == 'flow' and $methodName == 'browse')
            {
                $mode = zget($params, 'mode', 'browse');
                if(is_numeric($mode)) $mode = 'browse';
                $params['mode'] = $mode;

                $get = array_reverse($_GET);
                $get['mode'] = $mode;
                $_GET = array_reverse($get);
            }
            $params[$this->config->methodVar] = $methodName;        // $param = array('id' => 1, 'f' => 'operate');
            $params[$this->config->moduleVar] = $moduleName;        // $param = array('id' => 1, 'f' => 'operate', 'm' => 'flow');

            $params = array_reverse($params);                       // $params = array('m' => 'flow', 'f' => 'operate', 'id' => 1);

            $this->uri = $path . '?' . http_build_query($params);   // $this->uri = '/index.php?m=flow&f=operate&id=1';
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
     * @param  bool   $full The URI contains the webRoot if $full is true else only the URI will be return.
     * @access public
     * @return string
     */
    public function getURI($full = false)
    {
        $theURI   = !empty($this->rawURI) ? $this->rawURI : $this->uri;
        $tidParam = ($this->config->requestType == 'PATH_INFO' and helper::isWithTID()) ? "?tid={$_GET['tid']}" : '';

        if($full and $this->config->requestType == 'PATH_INFO')
        {
            if($theURI) return $this->config->webRoot . $theURI . '.' . $this->viewType . $tidParam;
            return $this->config->webRoot . $tidParam;
        }
        return $theURI . $tidParam;
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
        if(isset($_GET['project'])) $this->session->set('project', $_GET['project']);
        /* If the isFlow is true, reset the passed params. */

        /* display参数用来标记请求是否来自禅道客户端的卡片展示页面，此处应该删掉以避免对方法调用产生影响。 */
        /* The display parameter is used to mark whether the request comes from the card display page of the ZenTao client. It should be deleted here to avoid affecting the method call. */
        unset($passedParams['display']);

        return parent::mergeParams($defaultParams, $passedParams);
    }

    /**
     * 加载一个模块：
     *
     * Load a module.
     *
     * @access public
     * @return bool|object  if the module object of die.
     */
    public function loadModule()
    {
        /* 不能直接请求基类的方法 Cannot call methods of base control class. */
        if(method_exists('Control', $this->methodName))
        {
            echo 'Cannot call methods of base control class.';
            return false;
        }

        return parent::loadModule();
    }

    /**
     * 检查是否已安装禅道，主要用于DevOps平台版。
     * Check zentao is installed.
     *
     * @access public
     * @return bool
     */
    public function checkInstalled()
    {
        if(($this->config->inContainer || $this->config->inQuickon) && !$this->getInstalledVersion()) return false;

        return isset($this->config->installed) && $this->config->installed;
    }
}

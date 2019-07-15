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
     * 工作流模块名。
     * The module name of a flow.
     *
     * @var string
     * @access public
     */
    public $workflowModule;

    /**
     * 工作流方法名。
     * The method name of a flow.
     *
     * @var string
     * @access public
     */
    public $workflowMethod;

    /**
     * Add custom langs when set client lang.
     *
     * @param   string $lang  zh-cn|zh-tw|zh-hk|en
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
     * 加载语言文件，返回全局$lang对象。
     * Load lang and return it as the global lang object.
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
            $productProject = false;
            if($this->dbh and !empty($this->config->db->name))
            {
                global $config;
                if(!isset($config->global)) $config->global = new stdclass();
                $flow = $this->dbh->query('SELECT value FROM' . TABLE_CONFIG . "WHERE `owner`='system' AND `module`='common' AND `key`='flow'")->fetch();
                $config->global->flow = $flow ? $flow->value : 'full';

                try
                {
                    $productProject = $this->dbh->query('SELECT value FROM' . TABLE_CONFIG . "WHERE `owner`='system' AND `module`='custom' AND `key`='productProject'")->fetch();
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

            $productCommon = $projectCommon = 0;
            if(!empty($this->config->isINT)) $projectCommon = 1;
            if($productProject)
            {
                $productProject = $productProject->value;
                list($productCommon, $projectCommon) = explode('_', $productProject);
            }
            $lang->productCommon = isset($this->config->productCommonList[$this->clientLang][(int)$productCommon]) ? $this->config->productCommonList[$this->clientLang][(int)$productCommon] : $this->config->productCommonList['en'][0];
            $lang->projectCommon = isset($this->config->projectCommonList[$this->clientLang][(int)$projectCommon]) ? $this->config->projectCommonList[$this->clientLang][(int)$projectCommon] : $this->config->projectCommonList['en'][0];
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
     * 加载模块的config文件，返回全局$config对象。
     * 如果该模块是common，加载$configRoot的配置文件，其他模块则加载其模块的配置文件。
     *
     * Load config and return it as the global config object.
     * If the module is common, search in $configRoot, else in $modulePath.
     *
     * Extension: set appName as empty.
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
     * Alias load  module config.
     *
     * Extension: set appName as empty.
     *
     * @param  string $moduleName
     * @param  string $appName
     * @access public
     * @return void
     */
    public function loadConfig($moduleName, $appName = '')
    {
        $appName = '';

        return parent::loadModuleConfig($moduleName, $appName);
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
     * 设置要被调用的控制器文件。
     * Set the control file of the module to be called.
     *
     * Extension: If the module and method is defined in workflow, run workflow engine.
     *
     * @param   bool    $exitIfNone     没有找到该控制器文件的情况：如果该参数为true，则终止程序；如果为false，则打印错误日志
     *                                  If control file not foundde, how to do. True, die the whole app. false, log error.
     * @access  public
     * @return  bool
     */
    public function setControlFile($exitIfNone = true)
    {
        /* If the module and method is defined in workflow, run workflow engine. */
        if(defined('TABLE_WORKFLOW') && defined('TABLE_WORKFLOWACTION'))
        {
            $flow = $this->dbh->query("SELECT * FROM " . TABLE_WORKFLOW . " WHERE `module` = '$this->moduleName'")->fetch();
            if($flow)
            {
                $action = $this->dbh->query("SELECT * FROM " . TABLE_WORKFLOWACTION . " WHERE `module` = '$this->moduleName' AND `action` = '$this->methodName'")->fetch();
                if(zget($action, 'extensionType') == 'override')
                {
                    $this->workflowModule = $this->moduleName;
                    $this->workflowMethod = $this->methodName;

                    $this->loadModuleConfig('workflowaction');

                    $moduleName = 'flow';
                    $methodName = in_array($this->methodName, $this->config->workflowaction->default->actions) ? $this->methodName : 'operate';

                    $this->setModuleName($moduleName);
                    $this->setMethodName($methodName);
                }
            }
        }

        /* Call method of parent. */
        return parent::setControlFile($exitIfNone);
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
     * 合并请求的参数和默认参数，这样就可以省略已经有默认值的参数了。
     * Merge the params passed in and the default params. Thus the params which have default values needn't pass value, just like a function.
     *
     * Extension: If the workflowmodule and workflowmethod is not empty, reset the passed params.
     *
     * @param   array $defaultParams     the default params defined by the method.
     * @param   array $passedParams      the params passed in through url.
     * @access  public
     * @return  array the merged params.
     */
    public function mergeParams($defaultParams, $passedParams)
    {
        /* If the workflowmodule and workflowmethod is not empty, reset the passed params. */
        if($this->workflowModule && $this->workflowMethod)
        {
            $passedParams = array_reverse($passedParams);
            if(!in_array($this->workflowMethod, $this->config->workflowaction->default->actions))
            {
                $passedParams['method'] = $this->workflowMethod;
            }
            $passedParams['module'] = $this->workflowModule;

            $passedParams = array_reverse($passedParams);
        }

        unset($passedParams['display']);
        return parent::mergeParams($defaultParams, $passedParams);
    }
}

<?php declare(strict_types=1);
/**
 * ZenTaoPHP的control类。
 * The control class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * control基类继承与baseControl，所有模块的control类都派生于它。
 * The base class of control extends baseControl.
 *
 * @package framework
 */
include __DIR__ . '/base/control.class.php';
class control extends baseControl
{
    public $redis = false;

    /**
     * Check requiredFields and set exportFields for workflow.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $appName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '', string $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);

        $this->app->setOpenApp();

        if($this->config->redis) $this->redis = $this->app->redis;

        if($this->config->edition == 'open') return false;

        /* Code for task #9224. Set requiredFields for workflow. */
        if($this->dbh && ($this->app->isServing() || (defined('RUN_MODE') and RUN_MODE == 'api')))
        {
            $this->extendExportFields();
            $this->extendEditorFields();

            /* If workflow is created by a normal user, set priv. */
            if(isset($this->app->user) and !$this->app->user->admin) $this->setDefaultPrivByWorkflow();
        }
    }

    /**
     * Append export fields to the config of this module from workflow.
     *
     * @access public
     * @return void
     */
    public function extendExportFields()
    {
        if(isset($this->config->{$this->moduleName}) and str_contains((string) $this->methodName, 'export'))
        {
            if(isset($this->config->{$this->moduleName}->exportFields) or isset($this->config->{$this->moduleName}->list->exportFields))
            {
                $exportFields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($this->moduleName)->andWhere('canExport')->eq('1')->andWhere('buildin')->eq('0')->fetchAll('field');

                if(isset($this->config->{$this->moduleName}->exportFields))
                {
                    foreach($exportFields as $field) $this->config->{$this->moduleName}->exportFields .= ",{$field->field}";
                }

                if(isset($this->config->{$this->moduleName}->list->exportFields))
                {
                    foreach($exportFields as $field) $this->config->{$this->moduleName}->list->exportFields .= ",{$field->field}";
                }

                if(isset($this->config->excel->editor[$this->moduleName]))
                {
                    foreach($exportFields as $field)
                    {
                        if($field->control == 'richtext') $this->config->excel->editor[$this->moduleName][] = $field->field;
                    }
                }

                foreach($exportFields as $flowField => $exportField)
                {
                    if(!isset($this->lang->{$this->moduleName}->$flowField)) $this->lang->{$this->moduleName}->$flowField = $exportField->name;
                }
            }
        }
    }

    /**
     * Append editor fields to the config of this module from workflow.
     *
     * @access public
     * @return void
     */
    public function extendEditorFields()
    {
        $moduleName = $this->moduleName;
        $methodName = $this->methodName;

        $textareaFields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($this->moduleName)->andWhere('control')->eq('richtext')->andWhere('buildin')->eq('0')->fetchAll('field');
        if($textareaFields)
        {
            $editorIdList = array();
            foreach($textareaFields as $textareaField) $editorIdList[] = $textareaField->field;

            if(!isset($this->config->{$moduleName})) $this->config->{$moduleName} = new stdclass();
            if(!isset($this->config->{$moduleName}->editor)) $this->config->{$moduleName}->editor = new stdclass();
            if(!isset($this->config->{$moduleName}->editor->{$methodName})) $this->config->{$moduleName}->editor->{$methodName} = array('id' => '', 'tools' => 'simpleTools');
            $this->config->{$moduleName}->editor->{$methodName}['id'] .= ',' . join(',', $editorIdList);
            trim($this->config->{$moduleName}->editor->{$methodName}['id'], ',');
        }
    }

    /**
     * Det default priv by workflow.
     *
     * @access public
     * @return bool
     */
    public function setDefaultPrivByWorkflow()
    {
        $actionList = $this->dao->select('module, `action`')->from(TABLE_WORKFLOWACTION)
            ->where('createdBy')->eq($this->app->user->account)
            ->andWhere('buildin')->eq('0')
            ->fetchGroup('module');

        if($actionList)
        {
            foreach($actionList as $module => $actions)
            {
                foreach($actions as $action) $this->app->user->rights['rights'][$module][$action->action] = 1;
            }
        }

        $labelList = $this->dao->select('module, code')->from(TABLE_WORKFLOWLABEL)
            ->where('createdBy')->eq($this->app->user->account)
            ->andWhere('buildin')->eq('0')
            ->fetchGroup('module');

        if($labelList)
        {
            foreach($labelList as $module => $labels)
            {
                foreach($labels as $label)
                {
                    $code = str_replace('browse', '', (string) $label->code);
                    $this->app->user->rights['rights'][$module][$code] = 1;
                }
            }
        }

        return true;
    }

    /**
     * 企业版部分功能是从然之合并过来的。ZDOO代码中调用loadModel方法时传递了一个非空的appName，在禅道中会导致错误。
     * 调用父类的loadModel方法来避免这个错误。
     * Some codes merged from ZDOO called the function loadModel with a non-empty appName which causes an error in zentao.
     * Call the parent function with empty appName to avoid this error.
     *
     * @param  string $moduleName 模块名，如果为空，使用当前模块。The module name, if empty, use current module's name.
     * @param  string $appName    应用名，如果为空，使用当前应用。The app name, if empty, use current app's name.
     * @access public
     * @return object|bool 如果没有model文件，返回false，否则返回model对象。If no model file, return false, else return the model object.
     */
    public function loadModel(string $moduleName = '', string $appName = ''): object|bool
    {
        return parent::loadModel($moduleName);
    }

    /**
     * 企业版部分功能是从然之合并过来的。ZDOO代码中调用loadZen方法时传递了一个非空的appName，在禅道中会导致错误。
     * 调用父类的loadZen方法来避免这个错误。
     * Some codes merged from ZDOO called the function loadZen with a non-empty appName which causes an error in zentao.
     * Call the parent function with empty appName to avoid this error.
     *
     * @param  string $moduleName 模块名，如果为空，使用当前模块。The module name, if empty, use current module's name.
     * @param  string $appName    应用名，如果为空，使用当前应用。The app name, if empty, use current app's name.
     * @access public
     * @return object|bool 如果没有model文件，返回false，否则返回model对象。If no model file, return false, else return the model object.
     */
    public function loadZen(string $moduleName = '', string $appName = ''): object|bool
    {
        return parent::loadZen($moduleName);
    }

    /**
     * 加载model的class扩展，主要是为了开发加密代码使用。
     * 可以将主要的逻辑存放到$moduleName/ext/model/class/$extensionName.class.php中。
     * 然后在ext/model/$extension.php的扩展里面使用$this->loadExtension()来调用相应的方法。
     * ext/model/class/*.class.php代码可以加密。而ext/model/*.php可以不用加密。
     * 因为框架对model的扩展是采取合并文件的方式，ext/model/*.php文件不能加密。
     *
     * Load extension class of a model thus user can encrypt the code.
     * You can put the main extension logic codes in $moduleName/ext/model/class/$extensionName.class.php.
     * And call them by the ext/model/$extension.php like this: $this->loadExtension('myextension')->method().
     * You can encrypt the code in ext/model/class/*.class.php.
     * Because the framework will merge the extension files in ext/model/*.php to the module/model.php.
     *
     * @param  string $extensionName
     * @param  string $moduleName
     * @access public
     * @return void
     */
    public function loadExtension(string $extensionName, string $moduleName = '')
    {
        if(empty($extensionName)) return false;
        if(empty($moduleName)) $moduleName = $this->moduleName;

        $moduleName    = strtolower((string) $moduleName);
        $extensionName = strtolower($extensionName);

        $type      = 'model';
        $className = strtolower(static::class);
        if($className == $moduleName . 'zen' || $className == 'ext' . $moduleName . 'zen') $type = 'zen';

        /* 设置扩展类的名字。Set the extension class name. */
        $extensionClass = $extensionName . ucfirst($moduleName);
        if($type != 'model') $extensionClass .= ucfirst($type);
        if(isset($this->$extensionClass)) return $this->$extensionClass;

        /* 设置扩展的名字和相应的文件。Set extenson name and extension file. */
        $moduleExtPath = $this->app->getModuleExtPath($moduleName, $type);
        if(!empty($moduleExtPath['site'])) $extensionFile = $moduleExtPath['site'] . 'class/' . $extensionName . '.class.php';
        if(!isset($extensionFile) or !file_exists($extensionFile)) $extensionFile = $moduleExtPath['custom'] . 'class/' . $extensionName . '.class.php';
        if(!isset($extensionFile) or !file_exists($extensionFile)) $extensionFile = $moduleExtPath['saas']   . 'class/' . $extensionName . '.class.php';
        if(!isset($extensionFile) or !file_exists($extensionFile)) $extensionFile = $moduleExtPath['vision'] . 'class/' . $extensionName . '.class.php';
        if(!isset($extensionFile) or !file_exists($extensionFile)) $extensionFile = $moduleExtPath['xuan']   . 'class/' . $extensionName . '.class.php';
        if(!isset($extensionFile) or !file_exists($extensionFile)) $extensionFile = $moduleExtPath['common'] . 'class/' . $extensionName . '.class.php';

        /* 载入父类。Try to import parent model file auto and then import the extension file. */
        if(!class_exists($moduleName . ucfirst($type))) helper::import($this->app->getModulePath($this->appName, $moduleName) . $type . '.php');
        if(!helper::import($extensionFile)) return false;
        if(!class_exists($extensionClass)) return false;

        /* 实例化扩展类。Create an instance of the extension class and return it. */
        $extensionObject = new $extensionClass();
        if($type == 'model') $extensionClass = str_replace(ucfirst($type), '', $extensionClass);
        $this->$extensionClass = $extensionObject;
        $this->$extensionClass->view = $this->view;
        return $extensionObject;
    }

    /**
     * 设置视图文件：主视图文件，扩展视图文件， 站点扩展视图文件，以及钩子脚本。
     * Set view files: the main file, extension view file, site extension view file and hook files.
     *
     * @param  string   $moduleName    module name
     * @param  string   $methodName    method name
     * @param  string   $viewDir
     * @access public
     * @return string  the view file
     */
    public function setViewFile(string $moduleName, string $methodName, string $viewDir = 'view')
    {
        $moduleName = strtolower(trim($moduleName));
        $methodName = strtolower(trim($methodName));

        $modulePath  = $this->app->getModulePath($this->appName, $moduleName);
        $viewExtPath = $this->app->getModuleExtPath($moduleName, $viewDir);

        $viewType     = ($this->viewType == 'mhtml' or $this->viewType == 'xhtml') ? 'html' : $this->viewType;
        $mainViewFile = $modulePath . $viewDir . DS . $this->devicePrefix . $methodName . '.' . $viewType . '.php';

        /* If the main view file doesn't exist, set the device prefix to empty and reset the main view file. */
        if(!file_exists($mainViewFile) and $this->app->clientDevice != 'mobile')
        {
            $originalPrefix     = $this->devicePrefix;
            $this->devicePrefix = '';
            $mainViewFile = $modulePath . 'view' . DS . $this->devicePrefix . $methodName . '.' . $viewType . '.php';
            $this->devicePrefix = $originalPrefix;
        }

        $viewFile = $mainViewFile;

        if(!empty($viewExtPath))
        {
            $commonExtViewFile = $viewExtPath['common'] . $this->devicePrefix . $methodName . ".{$viewType}.php";
            $xuanExtViewFile   = $viewExtPath['xuan']   . $this->devicePrefix . $methodName . ".{$viewType}.php";
            $visionExtViewFile = $viewExtPath['vision'] . $this->devicePrefix . $methodName . ".{$viewType}.php";
            $saasExtViewFile   = $viewExtPath['saas']   . $this->devicePrefix . $methodName . ".{$viewType}.php";
            $customExtViewFile = $viewExtPath['custom'] . $this->devicePrefix . $methodName . ".{$viewType}.php";
            $siteExtViewFile   = empty($viewExtPath['site']) ? '' : $viewExtPath['site'] . $this->devicePrefix . $methodName . ".{$viewType}.php";

            /* Get ext files, site > custom > vision > common. */
            if(!empty($siteExtViewFile) and file_exists($siteExtViewFile))
            {
                $viewFile = $siteExtViewFile;
            }
            else if(file_exists($customExtViewFile))
            {
                $viewFile = $customExtViewFile;
            }
            else if(!empty($viewExtPath['vision']) and file_exists($visionExtViewFile))
            {
                $viewFile = $visionExtViewFile;
            }
            else if(file_exists($xuanExtViewFile))
            {
                $viewFile = $xuanExtViewFile;
            }
            else if(file_exists($saasExtViewFile))
            {
                $viewFile = $saasExtViewFile;
            }
            else if(file_exists($commonExtViewFile))
            {
                $viewFile = $commonExtViewFile;
            }

            if(!is_file($viewFile)) $viewFile = dirname((string) $viewExtPath['common'], 2) . DS . 'view' . DS . $this->devicePrefix . $methodName . ".{$viewType}.php";
            if(!is_file($viewFile)) helper::end(js::error($this->lang->notPage) . js::locate('back'));

            /* Get ext hook files. */
            $commonExtHookFiles = glob($viewExtPath['common'] . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");
            if(!empty($viewExtPath['vision']))
            {
                $visionExtHookFiles = glob($viewExtPath['vision'] . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");
                $commonExtHookFiles = array_merge((array)$commonExtHookFiles, (array)$visionExtHookFiles);
            }

            $xuanExtHookFiles   = glob($viewExtPath['xuan']   . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");
            $saasExtHookFiles   = glob($viewExtPath['saas']   . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");
            $customExtHookFiles = glob($viewExtPath['custom'] . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");

            $siteExtHookFiles = empty($viewExtPath['site']) ? '' : glob($viewExtPath['site'] . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");
            $extHookFiles     = array_merge((array)$commonExtHookFiles, (array)$xuanExtHookFiles, (array)$saasExtHookFiles, (array)$customExtHookFiles, (array)$siteExtHookFiles);
        }

        if(!empty($extHookFiles)) return array('viewFile' => $viewFile, 'hookFiles' => $extHookFiles);
        return $viewFile;
    }

    /**
     * 默认渲染方法，适用于viewType = html的时候。
     * Default parse method when viewType != json, like html.
     *
     * @param string $moduleName    module name
     * @param string $methodName    method name
     * @access public
     * @return void
     */
    public function parseDefault(string $moduleName, string $methodName)
    {
        /**
         * 设置视图文件。(PHP7有一个bug，不能直接$viewFile = $this->setViewFile())。
         * Set viewFile. (Can't assign $viewFile = $this->setViewFile() directly because one php7's bug.)
         */
        $results  = $this->setViewFile($moduleName, $methodName);
        $viewFile = $results;
        if(is_array($results)) extract($results);

        /**
         * 获得当前页面的CSS和JS。
         * Get css and js codes for current method.
         */
        $css = $this->getCSS($moduleName, $methodName);
        $js  = $this->getJS($moduleName, $methodName);
        /* If the js or css file doesn't exist, set the device prefix to empty and reset the js or css file. */
        if($this->viewType == 'xhtml')
        {
            $originalPrefix = $this->devicePrefix;
            $this->devicePrefix = '';
            $css .= $this->getCSS($moduleName, $methodName);
            $js  .= $this->getJS($moduleName, $methodName);
            $this->devicePrefix = $originalPrefix;
        }
        if($css) $this->view->pageCSS = $css;
        if($js)  $this->view->pageJS  = $js;

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
     * @param  string $moduleName module name.
     * @param  string $methodName method name.
     * @param  array  $params     params.
     * @access  public
     * @return  string  the parsed html.
     */
    public function fetch(string $moduleName = '', string $methodName = '', array|string $params = array(), string $appName = '')
    {
        if($moduleName != $this->moduleName) $this->app->fetchModule = $moduleName;

        return parent::fetch($moduleName, $methodName, $params, $appName);
    }

    /**
     * Build operate menu of a method.
     *
     * @param  object $object    product|project|productplan|release|build|story|task|bug|testtask|testcase|testsuite
     * @param  string $type view|browse
     * @access public
     * @return string
     */
    public function buildOperateMenu(object $object, string $type = 'view')
    {
        if($this->config->edition == 'open') return false;

        $moduleName = $this->moduleName;
        return $this->$moduleName->buildOperateMenu($object, $type);
    }

    /**
     * Execute hooks of a method.
     *
     * @param  int    $objectID     The id of an object. The object maybe a bug | build | feedback | product | productplan | project | release | story | task | testcase | testsuite | testtask.
     * @access public
     * @return string
     */
    public function executeHooks(int $objectID): string
    {
        if($this->config->edition == 'open') return '';

        $moduleName = $this->moduleName;
        return $this->$moduleName->executeHooks($objectID);
    }

    /**
     * Set workflow export fields
     *
     * @access public
     * @return array
     */
    public function getFlowExportFields()
    {
        if($this->config->edition == 'open') return array();
        if(!empty($this->app->installing) || !empty($this->app->upgrading)) return array();

        $moduleName = $this->moduleName;
        return $this->$moduleName->getFlowExportFields();
    }

    /**
     * Print extend fields.
     *
     * @param  object $object   bug | build | feedback | product | productplan | project | release | story | task | testcase | testsuite | testtask
     * @param  string $type     The parent component which fileds displayed in. It should be table or div.
     * @param  string $extras   The extra params.
     *                          columns=2|3|5           Number of the columns merged to display the fields. The default is 1.
     *                          position=left|right     The position which the fields displayed in a page.
     *                          inForm=0|1              The fields displayed in a form or not. The default is 1.
     *                          inCell=0|1              The fields displayed in a div with class cell or not. The default is 0.
     * @param  bool   $print
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function printExtendFields(object|string $object, string $type, string $extras = '', bool $print = true, string $moduleName = '', string $methodName = '')
    {
        if($this->config->edition == 'open') return false;
        if(!empty($this->app->installing) || !empty($this->app->upgrading)) return false;

        $moduleName = $moduleName ?: $this->app->getModuleName();
        $methodName = $methodName ?: $this->app->getMethodName();

        $this->loadModel('flow');
        if($type == 'html')
        {
            parse_str($extras, $result);
            $html  = $this->flow->buildExtendHtmlValue($object, zget($result, 'position', 'info'));
            $html .= $this->appendExtendCssAndJS($moduleName, $methodName, $object);
        }
        else
        {
            ob_start();
            $this->flow->printFields($moduleName, $methodName, $object, $type, $extras);
            $html = ob_get_contents();
            ob_end_clean();
        }

        if(!$print) return $html;
        echo $html;
    }

    /**
     * 追加工作流新增的字段，给formGridPanel组件使用。
     * append workflow fields to form.
     *
     * @param  zin\fieldList $fields
     * @param  string        $moduleName
     * @param  string        $methodName
     * @param  object        $object
     * @access public
     * @return zin\fieldList
     */
    public function appendExtendFields(zin\fieldList $fields, string $moduleName = '', string $methodName = '', object $object = null): zin\fieldList
    {
        if($this->config->edition == 'open') return $fields;
        if(!empty($this->app->installing) || !empty($this->app->upgrading)) return $fields;

        $moduleName = $moduleName ? $moduleName : $this->app->rawModule;
        $methodName = $methodName ? $moduleName : $this->app->rawMethod;

        $flow = $this->loadModel('workflow')->getByModule($moduleName);
        if(!$flow) return $fields;

        $action = $this->loadModel('workflowaction')->getByModuleAndAction($flow->module, $methodName);
        if(!$action || $action->extensionType != 'extend') return $fields;

        $uiID      = $this->loadModel('workflowlayout')->getUIByData($flow->module, $action->action, $object);
        $fieldList = $this->workflowaction->getFields($flow->module, $action->action, true, null, $uiID);
        return $this->loadModel('flow')->buildFormFields($fields, $fieldList);
    }

    /**
     * 追加工作流配置的js和css到页面上。
     * append workflow js and css to form.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  object $object
     * @access public
     * @return string
     */
    public function appendExtendCssAndJS(string $moduleName = '', string $methodName = '', object $object = null): string
    {
        if($this->config->edition == 'open') return '';
        if(!empty($this->app->installing) || !empty($this->app->upgrading)) return '';

        $moduleName = $moduleName ? $moduleName : $this->app->rawModule;
        $methodName = $methodName ? $methodName : $this->app->rawMethod;

        $flow = $this->loadModel('workflow')->getByModule($moduleName);
        if(!$flow) return '';

        $action = $this->loadModel('workflowaction')->getByModuleAndAction($flow->module, $methodName);
        if(!$action || $action->extensionType == 'none') return '';

        $uiID      = $this->loadModel('workflowlayout')->getUIByData($flow->module, !empty($action->action) ? $action->action: '', $object);
        $fieldList = $this->loadModel('workflowaction')->getFields($flow->module, !empty($action->action) ? $action->action: '', true, null, $uiID);

        $html = '';
        if(!empty($flow->css))   $html .= "<style>$flow->css</style>";
        if(!empty($action->css)) $html .= "<style>$action->css</style>";

        $html .= $this->loadModel('flow')->getFormulaScript($moduleName, $action, $fieldList);
        if(!empty($action->linkages)) $html .= $this->flow->getLinkageScript($action, $fieldList, $uiID);
        if(!empty($flow->js))         $html .= "<script>$flow->js</script>";
        if(!empty($action->js))       $html .= "<script>$action->js</script>";

        return $html;
    }

    /**
     * 追加工作流新增的字段，给非formGridPanel组件使用。
     * append workflow field to form.
     *
     * @param  string $position   info|basic
     * @param  object $object
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return array
     */
    public function appendExtendForm(string $position = 'info', object $object = null, string $moduleName = '', string $methodName = ''): array
    {
        if($this->config->edition == 'open') return array();
        if(!empty($this->app->installing) || !empty($this->app->upgrading)) return array();

        $moduleName = $moduleName ? $moduleName : $this->app->rawModule;
        $methodName = $methodName ? $moduleName : $this->app->rawMethod;

        $this->loadModel('flow');
        $this->loadModel('workflowfield');

        $flow = $this->loadModel('workflow')->getByModule($moduleName);
        if(!$flow) return array();

        $action = $this->loadModel('workflowaction')->getByModuleAndAction($flow->module, $methodName);
        if(!$action || $action->extensionType != 'extend') return array();

        $uiID = is_object($object) ? $this->loadModel('workflowlayout')->getUIByData($flow->module, $action->action, $object) : 0;

        $wrapControl  = array('textarea', 'richtext', 'file');
        $fieldList    = $this->workflowaction->getFields($flow->module, $action->action, true, $object, $uiID);
        $layouts      = $this->loadModel('workflowlayout')->getFields($moduleName, $methodName, $uiID);
        $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');

        if($layouts)
        {
            $allFields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($moduleName)->fetchAll('field');
            foreach($fieldList as $fieldName => $field)
            {
                if(isset($allFields[$fieldName])) $field->default = $allFields[$fieldName]->default;
            }

            foreach($fieldList as $key => $field)
            {
                if($field->buildin || !$field->show || !isset($layouts[$field->field]) || (!empty($field->position) && $position != $field->position)) unset($fieldList[$key]);

                if((is_numeric($field->default) || $field->default) && empty($field->defaultValue)) $field->defaultValue = $field->default;
                if($object && empty($object->{$field->field})) $object->{$field->field} = $field->defaultValue;
                if(in_array($field->type, $this->config->workflowfield->numberTypes)) $field = $this->workflowfield->processNumberField($field);

                $field->required = $field->readonly || ($notEmptyRule && strpos(",$field->rules,", ",{$notEmptyRule->id},") !== false);
                $field->control  = $this->flow->buildFormControl($field);
                $field->items    = $field->options ? array_filter($field->options) : null;
                $field->value    = !empty($object) ? zget($object, $field->field, '') : '';
                $field->width    = $field->width != 'auto' ? $field->width : 'full';
            }

            return $fieldList;
        }
        return array();
    }

    /**
     * Process status of an object according to its subStatus.
     *
     * @param  string $module   product | release | story | project | task | bug | testcase | testtask | feedback
     * @param  object $record   a record of above modules.
     * @access public
     * @return string
     */
    public function processStatus(string $module, object $record)
    {
        $moduleName = $this->moduleName;

        return $this->$moduleName->processStatus($module, $record);
    }

    /**
     * Print view file.
     *
     * @param  string    $viewFile
     * @access public
     */
    public function printViewFile(string $viewFile): bool|string
    {
        if(!file_exists($viewFile)) return false;
        if(substr($viewFile, -4) != '.php') return false;
        if(strpos($viewFile, '..') !== false) return false;
        if(strpos($viewFile, '/view/') === false) return false;
        if(strpos($viewFile, $this->app->getModuleRoot()) !== 0 && strpos($viewFile, $this->app->getExtensionRoot()) !== 0) return false;

        $currentPWD = getcwd();
        chdir(dirname($viewFile));

        extract((array)$this->view);
        ob_start();
        include $viewFile;
        $output = ob_get_contents();
        ob_end_clean();

        chdir($currentPWD);

        return $output;
    }

    /**
     * 将工作流扩展的必填字段加到requiredFields里，类似批量操作的会在checkFlow之前手动校验。
     * append workflow extension fields to requiredFields.
     *
     * @param int     $objectID
     * @access public
     * @return void
     */
    public function extendRequireFields(int $objectID = 0)
    {
        if($this->config->edition == 'open') return;

        $uiID         = $this->loadModel('workflowlayout')->getUIByDataID($this->moduleName, $this->methodName, $objectID);
        $fields       = $this->loadModel('workflowaction')->getFields($this->moduleName, $this->methodName, true, null, $uiID);
        $layouts      = $this->loadModel('workflowlayout')->getFields($this->moduleName, $this->methodName, $uiID);
        $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');
        foreach($fields as $field)
        {
            if($field->buildin || !$field->show || !isset($layouts[$field->field])) continue;
            if($notEmptyRule && strpos(",$field->rules,", ",$notEmptyRule->id,") !== false)
            {
                if(!isset($this->config->{$this->moduleName})) $this->config->{$this->moduleName} = new stdclass();
                if(!isset($this->config->{$this->moduleName}->{$this->methodName})) $this->config->{$this->moduleName}->{$this->methodName} = new stdclass();
                if(!isset($this->config->{$this->moduleName}->{$this->methodName}->requiredFields)) $this->config->{$this->moduleName}->{$this->methodName}->requiredFields = '';
                $this->config->{$this->moduleName}->{$this->methodName}->requiredFields .= ",{$field->field}";
            }
        }
    }

    /**
     * Call the functions declared in the zen files.
     *
     * @param  string $method
     * @param  array  $arguments
     * @access public
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        $moduleName = $this->app->getModuleName();
        $zenClass   = $moduleName . 'Zen';

        if(isset($this->{$zenClass}) && is_callable(array($this->{$zenClass}, $method))) return call_user_func_array(array($this->{$zenClass}, $method), $arguments);

        $this->app->triggerError("the module {$moduleName} has no {$method} method", __FILE__, __LINE__, true);
    }

    /**
     * Call the static functions declared in the zen files.
     *
     * @param  string $method
     * @param  array  $arguments
     * @access public
     * @return mixed
     */
    public static function __callStatic(string $method, array $arguments)
    {
        global $app;
        $moduleName = $app->getModuleName();
        $zenClass   = 'ext' . $moduleName . 'Zen';
        if(is_callable("{$zenClass}::{$method}")) return call_user_func_array("{$zenClass}::{$method}", $arguments);

        $zenClass = $moduleName . 'Zen';
        if(is_callable("{$zenClass}::{$method}")) return call_user_func_array("{$zenClass}::{$method}", $arguments);

        $app->triggerError("the module {$moduleName} has no {$method} method", __FILE__, __LINE__, true);
    }
}

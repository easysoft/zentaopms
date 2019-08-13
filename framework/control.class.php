<?php
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
include dirname(__FILE__) . '/base/control.class.php';
class control extends baseControl
{
    /**
     * 企业版部分功能是从然之合并过来的。然之代码中调用loadModel方法时传递了一个非空的appName，在禅道中会导致错误。
     * 调用父类的loadModel方法来避免这个错误。
     * Some codes merged from ranzhi called the function loadModel with a non-empty appName which causes an error in zentao.
     * Call the parent function with empty appName to avoid this error.
     *
     * @param   string  $moduleName 模块名，如果为空，使用当前模块。The module name, if empty, use current module's name.
     * @param   string  $appName    The app name, if empty, use current app's name.
     * @access  public
     * @return  object|bool 如果没有model文件，返回false，否则返回model对象。If no model file, return false, else return the model object.
     */
    public function loadModel($moduleName = '', $appName = '')
    {
        return parent::loadModel($moduleName);
    }

    /**
     * 设置视图文件：主视图文件，扩展视图文件， 站点扩展视图文件，以及钩子脚本。
     * Set view files: the main file, extension view file, site extension view file and hook files.
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

        $viewType     = ($this->viewType == 'mhtml' or $this->viewType == 'xhtml') ? 'html' : $this->viewType;
        $mainViewFile = $modulePath . 'view' . DS . $this->devicePrefix . $methodName . '.' . $viewType . '.php';

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
            $siteExtViewFile   = empty($viewExtPath['site']) ? '' : $viewExtPath['site'] . $this->devicePrefix . $methodName . ".{$viewType}.php";

            $viewFile = file_exists($commonExtViewFile) ? $commonExtViewFile : $mainViewFile;
            $viewFile = (!empty($siteExtViewFile) and file_exists($siteExtViewFile)) ? $siteExtViewFile : $viewFile;
            if(!is_file($viewFile))
            {
                die(js::error($this->lang->notPage) . js::locate('back'));
            }

            $commonExtHookFiles = glob($viewExtPath['common'] . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");
            $siteExtHookFiles   = empty($viewExtPath['site']) ? '' : glob($viewExtPath['site'] . $this->devicePrefix . $methodName . ".*.{$viewType}.hook.php");
            $extHookFiles       = array_merge((array) $commonExtHookFiles, (array) $siteExtHookFiles);
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
    public function parseDefault($moduleName, $methodName)
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
         * Change the dir to the view file to keep the relative pathes work.
         */
        $currentPWD = getcwd();
        chdir(dirname($viewFile));

        /**
         * 使用extract安定ob方法渲染$viewFile里面的代码。
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
     * Execute hooks of a method.
     *
     * @param  int    $objectID     The id of an object. The object maybe a bug | build | feedback | product | productplan | project | release | story | task | testcase | testsuite | testtask.
     * @access public
     * @return void
     */
    public function executeHooks($objectID)
    {
        if(!isset($this->config->bizVersion)) return false;

        $flow   = $this->loadModel('workflow')->getByModule($this->moduleName);
        $action = $this->loadModel('workflowaction')->getByModuleAndAction($this->moduleName, $this->methodName);
        if($flow && $action) $this->loadModel('workflowhook')->execute($flow, $action, $objectID);
    }

    /**
     * Build operate menu of a method.
     *
     * @param  object $object    product|project|productplan|release|build|story|task|bug|testtask|testcase|testsuite
     * @param  string $displayOn view|browse
     * @access public
     * @return void
     */
    public function buildOperateMenu($object, $displayOn = 'view')
    {
        if(!isset($this->config->bizVersion)) return false;

        $flow = $this->loadModel('workflow')->getByModule($this->moduleName);
        return $this->loadModel('flow')->buildOperateMenu($flow, $object, $displayOn);
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
     * @access public
     * @return void
     */
    public function printExtendFields($object, $type, $extras = '')
    {
        if(!isset($this->config->bizVersion)) return false;

        echo $this->loadModel('flow')->printFields($this->moduleName, $this->methodName, $object, $type, $extras);
    }

    /**
     * Process status of an object according to its subStatus.
     *
     * @param  string $module   product | release | story | project | task | bug | testcase | testtask | feedback
     * @param  object $record   a record of above modules.
     * @access public
     * @return string
     */
    public function processStatus($module, $record)
    {
        $moduleName = $this->moduleName;

        return $this->$moduleName->processStatus($module, $record);
    }
}

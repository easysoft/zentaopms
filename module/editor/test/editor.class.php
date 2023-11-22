<?php
class editorTest
{

    /**
     * Construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('editor');
    }

    /**
     * Test for get module files.
     *
     * @param  string    $moduleName
     * @access public
     * @return 1|0
     */
    public function getModuleFilesTest($moduleName)
    {
        $moduleFiles = $this->objectModel->getModuleFiles($moduleName);
        $modulePath  = $this->objectModel->app->getModulePath('', $moduleName);
        if(!isset($moduleFiles[$modulePath])) return 0;
        if(!isset($moduleFiles[$modulePath][$modulePath . 'model.php'])) return 0;
        if(!isset($moduleFiles[$modulePath][$modulePath . 'model.php'][$modulePath . 'model.php/create'])) return 0;
        return 1;
    }

    /**
     * Test for get extension files.
     *
     * @param  string    $moduleName
     * @access public
     * @return 1|0
     */
    public function getExtensionFilesTest($moduleName)
    {
        $files   = $this->objectModel->getExtensionFiles($moduleName);
        $edition = $this->objectModel->config->edition;

        if($edition == 'open') return 1;

        $extensionRoot = $this->objectModel->app->getExtensionRoot();
        $extensionPath = $extensionRoot . $edition . DS . $moduleName . DS . 'ext' . DS . 'control' . DS;
        if(!isset($files[$edition][$extensionPath])) return 0;
        return 1;
    }

    /**
     * Test for get two grade files.
     *
     * @access public
     * @return 1|0
     */
    public function getTwoGradeFilesTest()
    {
        $extensionFullDir = $this->objectModel->app->getModulePath('', 'todo');
        $files = $this->objectModel->getTwoGradeFiles($extensionFullDir);
        if(!isset($files[$extensionFullDir . 'lang'])) return 0;
        if(!isset($files[$extensionFullDir . 'lang'][$extensionFullDir . 'lang' . DS . 'zh-cn.php'])) return 0;
        return 1;
    }

    /**
     * Test for analysis.
     *
     * @access public
     * @return 1|0
     */
    public function analysisTest()
    {
        $fileName = $this->objectModel->app->getModulePath('', 'todo') . 'control.php';
        $objects  = $this->objectModel->analysis($fileName);
        if(!isset($objects[$fileName . '/create'])) return 0;
        return 1;
    }

    /**
     * Test for print tree.
     *
     * @access public
     * @return array
     */
    public function printTreeTest(): array
    {
        $files = $this->objectModel->getModuleFiles('todo');
        $tree  = $this->objectModel->printTree($files);
        return $tree;
    }

    /**
     * Test for add link for dir.
     *
     * @access public
     * @return string
     */
    public function addLink4DirTest()
    {
        $result     = '';
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');

        $controlPath = $modulePath . 'control.php';
        $link        = $this->objectModel->addLink4Dir($controlPath);
        $result     .= (strpos($link->actions['items'][0]['data-url'], 'newPage') === false ? 0 : 1) . ',';

        $modelPath = $modulePath . 'model.php';
        $link      = $this->objectModel->addLink4Dir($modelPath);
        $result   .= (strpos($link->actions['items'][0]['data-url'], 'newMethod') === false ? 0 : 1) . ',';

        $langPath = $modulePath . 'lang';
        $link     = $this->objectModel->addLink4Dir($langPath);
        $result  .= (empty($link->actions['items']) ? 1 : 0) . ',';

        $edition = $this->objectModel->config->edition;
        if($edition == 'open') return $result . '1,1,1,1,1';

        $extensionRoot  = $this->objectModel->app->getExtensionRoot();
        $extensionPath  = $extensionRoot . $edition . DS . 'todo' . DS . 'ext' . DS;
        $extControlPath = $extensionPath . 'control';
        $link           = $this->objectModel->addLink4Dir($extControlPath);
        $result        .= (strpos($link->actions['items'][0]['data-url'], 'newExtend') === false ? 0 : 1) . ',';

        $extModelPath = $extensionPath . 'model';
        $link         = $this->objectModel->addLink4Dir($extModelPath);
        $result      .= (strpos($link->actions['items'][0]['data-url'], 'newExtend') === false ? 0 : 1) . ',';

        $extJSPath = $extensionPath . 'js';
        $link      = $this->objectModel->addLink4Dir($extJSPath);
        $result   .= (strpos($link->actions['items'][0]['data-url'], 'newJS') === false ? 0 : 1) . ',';

        $extCSSPath = $extensionPath . 'css';
        $link       = $this->objectModel->addLink4Dir($extCSSPath);
        $result    .= (strpos($link->actions['items'][0]['data-url'], 'newCSS') === false ? 0 : 1) . ',';

        $extLangPath = $extensionPath . 'lang';
        $link        = $this->objectModel->addLink4Dir($extLangPath);
        $result     .= (strpos($link->actions['items'][0]['data-url'], 'lang') === false ? 0 : 1);
        return $result;
    }

    /**
     * Test for add link for file.
     *
     * @access public
     * @return string
     */
    public function addLink4FileTest()
    {
        $result     = '';
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');

        $viewPath = $modulePath . 'view' . DS . 'create.html.php';
        $link     = $this->objectModel->addLink4File($viewPath, 'create.html.php');
        $result  .= ((str_contains($link->actions['items'][0]['data-url'], 'override') && str_contains($link->actions['items'][1]['data-url'], 'newHook')) ? 1 : 0) . ',';

        $controlPath = $modulePath . 'control.php' . DS . 'create';
        $link      = $this->objectModel->addLink4File($controlPath, 'create');
        $result   .= (str_contains($link->actions['items'][0]['data-url'], 'extendControl') ? 1 : 0) . ',';

        $modelPath = $modulePath . 'model.php' . DS . 'create';
        $link      = $this->objectModel->addLink4File($modelPath, 'create');
        $result   .= (str_contains($link->actions['items'][0]['data-url'], 'extendModel') ? 1 : 0) . ',';

        $langPath = $modulePath . 'lang' . DS . 'zh-cn.php';
        $link     = $this->objectModel->addLink4File($langPath, 'zh-cn.php');
        $result  .= ((str_contains($link->actions['items'][1]['data-url'], 'newzh_cn') && str_contains($link->actions['items'][0]['data-url'], 'extendOther')) ? 1 : 0) . ',';

        $configPath = $modulePath . 'config.php';
        $link       = $this->objectModel->addLink4File($configPath, 'config.php');
        $result    .= ((str_contains($link->actions['items'][1]['data-url'], 'newConfig') && str_contains($link->actions['items'][0]['data-url'], 'extendOther')) ? 1 : 0) . ',';

        $edition = $this->objectModel->config->edition;
        if($edition == 'open') return $result . '1,1,1,1,1,1';

        $extensionRoot  = $this->objectModel->app->getExtensionRoot();
        $extensionPath  = $extensionRoot . $edition . DS . 'todo' . DS . 'ext' . DS;
        $extControlPath = $extensionPath . 'control' . DS . 'create.php';
        $link           = $this->objectModel->addLink4File($extControlPath, 'create.php');
        $result        .= ((str_contains($link->actions['items'][1]['data-url'], 'delete') && str_contains($link->actions['items'][0]['data-url'], 'edit')) ? 1 : 0) . ',';

        $extModelPath = $extensionPath . 'model' . DS . 'zentaobiz.class.php';
        $link         = $this->objectModel->addLink4File($extModelPath, 'zentaobiz.class.php');
        $result      .= ((str_contains($link->actions['items'][1]['data-url'], 'delete') && str_contains($link->actions['items'][0]['data-url'], 'edit')) ? 1 : 0) . ',';

        $extJSPath = $extensionPath . 'js' . DS . 'create' . DS . 'zentaobiz.js';
        $link      = $this->objectModel->addLink4File($extJSPath, 'zentaobiz.js');
        $result   .= ((str_contains($link->actions['items'][1]['data-url'], 'delete') && str_contains($link->actions['items'][0]['data-url'], 'edit')) ? 1 : 0) . ',';

        $extCSSPath = $extensionPath . 'css' . DS . 'create' . DS . 'zentaobiz.css';
        $link       = $this->objectModel->addLink4File($extCSSPath, 'zentaobiz.css');
        $result    .= ((str_contains($link->actions['items'][1]['data-url'], 'delete') && str_contains($link->actions['items'][0]['data-url'], 'edit')) ? 1 : 0) . ',';

        $extLangPath = $extensionPath . 'lang' . DS . 'zh-cn' . DS . 'zentaobiz.php';
        $link        = $this->objectModel->addLink4File($extLangPath, 'zentaobiz.php');
        $result     .= ((str_contains($link->actions['items'][1]['data-url'], 'delete') && str_contains($link->actions['items'][0]['data-url'], 'edit')) ? 1 : 0) . ',';

        $extConfigPath = $extensionPath . 'config' . DS . 'zentaobiz.php';
        $link          = $this->objectModel->addLink4File($extConfigPath, 'zentaobiz.php');
        $result       .= ((str_contains($link->actions['items'][1]['data-url'], 'delete') && str_contains($link->actions['items'][0]['data-url'], 'edit')) ? 1 : 0);
        return $result;
    }

    /**
     * Test for get extend link.
     *
     * @access public
     * @return 1|0
     */
    public function getExtendLinkTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $viewPath   = $modulePath . 'view' . DS . 'create.html.php';
        $link       = $this->objectModel->getExtendLink($viewPath, 'extendOther');
        return (strpos($link, 'edit') !== false && strpos($link, 'extendOther') !== false) ? 1 : 0;
    }

    /**
     * Test for get api link.
     *
     * @access public
     * @return 1|0
     */
    public function getAPILinkTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $modelPath  = $modulePath . 'model.php' . DS . 'create';
        $link       = $this->objectModel->getAPILink($modelPath, 'extendModel');
        return (strpos($link, 'debug') !== false && strpos($link, 'extendModel') !== false) ? 1 : 0;
    }

    /**
     * Test for extend model.
     *
     * @access public
     * @return 1|0
     */
    public function extendModelTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $modelPath  = $modulePath . 'model.php' . DS . 'create';
        $content    = $this->objectModel->extendModel($modelPath);
        return strpos($content, 'public function create(') === false ? 0 : 1;
    }

    /**
     * Test for extend control.
     *
     * @access public
     * @return string
     */
    public function extendControlTest()
    {
        $result     = '';
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $filePath   = $modulePath . 'control.php' . DS . 'create';
        $content    = $this->objectModel->extendControl($filePath, 'yes');
        $result    .= strpos($content, 'class mytodo extends todo') !== false ? '1,' : '0,';

        $content = $this->objectModel->extendControl($filePath, 'no');
        $result .= strpos($content, 'class todo extends control') !== false ? 1 : 0;
        return $result;
    }

    /**
     * Test for new control.
     *
     * @access public
     * @return 1|0
     */
    public function newControlTest()
    {
        $filePath = $this->objectModel->app->getModulePath('', 'todo') . 'control.php' . DS . 'create';
        $content  = $this->objectModel->newControl($filePath);
        return (strpos($content, 'class todo extends control') !== false && strpos($content, 'public function create(') !== false) ? 1 : 0;
    }

    /**
     * Test for get param.
     *
     * @access public
     * @return 1|0
     */
    public function getParamTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo') . 'control.php';
        include $modulePath;
        $params = $this->objectModel->getParam('todo', 'create');
        return $params == "\$date='today', \$userID='', \$from='todo'" ? 1 : 0;
    }

    /**
     * Test for get method code.
     *
     * @access public
     * @return 1|0
     */
    public function getMethodCodeTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo') . 'control.php';
        include $modulePath;
        $code = $this->objectModel->getMethodCode('todo', 'create');
        return strpos($code, "public function create(\$date = 'today', \$userID = '', \$from = 'todo')") !== false ? 1 : 0;
    }

    /**
     * Test for get save path.
     *
     * @access public
     * @return string
     */
    public function getSavePathTest()
    {
        $_POST['fileName'] = 'test.php';
        $result     = '';
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $extPath    = $this->objectModel->app->getExtensionRoot() . 'custom' . DS;

        $filePath = $modulePath . 'model.php' . DS . 'create';
        $path     = $this->objectModel->getSavePath($filePath, 'extendModel');
        $result  .= $path == $extPath . 'todo' . DS . 'ext' . DS . 'model' . DS . 'test.php' ? '1,' : '0,';

        $filePath = $modulePath . 'control.php' . DS . 'create';
        $path     = $this->objectModel->getSavePath($filePath, 'extendControl');
        $result  .= $path == $extPath . 'todo' . DS . 'ext' . DS . 'control' . DS . 'create.php' ? '1,' : '0,';

        $filePath = $modulePath . 'view' . DS . 'create.html.php';
        $path     = $this->objectModel->getSavePath($filePath, 'override');
        $result  .= $path == $extPath . 'todo' . DS . 'ext' . DS . 'view' . DS . 'create.html.php' ? '1,' : '0,';

        $filePath = $modulePath . 'config.php';
        $path     = $this->objectModel->getSavePath($filePath, 'extendOther');
        $result  .= $path == $extPath . 'todo' . DS . 'ext' . DS . 'config' . DS . 'test.php' ? '1,' : '0,';

        $filePath = $modulePath . 'lang' . DS . 'zh-cn.php';
        $path     = $this->objectModel->getSavePath($filePath, 'extendOther');
        $result  .= $path == $extPath . 'todo' . DS . 'ext' . DS . 'lang' . DS . 'zh-cn' . DS . 'test.php' ? '1,' : '0,';

        $_POST['fileName'] = 'create.html.hook.php';
        $filePath = $modulePath . 'view' . DS . 'create.html.php';
        $path     = $this->objectModel->getSavePath($filePath, 'newhook');
        $result  .= $path == $extPath . 'todo' . DS . 'ext' . DS . 'view' . DS . 'create.html.hook.php' ? '1,' : '0,';

        $_POST['fileName'] = 'test.php';
        $filePath = $modulePath . 'control.php';
        $path     = $this->objectModel->getSavePath($filePath, 'newmethod');
        $result  .= $path == $extPath . 'todo' . DS . 'ext' . DS . 'control' . DS . 'test.php' ? '1,' : '0,';

        $filePath = $extPath . 'todo' . DS . 'ext' . DS . 'control';
        $path     = $this->objectModel->getSavePath($filePath, 'newextend');
        $result  .= $path == $extPath . 'todo' . DS . 'ext' . DS . 'control' . DS . 'test.php' ? '1,' : '0,';

        $filePath = $modulePath . 'config.php';
        $path     = $this->objectModel->getSavePath($filePath, 'newconfig');
        $result  .= $path == $extPath . 'todo' . DS . 'ext' . DS . 'config' . DS . 'test.php' ? '1,' : '0,';

        $_POST['fileName'] = 'test.js';
        $filePath = $extPath . 'todo' . DS . 'ext' . DS . 'js' . DS . 'create' . DS;
        $path     = $this->objectModel->getSavePath($filePath, 'newJS');
        $result  .= $path == $extPath . 'todo' . DS . 'ext' . DS . 'js' . DS . 'create' . DS . 'test.js' ? '1,' : '0,';

        $_POST['fileName'] = 'test.css';
        $filePath = $extPath . 'todo' . DS . 'ext' . DS . 'css' . DS . 'create' . DS;
        $path     = $this->objectModel->getSavePath($filePath, 'newCSS');
        $result  .= $path == $extPath . 'todo' . DS . 'ext' . DS . 'css' . DS . 'create' . DS . 'test.css' ? '1' : '0';

        return $result;
    }

    /**
     * Test for get class name by path.
     *
     * @access public
     * @return string
     */
    public function getClassNameByPathTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $extPath    = $this->objectModel->app->getExtensionRoot() . 'custom' . DS;

        $result    = '';
        $filePath  = $modulePath . 'model.php' . DS . 'create';
        $className = $this->objectModel->getClassNameByPath($filePath);
        $result   .= $className == 'todo' ? '1,' : '0,';
        $filePath  = $extPath . 'todo' . DS . 'ext' . DS . 'control';
        $className = $this->objectModel->getClassNameByPath($filePath);
        $result   .= $className == 'todo' ? '1' : '0';
        return $result;
    }
}

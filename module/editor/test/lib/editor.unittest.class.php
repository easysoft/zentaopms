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
     * Test analysis method with control.php file.
     *
     * @access public
     * @return array
     */
    public function analysisControlTest()
    {
        $fileName = $this->objectModel->app->getModulePath('', 'todo') . 'control.php';
        $objects  = $this->objectModel->analysis($fileName);

        return array(
            'hasCreateMethod' => isset($objects[$fileName . '/create']) ? 1 : 0,
            'methodCount'     => count($objects),
            'isArray'         => is_array($objects) ? 1 : 0
        );
    }

    /**
     * Test analysis method with model.php file.
     *
     * @access public
     * @return array
     */
    public function analysisModelTest()
    {
        $fileName = $this->objectModel->app->getModulePath('', 'todo') . 'model.php';
        $objects  = $this->objectModel->analysis($fileName);

        return array(
            'hasCreateMethod' => isset($objects[$fileName . '/create']) ? 1 : 0,
            'methodCount'     => count($objects),
            'isArray'         => is_array($objects) ? 1 : 0
        );
    }

    /**
     * Test analysis method with non-existent file.
     *
     * @access public
     * @return int
     */
    public function analysisNonExistentFileTest()
    {
        $fileName = '/nonexistent/path/test.php';
        try {
            $objects = $this->objectModel->analysis($fileName);
            return empty($objects) ? 0 : 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Test analysis method with empty file path.
     *
     * @access public
     * @return int
     */
    public function analysisEmptyPathTest()
    {
        $fileName = '';
        try {
            $objects = $this->objectModel->analysis($fileName);
            return empty($objects) ? 0 : 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Test analysis method return structure.
     *
     * @access public
     * @return array
     */
    public function analysisStructureTest()
    {
        $fileName = $this->objectModel->app->getModulePath('', 'todo') . 'control.php';
        $objects  = $this->objectModel->analysis($fileName);

        $hasCorrectStructure = 1;
        foreach($objects as $key => $methodName)
        {
            if(strpos($key, $fileName . '/') !== 0)
            {
                $hasCorrectStructure = 0;
                break;
            }
            if(!is_string($methodName))
            {
                $hasCorrectStructure = 0;
                break;
            }
        }

        return array(
            'hasCorrectStructure' => $hasCorrectStructure,
            'keyFormat'           => !empty($objects) && strpos(key($objects), '/') !== false ? 1 : 0,
            'valueType'           => !empty($objects) && is_string(current($objects)) ? 1 : 0
        );
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
     * Test for add link for dir - control.php directory.
     *
     * @access public
     * @return array
     */
    public function addLink4DirControlTest()
    {
        $modulePath  = $this->objectModel->app->getModulePath('', 'todo');
        $controlPath = $modulePath . 'control.php';
        $link        = $this->objectModel->addLink4Dir($controlPath);

        return array(
            'hasNewPageLink' => strpos($link->actions['items'][0]['data-url'], 'newPage') !== false ? 1 : 0,
            'text'           => $link->text,
            'id'             => $link->id
        );
    }

    /**
     * Test for add link for dir - model.php directory.
     *
     * @access public
     * @return array
     */
    public function addLink4DirModelTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $modelPath  = $modulePath . 'model.php';
        $link       = $this->objectModel->addLink4Dir($modelPath);

        return array(
            'hasNewMethodLink' => strpos($link->actions['items'][0]['data-url'], 'newMethod') !== false ? 1 : 0,
            'text'             => $link->text,
            'id'               => $link->id
        );
    }

    /**
     * Test for add link for dir - lang directory.
     *
     * @access public
     * @return array
     */
    public function addLink4DirLangTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $langPath   = $modulePath . 'lang';
        $link       = $this->objectModel->addLink4Dir($langPath);

        return array(
            'hasEmptyActions' => empty($link->actions['items']) ? 1 : 0,
            'text'            => $link->text,
            'id'              => $link->id
        );
    }

    /**
     * Test for add link for dir - JS parent directory in extension.
     *
     * @access public
     * @return array
     */
    public function addLink4DirJSTest()
    {
        $extensionRoot = $this->objectModel->app->getExtensionRoot();
        $extensionPath = $extensionRoot . 'custom' . DS . 'todo' . DS . 'ext' . DS;
        $extJSPath     = $extensionPath . 'js' . DS . 'create';
        $link          = $this->objectModel->addLink4Dir($extJSPath);

        return array(
            'hasNewJSLink' => strpos($link->actions['items'][0]['data-url'], 'newJS') !== false ? 1 : 0,
            'text'         => $link->text,
            'id'           => $link->id
        );
    }

    /**
     * Test for add link for dir - CSS parent directory in extension.
     *
     * @access public
     * @return array
     */
    public function addLink4DirCSSTest()
    {
        $extensionRoot = $this->objectModel->app->getExtensionRoot();
        $extensionPath = $extensionRoot . 'custom' . DS . 'todo' . DS . 'ext' . DS;
        $extCSSPath    = $extensionPath . 'css' . DS . 'create';
        $link          = $this->objectModel->addLink4Dir($extCSSPath);

        return array(
            'hasNewCSSLink' => strpos($link->actions['items'][0]['data-url'], 'newCSS') !== false ? 1 : 0,
            'text'          => $link->text,
            'id'            => $link->id
        );
    }

    /**
     * Test for add link for dir - other extension directory.
     *
     * @access public
     * @return array
     */
    public function addLink4DirExtTest()
    {
        $extensionRoot  = $this->objectModel->app->getExtensionRoot();
        $extensionPath  = $extensionRoot . 'custom' . DS . 'todo' . DS . 'ext' . DS;
        $extControlPath = $extensionPath . 'control';
        $link           = $this->objectModel->addLink4Dir($extControlPath);

        return array(
            'hasNewExtendLink' => strpos($link->actions['items'][0]['data-url'], 'newExtend') !== false ? 1 : 0,
            'text'             => $link->text,
            'id'               => $link->id
        );
    }

    /**
     * Test for add link for dir - empty path edge case.
     *
     * @access public
     * @return array
     */
    public function addLink4DirEmptyTest()
    {
        $emptyPath = '';
        $link      = $this->objectModel->addLink4Dir($emptyPath);

        return array(
            'hasBasicStructure' => (isset($link->id) && isset($link->text) && isset($link->actions)) ? 1 : 0,
            'text'              => $link->text,
            'id'                => $link->id
        );
    }

    /**
     * Test for add link for dir - special characters in path.
     *
     * @access public
     * @return array
     */
    public function addLink4DirSpecialCharsTest()
    {
        $specialPath = '/tmp/test-module_with@special#chars$/model.php';
        $link        = $this->objectModel->addLink4Dir($specialPath);

        return array(
            'hasValidId' => !empty($link->id) && strlen($link->id) == 32 ? 1 : 0, // MD5 hash is 32 characters
            'text'       => $link->text,
            'id'         => $link->id
        );
    }

    /**
     * Test addLink4File for view directory files.
     *
     * @access public
     * @return array
     */
    public function addLink4FileViewTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $viewPath   = $modulePath . 'view' . DS . 'create.html.php';
        $link       = $this->objectModel->addLink4File($viewPath, 'create.html.php');

        return array(
            'hasOverrideLink' => str_contains($link->actions['items'][0]['data-url'], 'override') ? 1 : 0,
            'hasNewHookLink'  => str_contains($link->actions['items'][1]['data-url'], 'newHook') ? 1 : 0,
            'textMatch'       => ($link->text === 'create.html.php') ? 1 : 0,
            'idLength'        => strlen($link->id)
        );
    }

    /**
     * Test addLink4File for control.php methods.
     *
     * @access public
     * @return array
     */
    public function addLink4FileControlTest()
    {
        $modulePath  = $this->objectModel->app->getModulePath('', 'todo');
        $controlPath = $modulePath . 'control.php' . DS . 'create';
        $link        = $this->objectModel->addLink4File($controlPath, 'create');

        return array(
            'hasExtendControlLink' => str_contains($link->actions['items'][0]['data-url'], 'extendControl') ? 1 : 0,
            'hasApiLink'           => str_contains($link->actions['items'][1]['data-url'], 'api') ? 1 : 0,
            'actionsCount'         => count($link->actions['items'])
        );
    }

    /**
     * Test addLink4File for model.php methods.
     *
     * @access public
     * @return array
     */
    public function addLink4FileModelTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $modelPath  = $modulePath . 'model.php' . DS . 'create';
        $link       = $this->objectModel->addLink4File($modelPath, 'create');

        return array(
            'hasExtendModelLink' => str_contains($link->actions['items'][0]['data-url'], 'extendModel') ? 1 : 0,
            'hasApiLink'         => str_contains($link->actions['items'][1]['data-url'], 'api') ? 1 : 0,
            'actionsCount'       => count($link->actions['items'])
        );
    }

    /**
     * Test addLink4File for ext directory files.
     *
     * @access public
     * @return array
     */
    public function addLink4FileExtTest()
    {
        $extensionRoot  = $this->objectModel->app->getExtensionRoot();
        $extensionPath  = $extensionRoot . 'custom' . DS . 'todo' . DS . 'ext' . DS;
        $extControlPath = $extensionPath . 'control' . DS . 'create.php';
        $link           = $this->objectModel->addLink4File($extControlPath, 'create.php');

        return array(
            'hasEditLink'    => str_contains($link->actions['items'][0]['data-url'], 'edit') ? 1 : 0,
            'hasDeleteLink'  => isset($link->actions['items'][1]['url']) && str_contains($link->actions['items'][1]['url'], 'delete') ? 1 : 0,
            'confirmExists'  => isset($link->actions['items'][1]['data-confirm']) ? 1 : 0
        );
    }

    /**
     * Test addLink4File for lang directory files.
     *
     * @access public
     * @return array
     */
    public function addLink4FileLangTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $langPath   = $modulePath . 'lang' . DS . 'zh-cn.php';
        $link       = $this->objectModel->addLink4File($langPath, 'zh-cn.php');

        return array(
            'hasExtendOtherLink' => str_contains($link->actions['items'][0]['data-url'], 'extendOther') ? 1 : 0,
            'hasNewLangLink'     => str_contains($link->actions['items'][1]['data-url'], 'newzh_cn') ? 1 : 0,
            'actionsCount'       => count($link->actions['items'])
        );
    }

    /**
     * Test addLink4File for config.php file.
     *
     * @access public
     * @return array
     */
    public function addLink4FileConfigTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $configPath = $modulePath . 'config.php';
        $link       = $this->objectModel->addLink4File($configPath, 'config.php');

        return array(
            'hasExtendOtherLink' => str_contains($link->actions['items'][0]['data-url'], 'extendOther') ? 1 : 0,
            'hasNewConfigLink'   => str_contains($link->actions['items'][1]['data-url'], 'newConfig') ? 1 : 0,
            'actionsCount'       => count($link->actions['items'])
        );
    }

    /**
     * Test addLink4File for empty file path edge case.
     *
     * @access public
     * @return array
     */
    public function addLink4FileEmptyTest()
    {
        $emptyPath = '';
        $link      = $this->objectModel->addLink4File($emptyPath, '');

        return array(
            'hasBasicStructure' => (isset($link->id) && isset($link->name) && isset($link->text) && isset($link->actions)) ? 1 : 0,
            'idExists'          => !empty($link->id) ? 1 : 0,
            'actionsExists'     => isset($link->actions['items']) ? 1 : 0
        );
    }

    /**
     * Test addLink4File for special characters in path.
     *
     * @access public
     * @return array
     */
    public function addLink4FileSpecialCharsTest()
    {
        $specialFile = 'test@#$%^&*()_+-=[]{}|;:,.<>?file.php';
        $specialPath = '/tmp/special/path';
        $link        = $this->objectModel->addLink4File($specialPath, $specialFile);

        return array(
            'hasValidId'     => !empty($link->id) && strlen($link->id) === 32 ? 1 : 0,
            'namePreserved'  => ($link->name === $specialFile) ? 1 : 0,
            'textPreserved'  => ($link->text === $specialFile) ? 1 : 0
        );
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
     * @param  string    $filePath
     * @param  string    $action
     * @access public
     * @return mixed
     */
    public function getAPILinkTest($filePath = '', $action = '')
    {
        if(empty($filePath))
        {
            $modulePath = $this->objectModel->app->getModulePath('', 'todo');
            $filePath   = $modulePath . 'model.php' . DS . 'create';
        }
        if(empty($action)) $action = 'extendModel';

        $link = $this->objectModel->getAPILink($filePath, $action);
        if(dao::isError()) return dao::getError();

        return array(
            'link'           => $link,
            'hasDebug'       => strpos($link, 'debug') !== false ? 1 : 0,
            'hasAction'      => strpos($link, $action) !== false ? 1 : 0,
            'hasFilePath'    => strpos($link, 'filePath=') !== false ? 1 : 0,
            'isValidLink'    => filter_var($link, FILTER_VALIDATE_URL) !== false ? 1 : 0,
            'linkLength'     => strlen($link)
        );
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
     * Test extendModel with todo create method.
     *
     * @access public
     * @return array
     */
    public function extendModelCreateTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $modelPath  = $modulePath . 'model.php' . DS . 'create';
        $content    = $this->objectModel->extendModel($modelPath);

        return array(
            'hasPhpTag'         => (strpos($content, '<?php') !== false) ? 1 : 0,
            'hasMethodSignature' => (strpos($content, 'public function create(') !== false) ? 1 : 0,
            'hasParentCall'     => (strpos($content, 'parent::create(') !== false) ? 1 : 0
        );
    }

    /**
     * Test extendModel with todo edit method.
     *
     * @access public
     * @return array
     */
    public function extendModelEditTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $modelPath  = $modulePath . 'model.php' . DS . 'edit';
        $content    = $this->objectModel->extendModel($modelPath);

        return array(
            'hasPhpTag'    => (strpos($content, '<?php') !== false) ? 1 : 0,
            'hasMethodName' => (strpos($content, 'function edit') !== false) ? 1 : 0
        );
    }

    /**
     * Test extendModel parent call verification.
     *
     * @access public
     * @return array
     */
    public function extendModelParentCallTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $modelPath  = $modulePath . 'model.php' . DS . 'delete';
        $content    = $this->objectModel->extendModel($modelPath);

        return array(
            'hasParentCall' => (strpos($content, 'parent::delete(') !== false) ? 1 : 0
        );
    }

    /**
     * Test extendModel syntax validation.
     *
     * @access public
     * @return array
     */
    public function extendModelSyntaxTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $modelPath  = $modulePath . 'model.php' . DS . 'getById';
        $content    = $this->objectModel->extendModel($modelPath);

        return array(
            'hasValidSyntax' => (strpos($content, '<?php') !== false && strpos($content, 'public function') !== false) ? 1 : 0
        );
    }

    /**
     * Test extendModel parameter handling.
     *
     * @access public
     * @return array
     */
    public function extendModelParameterTest()
    {
        $modulePath = $this->objectModel->app->getModulePath('', 'todo');
        $modelPath  = $modulePath . 'model.php' . DS . 'update';
        $content    = $this->objectModel->extendModel($modelPath);

        return array(
            'hasCorrectParams' => (strpos($content, 'parent::update(') !== false) ? 1 : 0
        );
    }

    /**
     * Test for extend control.
     *
     * @param  string    $filePath
     * @param  string    $isExtends
     * @access public
     * @return array
     */
    public function extendControlTest($filePath = '', $isExtends = '')
    {
        if(empty($filePath))
        {
            $modulePath = $this->objectModel->app->getModulePath('', 'todo');
            $filePath   = $modulePath . 'control.php' . DS . 'create';
        }
        if(empty($isExtends)) $isExtends = 'yes';

        try {
            $content = $this->objectModel->extendControl($filePath, $isExtends);
            if(dao::isError()) return dao::getError();

            $result = array(
                'content' => $content,
                'length'  => strlen($content),
                'hasPhpTag' => strpos($content, '<?php') !== false ? 1 : 0
            );

            if($isExtends == 'yes')
            {
                $className = basename(dirname(dirname($filePath)));
                $result['hasMyClass'] = strpos($content, "class my$className extends $className") !== false ? 1 : 0;
                $result['hasImportControl'] = strpos($content, "helper::importControl('$className')") !== false ? 1 : 0;
            }
            else
            {
                $className = basename(dirname(dirname($filePath)));
                $result['hasDirectClass'] = strpos($content, "class $className extends control") !== false ? 1 : 0;
                $result['hasMethodCode'] = strpos($content, 'public function') !== false ? 1 : 0;
            }

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage(), 'hasError' => 1);
        }
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
        return $params == "\$date='today', \$from='todo'" ? 1 : 0;
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
        return strpos($code, "public function create(") !== false ? 1 : 0;
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
     * Test for save method.
     *
     * @param  string    $filePath
     * @access public
     * @return string|bool
     */
    public function saveTest(string $filePath): string|bool
    {
        $_POST['fileContent'] = "<?php\n";

        return $this->objectModel->save($filePath);
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

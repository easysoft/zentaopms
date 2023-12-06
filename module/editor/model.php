<?php
/**
 * The model file of editor module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class editorModel extends model
{
    /**
     * Get module files, contain control's methods and model's method but except ext.
     *
     * @param  string    $moduleName
     * @access public
     * @return array
     */
    public function getModuleFiles(string $moduleName): array
    {
        $allModules = array();
        $modulePath = $this->app->getModulePath('', $moduleName);
        foreach($this->config->editor->sort as $name)
        {
            $moduleFullFile = $modulePath . $name;
            if(!file_exists($moduleFullFile)) continue;
            if($name == 'control.php' or $name == 'model.php')
            {
                $allModules[$modulePath][$moduleFullFile] = $this->analysis($moduleFullFile);
            }
            elseif(is_dir($moduleFullFile))
            {
                $ext = ($name == 'js' or $name == 'css') ? $name : 'php';
                foreach(glob($moduleFullFile . DS . "*.$ext") as $fileName) $allModules[$modulePath][$moduleFullFile][$fileName] = basename($fileName);
            }
            else
            {
                $allModules[$modulePath][$moduleFullFile] = $name;
            }
        }
        $allModules += $this->getExtensionFiles($moduleName);
        return $allModules;
    }

    /**
     * Get extension files.
     *
     * @param  string    $moduleName
     * @access public
     * @return array
     */
    public function getExtensionFiles(string $moduleName): array
    {
        $extensionList = array();
        foreach($this->config->editor->extSort as $ext)
        {
            $extModulePaths = $this->app->getModuleExtPath($moduleName, $ext);
            foreach($extModulePaths as $extType => $extensionFullDir)
            {
                if(empty($extensionFullDir) or !is_dir($extensionFullDir)) continue;

                if($extType == 'common') $extType = $this->config->edition;
                if($ext == 'lang' or $ext == 'js' or $ext == 'css')
                {
                    $extensionList[$extType][$extensionFullDir] = $this->getTwoGradeFiles($extensionFullDir);
                }
                else
                {
                    foreach(glob($extensionFullDir . '*') as $extensionFullFile)
                    {
                        if($ext == 'model' and is_dir($extensionFullFile))
                        {
                            $extModelDir = $extensionFullFile;
                            foreach(glob($extModelDir . '/*') as $extModelFile)
                            {
                                $fileName = basename($extModelFile);
                                if($fileName == 'index.html') continue;
                                $extensionList[$extType][$extensionFullDir][$extensionFullFile][$extModelFile] = $fileName;
                            }
                        }
                        else
                        {
                            $fileName = basename($extensionFullFile);
                            if($fileName == 'index.html') continue;
                            $extensionList[$extType][$extensionFullDir][$extensionFullFile] = $fileName;
                        }
                    }
                }
            }
        }
        return $extensionList;
    }

    /**
     * if a directory has  two grage, this method will get files
     *
     * @param  string    $extensionFullDir
     * @access public
     * @return array
     */
    public function getTwoGradeFiles(string $extensionFullDir): array
    {
        $fileList = array();
        $langDirs = scandir($extensionFullDir);
        foreach($langDirs as $langDir)
        {
            if($langDir == '.' or $langDir == '..' or $langDir == '.svn' or $langDir == 'index.html') continue;
            $langFullDir = $extensionFullDir . $langDir;
            $fileList[$langFullDir] = array();
            if(is_dir($langFullDir))
            {
                $langFiles = scandir($langFullDir);
                foreach($langFiles as $langFile)
                {
                    if($langFile == '.' or $langFile == '..' or $langFile == '.svn' or $langFile == 'index.html') continue;
                    $langFullFile = $langFullDir . DS . $langFile;
                    $fileList[$langFullDir][$langFullFile] = $langFile;
                }
            }
        }
        return $fileList;
    }

    /**
     * Analysis methods of control and model.
     *
     * @param  string    $fileName
     * @access public
     * @return array
     */
    public function analysis(string $fileName): array
    {
        $classMethod = array();
        $class       = $this->getClassNameByPath($fileName);
        if(strpos($fileName, 'model.php') !== false) $class .= 'Model';
        if(!class_exists($class)) include $fileName;

        $reflection = new ReflectionClass($class);
        foreach($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
        {
            $methodName = $method->name;
            if($method->getFileName() != $fileName) continue;
            if($methodName == '__construct') continue;
            $classMethod[$fileName . DS . $methodName] = $methodName;
        }

        return $classMethod;
    }

    /**
     * Print tree from module files.
     *
     * @param  array    $files
     * @param  bool     $isRoot
     * @access public
     * @return string
     */
    public function printTree(array $files, bool $isRoot = true) : array|false
    {
        if(empty($files) or !is_array($files)) return false;

        $tree = array();
        if($isRoot)
        {
            $module   = basename(dirname(key($files)));
            $langFile = dirname(key($files)) . DS . 'lang' . DS . $this->app->getClientLang() . '.php';
            if(file_exists($langFile))
            {
                if(!isset($lang)) $lang = new stdclass();
                if(!isset($lang->$module)) $lang->$module = new stdclass();
                include $langFile;
            }

            $this->module = '';
            if(isset($lang->$module)) $this->module = $lang->$module;
            if(empty($this->module) and isset($this->lang->$module)) $this->module = $this->lang->$module;
        }

        foreach($files as $key => $file)
        {
            if(is_array($file))
            {
                $dirTree = $this->addLink4Dir($key);
                $dirTree->items = $this->printTree($file, false);
                $tree[] = $dirTree;
            }
            else
            {
                $tree[] = $this->addLink4File($key, $file);
            }
        }
        return $tree;
    }

    /**
     * Add link for directory or has children grade
     *
     * @param  string    $filePath
     * @access public
     * @return object
     */
    public function addLink4Dir(string $filePath): object
    {
        $tree        = new stdclass();
        $fileName    = basename($filePath);
        $tree->id    = md5($filePath);
        $tree->title = $fileName;
        $tree->actions['items'] = array();
        if(isset($this->lang->editor->translate[$fileName]))
        {
            $tree->text = $this->lang->editor->translate[$fileName];
        }
        else
        {
            $moduleName = zget($this->lang->editor->modules, $fileName, isset($this->lang->{$fileName}->common) ? $this->lang->{$fileName}->common : $fileName);
            $tree->text = $moduleName;
        }

        if(strpos($filePath, DS . 'ext' . DS) !== false && $fileName != 'lang' && $fileName != 'js' && $fileName != 'css')
        {
            $parentName = basename(dirname($filePath));
            if($parentName == 'js')
            {
                $tree->actions['items'][] = array('key' => 'edit', 'text' => $this->lang->editor->newExtend, 'id' => $tree->id, 'data-url' => $this->getExtendLink($filePath, "newJS"), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
            }
            elseif($parentName == 'css')
            {
                $tree->actions['items'][] = array('key' => 'edit', 'text' => $this->lang->editor->newExtend, 'id' => $tree->id, 'data-url' => $this->getExtendLink($filePath, "newCSS"), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
            }
            else
            {
                $tree->actions['items'][] = array('key' => 'edit', 'text' => $this->lang->editor->newExtend, 'id' => $tree->id, 'data-url' => $this->getExtendLink($filePath, "newExtend"), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
            }
        }
        elseif($fileName == 'model.php')
        {
            $tree->actions['items'][] = array('key' => 'edit', 'text' => $this->lang->editor->newMethod, 'id' => $tree->id, 'data-url' => $this->getExtendLink($filePath, "newMethod"), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
        }
        elseif($fileName == 'control.php')
        {
            $tree->actions['items'][] = array('key' => 'edit', 'text' => $this->lang->editor->newPage, 'id' => $tree->id, 'data-url' => inlink('newPage', "filePath=" . helper::safe64Encode($filePath)), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
        }
        return $tree;
    }

    /**
     * Add link for file
     *
     * @param  string    $filePath
     * @param  string    $file
     * @access public
     * @return object
     */
    public function addLink4File(string $filePath, string $file): object
    {
        $tree = new stdClass();
        $tree->id   = md5($file);
        $tree->name = $file;
        $tree->text = $file;
        $tree->actions['items'] = array();
        if(strpos($filePath, DS . 'ext' . DS) !== false)
        {
            $tree->actions['items'][] = array('key' => 'edit', 'text' => $this->lang->editor->edit, 'id' => $tree->id, 'data-url' => $this->getExtendLink($filePath, "edit"), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
            $tree->actions['items'][] = array('key' => 'delete', 'text' => $this->lang->delete, 'id' => $tree->id, 'className' => 'ajax-submit', 'url' => inlink('delete', 'path=' . helper::safe64Encode($filePath)), 'data-confirm' => $this->lang->editor->deleteConfirm);
        }
        elseif(basename(dirname($filePath))== 'view')
        {
            $tree->actions['items'][] = array('key' => 'edit', 'text' => $this->lang->editor->override, 'id' => $tree->id,  'data-url' => $this->getExtendLink($filePath, "override"), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
            $tree->actions['items'][] = array('key' => 'create', 'text' => $this->lang->editor->newHook, 'id' => $tree->id, 'data-url' => $this->getExtendLink($filePath, "newHook"), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
        }
        else
        {
            $parentDir = basename(dirname($filePath));
            $action    = 'extendOther';
            if($parentDir == 'control.php') $action = 'extendControl';
            if($parentDir == 'model.php')   $action = 'extendModel';

            $tree->actions['items'][] = array('key' => 'edit', 'text' => $this->lang->editor->extend, 'id' => $tree->id, 'data-url' => $this->getExtendLink($filePath, $action), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
            if($action != 'extendOther') $tree->actions['items'][] = array('key' => 'api', 'text' => $this->lang->editor->api, 'id' => $tree->id, 'data-url' => $this->getAPILink($filePath, $action), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
            if($parentDir == 'lang')     $tree->actions['items'][] = array('key' => 'newLang', 'text' => $this->lang->editor->newLang, 'id' => $tree->id, 'data-url' => $this->getExtendLink($filePath, "new" . str_replace('-', '_', basename($filePath, '.php'))), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
            if(basename($filePath) == 'config.php') $tree->actions['items'][] = array('key' => 'newConfig', 'text' => $this->lang->editor->newConfig, 'id' => $tree->id, 'data-url' => $this->getExtendLink($filePath, "newConfig"), 'data-on' => 'click', 'data-call' => 'openInEditWin', 'data-params' => 'event');
        }
        return $tree;
    }

    /**
     * Get extend link
     *
     * @param  string    $filePath
     * @param  string    $action
     * @param  string    $isExtends
     * @access public
     * @return string
     */
    public function getExtendLink(string $filePath, string $action, string $isExtends = ''): string
    {
        return inlink('edit', "filePath=" . helper::safe64Encode($filePath) . "&action=$action&isExtends=$isExtends");
    }

    /**
     * Get api link.
     *
     * @param  string    $filePath
     * @param  string    $action
     * @access public
     * @return string
     */
    public function getAPILink(string $filePath, string $action): string
    {
        return helper::createLink('api', 'debug', "filePath=" . helper::safe64Encode($filePath) . "&action=$action");
    }

    /**
     * Save file to extension.
     *
     * @param  string    $filePath
     * @access public
     * @return string|bool
     */
    public function save(string $filePath): string|bool
    {
        /* Reduce expiration time for check safe file. */
        $this->config->safeFileTimeout = 15 * 60;
        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile) return dao::$errors[] = sprintf($this->lang->editor->noticeOkFile, str_replace('\\', '/', $statusFile));

        $dirPath     = dirname($filePath);
        $extFilePath = substr($filePath, 0, strpos($filePath, DS . 'ext' . DS) + 4);
        if(!is_dir($dirPath) and is_writable($extFilePath)) mkdir($dirPath, 0777, true);
        if(!is_dir($dirPath))
        {
            if(is_dir($extFilePath)) return dao::$errors[] = sprintf($this->lang->editor->notWritable, $extFilePath);
            return dao::$errors[] = sprintf($this->lang->editor->notExists, $extFilePath);
        }
        if(!is_writable($dirPath)) return dao::$errors[] = sprintf($this->lang->editor->notWritable, $extFilePath);
        if(strpos(strtolower(realpath($dirPath)), strtolower($this->app->getBasePath())) !== 0) return dao::$errors[] = $this->lang->editor->editFileError;

        $fileContent = $this->post->fileContent;
        $evils       = array('eval', 'exec', 'passthru', 'proc_open', 'shell_exec', 'system', '$$', 'include', 'require', 'assert', 'javascript', 'onclick');
        $gibbedEvils = array('e v a l', 'e x e c', ' p a s s t h r u', ' p r o c _ o p e n', 's h e l l _ e x e c', 's y s t e m', '$ $', 'i n c l u d e', 'r e q u i r e', 'a s s e r t', 'j a v a s c r i p t', 'o n c l i c k');
        $fileContent = str_ireplace($gibbedEvils, $evils, $fileContent);
        if(function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc()) $fileContent = stripslashes($fileContent);

        file_put_contents($filePath, $fileContent);
        return true;
    }

    /**
     * Extend model.php and get file content.
     *
     * @param  string    $filePath
     * @access public
     * @return string
     */
    public function extendModel(string $filePath): string
    {
        $className = basename(dirname(dirname($filePath)));
        if(!class_exists($className)) helper::import(dirname($filePath));

        $methodName  = basename($filePath);
        $methodParam = $this->getParam($className, $methodName, 'Model');
        return <<<EOD
<?php
public function $methodName($methodParam)
{
    return parent::$methodName($methodParam);
}
EOD;
    }

    /**
     * Extend control.php and get file content.
     *
     * @param  string    $filePath
     * @param  string    $isExtends
     * @access public
     * @return string
     */
    public function extendControl(string $filePath, string $isExtends): string
    {
        $className = basename(dirname(dirname($filePath)));
        if(!class_exists($className)) helper::import(dirname($filePath));

        $methodName = basename($filePath);
        if($isExtends == 'yes')
        {
            $methodParam = $this->getParam($className, $methodName);
            return <<<EOD
<?php
helper::importControl('$className');
class my$className extends $className
{
    public function $methodName($methodParam)
    {
        return parent::$methodName($methodParam);
    }
}
EOD;
        }
        else
        {
            $methodCode = $this->getMethodCode($className, $methodName);
            return <<<EOD
<?php
class $className extends control
{
$methodCode
}
EOD;
       }
    }

    /**
     * Add a control method.
     *
     * @param  string    $filePath
     * @access public
     * @return string
     */
    public function newControl(string $filePath): string
    {
        $className  = $this->getClassNameByPath($filePath);
        $methodName = basename($filePath, '.php');
        return <<<EOD
<?php
class $className extends control
{
    public function $methodName()
    {
    }
}
EOD;
    }

    /**
     * Get method's parameters.
     *
     * @param  string    $className
     * @param  string    $methodName
     * @param  string    $ext
     * @access public
     * @return string
     */
    public function getParam(string $className, string $methodName, string $ext = ''): string
    {
        $method       = new ReflectionMethod($className . $ext, $methodName);
        $methodParams = array();
        foreach($method->getParameters() as $param)
        {
            $methodParam = '$' . $param->getName();
            if($param->isOptional())
            {
                $defaultParam = $param->getDefaultValue();
                if(is_string($defaultParam))                         $defaultParam = "'$defaultParam'";
                if(is_array($defaultParam) and empty($defaultParam)) $defaultParam = 'array()';
                if(is_null($defaultParam))                           $defaultParam = 'null';
                $methodParam .= "=$defaultParam";
            }
            $methodParams[] = $methodParam;
        }

        return implode(', ', $methodParams);
    }

    /**
     * Get method code.
     *
     * @param  string    $className
     * @param  string    $methodName
     * @param  string    $ext  value may be Model
     * @access public
     * @return string
     */
    public function getMethodCode(string $className, string $methodName, string $ext = ''): string
    {
        $method    = new ReflectionMethod($className . $ext, $methodName);
        $fileName  = $method->getFileName();
        $startLine = $method->getStartLine();
        $endLine   = $method->getEndLine();

        $file = file($fileName);
        $code = '';
        for($i = $startLine - 1; $i <= $endLine; $i++) $code .= $file[$i];
        return $code;
    }

    /**
     * Get save path.
     *
     * @param  string    $filePath
     * @param  string    $action
     * @access public
     * @return string
     */
    public function getSavePath(string $filePath, string $action): string
    {
        $sourceFileName = basename($filePath);

        $fileExtension = 'php';
        if(strrpos($sourceFileName, '.') !== false) $fileExtension = substr($sourceFileName, strrpos($sourceFileName, '.') + 1);
        if(strtolower($action) == 'newjs')  $fileExtension = 'js';
        if(strtolower($action) == 'newcss') $fileExtension = 'css';

        $fileName   = empty($_POST['fileName']) ? '' : trim($this->post->fileName);
        $moduleName = $this->getClassNameByPath($filePath);
        $methodName = '';
        if(str_contains('|newjs|newcss|', '|' . strtolower($action) . '|')) $methodName = basename($filePath);
        if($fileName and (strpos($fileName, '.' . $fileExtension) !== (strlen($fileName) - strlen($fileExtension) - 1))) $fileName .= '.' . $fileExtension;

        $extPath = $this->app->getExtensionRoot() . 'custom' . DS . $moduleName . DS . 'ext' . DS;
        switch($action)
        {
        case 'extendModel':
            $fileName = empty($fileName) ? strtolower(basename($filePath)) . ".{$fileExtension}" : $fileName;
            return $extPath . 'model' . DS . $fileName;
        case 'extendControl':
            $fileName = strtolower(basename($filePath)) . ".{$fileExtension}";
            return $extPath . 'control' . DS . $fileName;
        case 'override':
            $fileName = basename($filePath);
            return $extPath . 'view' . DS . $fileName;
        case 'extendOther':
            $editName = basename($filePath);
            $fileName = empty($fileName) ? $editName: $fileName;
            if($editName == 'config.php') return $extPath . 'config' .DS . $fileName;
            if(strpos($editName, '.php') !== false) return $extPath . 'lang' . DS . basename($editName, ".{$fileExtension}") . DS . $fileName;
            return $extPath . $fileExtension . DS . basename($editName, ".{$fileExtension}") . DS . $fileName;
        default:
            if(empty($fileName)) return dao::$errors[] = $this->lang->editor->emptyFileName;

            $action = strtolower(str_replace('new', '', $action));
            if($action == 'method') return $extPath  . basename($filePath, ".{$fileExtension}") . DS . $fileName;
            if($action == 'extend') return $filePath . DS . $fileName;
            if($action == 'hook')   return $extPath  . 'view'   . DS . $fileName;
            if($action == 'config') return $extPath  . 'config' . DS . $fileName;
            if($action == 'js')     return $extPath  . 'js'     . DS . $methodName . DS . $fileName;
            if($action == 'css')    return $extPath  . 'css'    . DS . $methodName . DS . $fileName;
            return $extPath . 'lang' . DS . str_replace('_', '-', $action) . DS . $fileName;
        }
    }

    /**
     * Get class name by path.
     *
     * @param  string    $filePath
     * @access public
     * @return string
     */
    public function getClassNameByPath(string $filePath): string
    {
        $className = '';
        if(strpos($filePath, DS . 'module' . DS) !== false)
        {
            $className = strstr($filePath, DS . 'module' . DS);
            $className = substr($className, 0, strpos($className, DS, 9));
        }
        elseif(strpos($filePath, DS . 'ext' . DS) !== false)
        {
            $className = substr($filePath, 0, strpos($filePath, DS . 'ext' . DS));
        }
        elseif(strpos($filePath, DS . 'extension' . DS) !== false)
        {
            $className = strstr($filePath, DS . 'extension' . DS);
            $className = str_replace(DS . 'extension' . DS, '', $className);
            $className = substr($className, 0, strpos($className, DS, strpos($className, DS) + 1));
        }
        $className = basename($className);

        return $className;
    }
}

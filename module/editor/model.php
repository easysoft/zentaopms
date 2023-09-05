<?php
/**
 * The model file of editor module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
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
    public function getModuleFiles($moduleName)
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
    public function getExtensionFiles($moduleName)
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
     * @return string
     */
    public function getTwoGradeFiles($extensionFullDir)
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
    public function analysis($fileName)
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
    public function printTree($files, $isRoot = true)
    {
        if(empty($files) or !is_array($files)) return false;

        $tree = $isRoot ? "<ul id='extendTree' class='tree tree-lines' data-ride='tree'>\n" : "<ul>\n";
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
            $tree .= "<li>\n";
            if(is_array($file))
            {
                $tree .= $this->addLink4Dir($key);
                $tree .= $this->printTree($file, false);
            }
            else
            {
                $tree .= $this->addLink4File($key, $file);
            }
            $tree .= "</li>\n";
        }
        $tree .= "</ul>\n";
        return $tree;
    }

    /**
     * Add link for directory or has children grade
     *
     * @param  string    $filePath
     * @access public
     * @return string
     */
    public function addLink4Dir($filePath)
    {
        $tree     = '';
        $fileName = basename($filePath);
        if(isset($this->lang->editor->translate[$fileName]))
        {
            $file = "<span title='$fileName'>" . $this->lang->editor->translate[$fileName] . '</span>';
        }
        else
        {
            $moduleName = zget($this->lang->editor->modules, $fileName, isset($this->lang->{$fileName}->common) ? $this->lang->{$fileName}->common : $fileName);
            $file = "<span title='{$fileName}'>{$moduleName}</span>";
        }

        if(strpos($filePath, DS . 'ext' . DS) !== false)
        {
            $parentName = basename(dirname($filePath));
            if($fileName == 'lang' or $fileName == 'js' or $fileName == 'css')
            {
                $tree .= $file;
            }
            elseif($parentName == 'js')
            {
                $tree .= "$file " . html::a($this->getExtendLink($filePath, "newJS"), $this->lang->editor->newExtend, 'editWin');
            }
            elseif($parentName == 'css')
            {
                $tree .= "$file " . html::a($this->getExtendLink($filePath, "newCSS"), $this->lang->editor->newExtend, 'editWin');
            }
            else
            {
                $tree .= "$file " . html::a($this->getExtendLink($filePath, "newExtend"), $this->lang->editor->newExtend, 'editWin');
            }
        }
        elseif($fileName == 'model.php')
        {
            $tree .= "$file " . html::a($this->getExtendLink($filePath, 'newMethod'), $this->lang->editor->newMethod, 'editWin');
        }
        elseif($fileName == 'control.php')
        {
            $tree .= "$file " . html::a(inlink('newPage', "filePath=" . helper::safe64Encode($filePath)), $this->lang->editor->newPage, 'editWin');
        }
        else
        {
            $tree .= $file;
        }
        return $tree;
    }

    /**
     * Add link for file
     *
     * @param  string    $filePath
     * @param  string    $file
     * @access public
     * @return string
     */
    public function addLink4File($filePath, $file)
    {
        $tree = '';
        $file = "<span title='$file'>$file</span>";
        if(strpos($filePath, DS . 'ext' . DS) !== false)
        {
            $tree .= "$file " . html::a($this->getExtendLink($filePath, "edit"), $this->lang->edit, 'editWin');
            $tree .= html::a(inlink('delete', 'path=' . helper::safe64Encode($filePath)), $this->lang->delete, 'hiddenwin') . "\n";
        }
        elseif(basename(dirname($filePath))== 'view')
        {
            $tree .= "$file " . html::a($this->getExtendLink($filePath, "override"), $this->lang->editor->override, 'editWin');
            $tree .= html::a($this->getExtendLink($filePath, "newHook"), $this->lang->editor->newHook, 'editWin') . "\n";
        }
        else
        {
            $parentDir = basename(dirname($filePath));
            $action    = 'extendOther';
            if($parentDir == 'control.php') $action = 'extendControl';
            if($parentDir == 'model.php')   $action = 'extendModel';

            $tree .= "$file " . html::a($this->getExtendLink($filePath, $action), $this->lang->editor->extend, 'editWin');
            if($action != 'extendOther') $tree .= html::a($this->getAPILink($filePath, $action), $this->lang->editor->api, 'editWin');
            if($parentDir == 'lang')     $tree .= html::a($this->getExtendLink($filePath, "new" . str_replace('-', '_', basename($filePath, '.php'))), $this->lang->editor->newLang, 'editWin');
            if(basename($filePath) == 'config.php') $tree .= html::a($this->getExtendLink($filePath, "newConfig"), $this->lang->editor->newConfig, 'editWin');
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
    public function getExtendLink($filePath, $action, $isExtends = '')
    {
        return inlink('edit', "filePath=" . helper::safe64Encode($filePath) . "&action=$action&isExtends=$isExtends");
    }

    /**
     * Get api link.
     *
     * @param  int    $filePath
     * @param  int    $action
     * @param  string $type
     * @access public
     * @return string
     */
    public function getAPILink($filePath, $action)
    {
        return helper::createLink('api', 'debug', "filePath=" . helper::safe64Encode($filePath) . "&action=$action");
    }

    /**
     * Save file to extension.
     *
     * @param  string    $filePath
     * @access public
     * @return string
     */
    public function save($filePath)
    {
        /* Reduce expiration time for check safe file. */
        $this->config->safeFileTimeout = 15 * 60;
        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile) return sprintf($this->lang->editor->noticeOkFile, str_replace('\\', '/', $statusFile));

        $dirPath     = dirname($filePath);
        $extFilePath = substr($filePath, 0, strpos($filePath, DS . 'ext' . DS) + 4);
        if(!is_dir($dirPath) and is_writable($extFilePath)) mkdir($dirPath, 0777, true);
        if(!is_dir($dirPath))
        {
            if(is_dir($extFilePath)) return sprintf($this->lang->editor->notWritable, $extFilePath);
            return sprintf($this->lang->editor->notExists, $extFilePath);
        }
        if(!is_writable($dirPath)) return sprintf($this->lang->editor->notWritable, $extFilePath);
        if(strpos(strtolower(realpath($dirPath)), strtolower($this->app->getBasePath())) !== 0) return $this->lang->editor->editFileError;

        $fileContent = $this->post->fileContent;
        $evils       = array('eval', 'exec', 'passthru', 'proc_open', 'shell_exec', 'system', '$$', 'include', 'require', 'assert', 'javascript', 'onclick');
        $gibbedEvils = array('e v a l', 'e x e c', ' p a s s t h r u', ' p r o c _ o p e n', 's h e l l _ e x e c', 's y s t e m', '$ $', 'i n c l u d e', 'r e q u i r e', 'a s s e r t', 'j a v a s c r i p t', 'o n c l i c k');
        $fileContent = str_ireplace($gibbedEvils, $evils, $fileContent);
        if(get_magic_quotes_gpc()) $fileContent = stripslashes($fileContent);

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
    public function extendModel($filePath)
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
     * @access public
     * @return string
     */
    public function extendControl($filePath, $isExtends)
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
    public function newControl($filePath)
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
    public function getParam($className, $methodName, $ext = '')
    {
        $method = new ReflectionMethod($className . $ext, $methodName);
        $methodParam = '';
        foreach ($method->getParameters() as $param)
        {
            $methodParam .= '$' . $param->getName();
            if($param->isOptional())
            {
                $defaultParam = $param->getDefaultValue();
                if(is_string($defaultParam))
                {
                    $methodParam .= "='$defaultParam', ";
                }
                else
                {
                    if(is_array($defaultParam) and empty($defaultParam)) $defaultParam = 'array()';
                    if(is_null($defaultParam)) $defaultParam = 'null';
                    $methodParam .= "=$defaultParam, ";
                }
            }
            else
            {
                $methodParam .= ', ';
            }
        }
        $methodParam = rtrim($methodParam, ', ');
        return $methodParam;
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
    public function getMethodCode($className, $methodName, $ext = '')
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
    public function getSavePath($filePath, $action)
    {
        $fileExtension  = 'php';
        $sourceFileName = basename($filePath);
        if(strrpos($sourceFileName, '.') !== false) $fileExtension = substr($sourceFileName, strrpos($sourceFileName, '.') + 1);

        $fileName   = empty($_POST['fileName']) ? '' : trim($this->post->fileName);
        $moduleName = $this->getClassNameByPath($filePath);

        $methodName = '';
        if(strtolower($action) == 'newjs')
        {
            if($fileExtension != 'js') $fileExtension = 'js';
            $methodName = basename($filePath);
        }
        if(strtolower($action) == 'newcss')
        {
            if($fileExtension != 'css') $fileExtension = 'css';
            $methodName = basename($filePath);
        }

        $extPath    = $this->app->getExtensionRoot() . 'custom' . DS . $moduleName . DS . 'ext' . DS;
        if($fileName and (strpos($fileName, '.' . $fileExtension) !== (strlen($fileName) - strlen($fileExtension) - 1))) $fileName .= '.' . $fileExtension;
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
            if(empty($fileName)) return print(js::error($this->lang->editor->emptyFileName));

            $action = strtolower(str_replace('new', '', $action));
            if($action == 'hook')   return $extPath . 'view' . DS . $fileName;
            if($action == 'method') return $extPath . basename($filePath, ".{$fileExtension}") . DS . $fileName;
            if($action == 'extend') return $filePath . DS . $fileName;
            if($action == 'config') return $extPath . 'config' . DS . $fileName;
            if($action == 'js')     return $extPath . 'js'  . DS . $methodName . DS . $fileName;
            if($action == 'css')    return $extPath . 'css' . DS . $methodName . DS . $fileName;
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
    public function getClassNameByPath($filePath)
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

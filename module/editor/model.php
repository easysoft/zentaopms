<?php
/**
 * The model file of editor module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
     * @access public
     * @return array
     */
    public function getModuleFiles()
    {
        $moduleRoot = $this->app->getModuleRoot();
        $allModules = array();
        $moduleDirs = scandir($moduleRoot);
        foreach($moduleDirs as $moduleDir)
        {
            if($moduleDir == '.' or $moduleDir == '..' or $moduleDir == '.svn') continue;
            $moduleFullDir = $moduleRoot . $moduleDir;
            $moduleFiles = scandir($moduleFullDir);
            foreach($moduleFiles as $moduleFile)
            {
                if($moduleFile == '.' or $moduleFile == '..' or $moduleFile == '.svn') continue;
                $moduleFullFile = $moduleFullDir . '/' . $moduleFile;
                if($moduleFile == 'control.php' or $moduleFile == 'model.php')
                {
                    $allModules[$moduleFullDir][$moduleFullFile] = $this->analysis($moduleFullFile);
                }
                elseif($moduleFile == 'ext')
                {
                    $allModules[$moduleFullDir][$moduleFullFile] = $this->getExtensionFiles($moduleFullFile);
                }
                elseif(is_dir($moduleFullFile)) 
                {
                    $ext = ($moduleFile == 'js' or $moduleFile == 'css') ? $moduleFile : 'php';
                    foreach(glob($moduleFullFile . '/' . "*.$ext") as $fileName) $allModules[$moduleFullDir][$moduleFullFile][$fileName] = basename($fileName);
                }
                else
                {
                    $allModules[$moduleFullDir][$moduleFullFile] = $moduleFile;
                }
            }
        }
        return $allModules;
    }

    /**
     * Get extension files.  
     * 
     * @param  int    $extPath 
     * @access public
     * @return void
     */
    public function getExtensionFiles($extPath)
    {
        $extensionList = array();
        $extensionDirs = scandir($extPath);
        foreach($extensionDirs as $extensionDir)
        {
            if($extensionDir == '.' or $extensionDir == '..' or $extensionDir == '.svn') continue;
            $extensionFullDir = $extPath . '/' . $extensionDir;
            if(is_dir($extensionFullDir))
            {
                $extensionList[$extensionFullDir] = array();
                /* extend of lang is more a grade of directroy. */
                if($extensionDir == 'lang' or $extensionDir == 'js' or $extensionDir == 'css')
                {
                    
                    $extensionList[$extensionFullDir] = $this->getTwoGradeFiles($extensionFullDir);
                    continue;
                }
                $extensionFiles = scandir($extensionFullDir);
                foreach($extensionFiles as $extensionFile)
                {
                    if($extensionFile == '.' or $extensionFile == '..' or $extensionFile == '.svn') continue;
                    $extensionFullFile = $extensionFullDir . '/' . $extensionFile;
                    $extensionList[$extensionFullDir][$extensionFullFile] = $extensionFile;
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
            if($langDir == '.' or $langDir == '..' or $langDir == '.svn') continue;
            $langFullDir = $extensionFullDir . '/' . $langDir;
            $fileList[$langFullDir] = array();
            if(is_dir($langFullDir))
            {
                $langFiles = scandir($langFullDir);
                foreach($langFiles as $langFile)
                {
                    if($langFile == '.' or $langFile == '..' or $langFile == '.svn') continue;
                    $langFullFile = $langFullDir . '/' . $langFile;
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
        $class = strstr($fileName, '/module/');
        $class = substr($class, 0, strpos($class, '/', 9));
        $class = basename($class);
        if(strpos($fileName, 'model.php') !== false) $class .= 'Model';
        if(!class_exists($class)) include $fileName;
        $reflection = new ReflectionClass($class);
        foreach($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
        {
            $methodName = $method->name;
            if($method->getFileName() != $fileName) continue;
            $classMethod[$fileName . '/' . $methodName] = $methodName;
        }
        return $classMethod;
    }

    /**
     * Print tree from module files.
     * 
     * @param  int    $files 
     * @access public
     * @return void
     */
    public function printTree($files, $isRoot = true)
    {
        if(empty($files) or !is_array($files)) return false;
        $tree = $isRoot ? "<ul id='tree'>\n" : "<ul>\n";
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
        $tree = '';
        $fileName = basename($filePath);
        if(strpos($filePath, '/ext/') !== false)
        {
            if($fileName == 'lang' or $fileName == 'js' or $fileName == 'css')
            {
                $tree .= $fileName;
            }
            else
            {
                $tree .= $fileName . html::a($this->getExtendLink($filePath, "newExtend"), $this->lang->editor->newExtend);
            }
        }
        elseif($fileName == 'model.php' or $fileName == 'control.php')
        {
            $tree .= $fileName . html::a($this->getExtendLink($filePath, 'newMethod'), $this->lang->editor->newMethod);
        }
        else
        {
            $tree .= $fileName;
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
        if(strpos($filePath, '/ext/') !== false)
        {
            $tree .= $file . html::a($this->getExtendLink($filePath, "edit"), $this->lang->edit) . html::a(inlink('delete', 'path=' . helper::safe64Encode($filePath)), $this->lang->delete, 'hiddenwin') . "\n";
        }
        elseif(basename(dirname($filePath))== 'view')
        {
            $tree .= $file . html::a($this->getExtendLink($filePath, "override"), $this->lang->editor->override) . html::a($this->getExtendLink($filePath, "newHook"), $this->lang->editor->newHook) . "\n";
        }
        else
        {
            $parentDir = basename(dirname($filePath));
            $action = 'extendOther';
            if($parentDir == 'control.php') $action = 'extendControl';
            if($parentDir == 'model.php') $action = 'extendModel';
            $tree .= $file . html::a($this->getExtendLink($filePath, $action), $this->lang->editor->extend);
            if($parentDir == 'lang') $tree .= html::a($this->getExtendLink($filePath, "new" . str_replace('-', '_', basename($filePath, '.php'))), $this->lang->editor->newLang);
            if(basename($filePath) == 'config.php') $tree .= html::a($this->getExtendLink($filePath, "newConfig"), $this->lang->editor->newConfig);
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
        return inlink('index', "filePath=" . helper::safe64Encode($filePath) . "&action=$action&isExtends=$isExtends");
    }

    /**
     * Save file to extension.
     * 
     * @param  string    $filePath 
     * @access public
     * @return void
     */
    public function save($filePath)
    {
        $fileContent = $this->post->fileContent;
        if(get_magic_quotes_gpc()) $fileContent = stripslashes($fileContent);
        $dirPath = dirname($filePath);
        if(!is_dir($dirPath) and is_writable(dirname($dirPath))) mkdir($dirPath, 0777, true);
        if(is_writable($dirPath))
        {
            file_put_contents($filePath, $fileContent);
        }
        else
        {
            $extFilePath = substr($filePath, 0, strpos($filePath, '/ext/') + 4);
            die(js::alert($this->lang->editor->notWritable . $extFilePath));
        }
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
        $methodName = basename($filePath);
        $methodParam = $this->getParam($className, $methodName, 'Model');
        return $fileContent = <<<EOD
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
    public function extendControl($filePath)
    {
        $className = basename(dirname(dirname($filePath)));
        $methodName = basename($filePath);
        if($isExtends == 'yes')
        {
            $methodParam = $this->getParam($className, $methodName);
            return $fileContent = <<<EOD
include '../../control.php';
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
            return $fileContent = <<<EOD
class $className extends control
{
$methodCode
}
EOD;
       }
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
                if(is_string($defaultParam)) $methodParam .= "='$defaultParam', ";
                else $methodParam .= "=$defaultParam, ";
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
        for($i = $startLine - 1; $i <= $endLine; $i++)
        {
            $code .= $file[$i];
        }
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
        $fileName   = empty($_POST['fileName']) ? '' : trim($this->post->fileName);
        $moduleName = strstr($filePath, '/module/');
        $moduleName = substr($moduleName, 0, strpos($moduleName, '/', 9));
        $moduleName = basename($moduleName);
        $extPath    = $this->app->getModuleRoot() . $moduleName . '/ext/';
        switch($action)
        {
        case 'extendModel':
            $fileName = empty($fileName) ? strtolower(basename($filePath)) . '.php' : $fileName;
            return $extPath . 'model/' . $fileName;
        case 'extendControl':
            $fileName = strtolower(basename($filePath)) . '.php';
            return $extPath . 'control/' . $fileName;
        case 'override':
            $fileName = basename($filePath);
            return $extPath . 'view/' . $fileName;
        case 'extendOther':
            $editName = basename($filePath);
            $fileName = empty($fileName) ? $editName: $fileName;
            if($editName == 'config.php') return $extPath . 'config/' . $fileName;
            elseif(strpos($editName, '.php') !== false) return $extPath . 'lang/' . str_replace('.php', '', $editName) . '/' . $fileName;
            else return $extPath . substr($editName, strrpos($editName, '.') + 1) . '/' . substr($editName, 0, strrpos($editName, '.')) . '/' . $fileName;
        default:
            if(empty($fileName)) die(js::error($this->lang->editor->emptyFileName));
            $action = strtolower(str_replace('new', '', $action));
            if($action == 'hook') return $extPath . 'view/' . $fileName;
            elseif($action == 'method') return $extPath . basename($filePath, '.php') . '/' . $fileName;
            elseif($action == 'extend') return $filePath . '/' . $fileName;
            elseif($action == 'config') return $extPath . 'config/' . $fileName;
            else return $extPath . 'lang/' . str_replace('_', '-', $action) . '/' . $fileName;
        }
    }
}

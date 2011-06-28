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
     * @param  string    $moduleRoot 
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
                    foreach(glob($moduleFullFile . '/' . '*.php') as $fileName) $allModules[$moduleFullDir][$moduleFullFile][$fileName] = basename($fileName);
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
                if($extensionDir == 'lang')
                {
                    $langDirs = scandir($extensionFullDir);
                    foreach($langDirs as $langDir)
                    {
                        if($langDir == '.' or $langDir == '..' or $langDir == '.svn') continue;
                        $langFullDir = $extensionFullDir . '/' . $langDir;
                        $extensionList[$extensionFullDir][$langFullDir] = array();
                        if(is_dir($langFullDir))
                        {
                            $langFiles = scandir($langFullDir);
                            foreach($langFiles as $langFile)
                            {
                                if($langFile == '.' or $langFile == '..' or $langFile == '.svn') continue;
                                $langFullFile = $langFullDir . '/' . $langFile;
                                $extensionList[$extensionFullDir][$langFullDir][$langFullFile] = $langFile;
                            }
                        }
                    }
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
            $classMethod[$methodName] = $methodName;
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
                $tree .= basename($key) . "\n";
                $tree .= $this->printTree($file, false);
            }
            else
            {
                if(strpos($key, '/ext/') !== false)
                {
                    $tree .= $file . html::a(inlink('index', "editFileName=" . helper::safe64Encode($key) . "&action=edit"), $this->lang->edit) . html::a(inlink('delete', 'path=' . helper::safe64Encode($key)), $this->lang->delete, 'hiddenwin') . "\n";
                }
                else
                {
                    $tree .= $file . "\n";
                }
            }
            $tree .= "</li>\n";
        }
        $tree .= "</ul>\n";
        return $tree;
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
        if(is_writable($filePath))
        {
            file_put_contents($filePath, $fileContent);
        }
        else
        {
            die(js::alert($this->lang->editor->noWritable));
        }
    }
}

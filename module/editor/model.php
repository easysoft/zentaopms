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
                    $allModules[$moduleDir][$moduleFullFile] = $this->analysis($moduleFullFile);
                }
                elseif(is_dir($moduleFullFile)) 
                {
                    foreach(glob($moduleFullFile . '/' . '*.php') as $fileName) $allModules[$moduleDir][$moduleFile][$fileName] = basename($fileName);
                }
                else
                {
                    $allModules[$moduleDir][$moduleFullFile] = $moduleFile;
                }
            }
        }
        return $allModules;
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
                $tree .= $file . "\n";
            }
            $tree .= "</li>\n";
        }
        $tree .= "</ul>\n";
        return $tree;
    }
}

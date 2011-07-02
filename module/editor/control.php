<?php
/**
 * The control file of editor of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class editor extends control
{
    /**
     * Show module files and edit them. 
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->view->moduleList = $this->editor->getModules();
        $this->display();
    }

    /**
     * Show this module of files.
     * 
     * @param  string $moduleDir 
     * @access public
     * @return void
     */
    public function extend($moduleDir = '')
    {
        $moduleFiles = $this->editor->getModuleFiles($moduleDir);
        $this->view->module = $moduleDir;
        $this->view->tree = $this->editor->printTree($moduleFiles);
        $this->display();
    }

    /**
     * Edit extend. 
     * 
     * @param  string $filePath 
     * @param  string $action 
     * @param  string $isExtends 
     * @access public
     * @return void
     */
    public function edit($filePath = '', $action = '', $isExtends = '')
    {
        $this->view->safeFilePath = $filePath;
        $fileContent  = '';
        if($filePath)
        {
            $filePath = helper::safe64Decode($filePath);
            if($action == 'extendOther' and file_exists($filePath))
            {
                $this->view->showContent = htmlspecialchars(file_get_contents($filePath));
            }
            if($action == 'edit' or $action == 'override')
            {
                if(file_exists($filePath))
                {
                    $fileContent = file_get_contents($filePath);
                    if($action == 'override')
                    {
                        $fileContent = str_replace('../../', '../../../', $fileContent);
                        $fileContent = str_replace(array('\'./', '"./'), array('\'../../view/', '"../../view'), $fileContent);
                    }
                }
                else
                {
                    $filePath = '';
                }
            }
            elseif($action == 'extendModel')
            {
                $fileContent = $this->editor->extendModel($filePath);
            }
            elseif($action == 'extendControl')
            {
                $okUrl = $this->editor->getExtendLink($filePath, 'extendControl', 'yes');
                $cancelUrl = $this->editor->getExtendLink($filePath, 'extendControl', 'no');
                if(!$isExtends) die(js::confirm($this->lang->editor->extendConfirm, $okUrl, $cancelUrl));
                $fileContent = $this->editor->extendControl($filePath, $isExtends);
            }
            elseif($action == 'newPage')
            {
                $fileContent = $this->editor->newControl($filePath);
            }
            elseif(strrpos(basename($filePath), '.php') !== false and empty($fileContent))
            {
                $fileContent = "<?php\n";
            }
        }
        $this->view->fileContent = $fileContent;
        $this->view->filePath    = $filePath;
        $this->view->action      = $action;
        $this->display();
    }

    /**
     * Set Page name. 
     * 
     * @param  string    $filePath 
     * @access public
     * @return void
     */
    public function newPage($filePath)
    {
        $filePath = helper::safe64Decode($filePath);
        if($_POST)
        {
            $saveFilePath = $this->editor->getSavePath($filePath, 'newMethod');
            $extendLink   = $this->editor->getExtendLink($saveFilePath, 'newPage');
            if(file_exists($saveFilePath) and !$this->post->override) die(js::confirm($this->lang->editor->repeatPage, $extendLink, '', 'parent'));
            die(js::locate($extendLink, 'parent'));
        }
        $this->view->filePath    = $filePath;
        $this->display();
    }

    /**
     * Save file to extension.
     * 
     * @param  string $filePath 
     * @access public
     * @return void
     */
    public function save($filePath = '', $action = '')
    {
        if($filePath and $_POST)
        {
            $filePath = helper::safe64Decode($filePath);
            if($action != 'edit' and $action != 'newPage') $filePath = $this->editor->getSavePath($filePath, $action);
            if($action != 'edit' and $action != 'newPage' and file_exists($filePath) and !$this->post->override) die(js::error($this->lang->editor->repeatFile));
            $this->editor->save($filePath);
            echo js::reload('parent.parent.extendWin');
            die(js::locate(inlink('edit', "filePath=" . helper::safe64Encode($filePath) . "&action=edit"), 'parent'));
        }
    }

    /**
     * Delete extension file.
     * 
     * @param  string $filePath 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function delete($filePath = '', $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->editor->deleteConfirm, inlink('delete', "filePath=$filePath&confirm=yes")));
        }
        $filePath = helper::safe64Decode($filePath);
        if(file_exists($filePath) and unlink($filePath)) die(js::reload('parent'));
        die(js::alert($this->lang->editor->notDelete));

    }
}


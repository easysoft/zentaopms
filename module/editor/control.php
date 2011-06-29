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
    public function index($filePath = '', $action = '', $isExtends = '')
    {
        $allModules = $this->editor->getModuleFiles();
        $this->view->tree = $this->editor->printTree($allModules);
        $this->view->safeFilePath = $filePath;
        $filePath = helper::safe64Decode($filePath);
        $fileContent  = '';
        if($filePath)
        {
            if($action == 'extendOther' and file_exists($filePath))
            {
                $this->view->showContent = htmlspecialchars(file_get_contents($filePath));
            }
            elseif($action == 'edit' or $action == 'override')
            {
                if(file_exists($filePath))
                {
                    $fileContent = file_get_contents($filePath);
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
                if(!$isExtends) die(js::confirm($this->lang->editor->extendConfirm, $okUrl, $cencelUrl));
                $fileContent = $this->editor->extendControl($filePath);
            }
        }
        $this->view->fileContent = $fileContent;
        $this->view->filePath    = $filePath;
        $this->view->action      = $action;
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
            if($action != 'edit') $filePath = $this->editor->getSavePath($filePath, $action);
            if($action != 'edit' and file_exists($filePath) and !$this->post->override) die(js::error($this->lang->editor->repeatFile));
            $this->editor->save($filePath);
            die(js::locate(inlink('index', "filePath=" . helper::safe64Encode($filePath) . "&action=edit"), 'parent'));
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


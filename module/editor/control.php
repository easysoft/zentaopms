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
    public function index($editFileName = '', $action = '')
    {
        $allModules = $this->editor->getModuleFiles();
        $this->view->tree     = $this->editor->printTree($allModules);
        $editFileName = helper::safe64Decode($editFileName);
        $fileContent  = '';
        if($editFileName and file_exists($editFileName))
        {
            if($action == 'edit')
            {
                $fileContent = file_get_contents($editFileName);
            }
        }
        $this->view->fileContent = $fileContent;
        $this->view->filePath    = empty($fileContent) ? '' : $editFileName;
        $this->display();
    }

    /**
     * Save file to extension.
     * 
     * @param  string $filePath 
     * @access public
     * @return void
     */
    public function save($filePath = '')
    {
        if($filePath and $_POST)
        {
            $filePath = helper::safe64Decode($filePath);
            $this->editor->save($filePath);
            die(js::reload('parent'));
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
        die(js::alert($this->lang->editor->noDelete));

    }
}


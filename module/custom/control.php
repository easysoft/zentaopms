<?php
/**
 * The control file of custom of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class custom extends control
{
    /**
     * __construct 
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index 
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        die(js::locate(inlink('setCustom')));
    }

    /**
     * Custom 
     * 
     * @param  string $module 
     * @param  string $field 
     * @access public
     * @return void
     */
    public function setCustom($module = 'story', $field = 'priList')
    {
        if($module == 'user' and $field == 'priList') $field = 'roleList';
        $lang = $this->app->getClientLang();

        $this->app->loadLang($module);
        $fieldList = $this->lang->$module->$field;
        if(!empty($_POST))
        {
            $this->custom->deleteItems("{$lang}.{$module}.{$field}");
            foreach($_POST['keys'] as $index => $key)
            {
                $value  = $_POST['values'][$index];
                $system = $_POST['systems'][$index];
                $this->custom->setItem("{$lang}.{$module}.{$field}.{$key}.{$system}", $value);
            }
            if(!dao::getError()) die(js::reload('parent'));
        }

        $this->view->title        = $this->lang->custom->common . $this->lang->colon . $this->lang->custom->story;
        $this->view->position[]   = $this->lang->custom->common;
        $this->view->position[]   = $this->lang->custom->$module;
        $this->view->fieldList    = $fieldList;
        $this->view->dbFields     = $this->custom->getItems("{$lang}.{$module}.{$field}");
        $this->view->field        = $field;
        $this->view->module       = $module;
        $this->view->canAdd       = strpos($this->config->custom->$module->canAdd, $field) !== false;

        $this->display();
    }

    /**
     * Restore the default lang. Delete the related items.
     * 
     * @param  string $module 
     * @param  string $field 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function restore($module, $field, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->custom->confirmRestore, inlink('restore', "module=$module&field=$field&confirm=yes")));
        }

        $lang = $this->app->getClientLang();
        $this->custom->deleteItems("{$lang}.{$module}.{$field}");
        die(js::reload('parent'));
    }
}


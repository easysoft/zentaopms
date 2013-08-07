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
    public function __construct()
    {
        parent::__construct();
    }

    public function index($module = 'story', $field = 'priList')
    {
        $lang = $this->app->getClientLang();

        $this->app->loadLang($module);
        $fieldList = $this->lang->$module->$field;
        if(!empty($_POST))
        {
            foreach($_POST['keys'] as $index => $key)
            {
//                $value = $_POST['values'][$index];
 //               if(isset($fieldList[$key]) and $fieldList[$key] == $value) continue;
                $this->custom->setItem("{$lang}.{$module}.{$field}.{$key}", $value);
            }
            if(!dao::getError()) die(js::reload('parent'));
        }

        $this->view->standardList = $this->custom->getStandardList($module, $field);
        $this->view->title        = $this->lang->custom->common . $this->lang->colon . $this->lang->custom->story;
        $this->view->position[]   = $this->lang->custom->common;
        $this->view->position[]   = $this->lang->custom->$module;
        $this->view->fieldList    = $fieldList;
        $this->view->field        = $field;
        $this->view->module       = $module;
        $this->view->canAdd       = strpos($this->config->custom->$module->canAdd, $field) !== false;

        $this->display();
    }
}


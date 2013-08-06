<?php
/**
 * The control file of xxx of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     xxx
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class customlang extends control
{
    public function __construct()
    {
        parent::__construct();
    }

    public function story($field = 'priList')
    {
        if(!empty($_POST))
        {
            $this->customlang->update('story', $field);
            if(!dao::getError()) die(js::reload('parent'));
        }

        $this->app->loadLang('story');
        $fieldList    = array();
        $standardList = $this->lang->story->$field;
        $fieldList    = $this->customlang->getLang('', $this->app->getClientLang(), 'story', $field);
        $fieldList    = $fieldList ? unserialize($fieldList->value) + $standardList: $standardList;

        $this->view->title        = $this->lang->customlang->common . $this->lang->colon . $this->lang->customlang->story;
        $this->view->position[]   = $this->lang->customlang->common;
        $this->view->position[]   = $this->lang->customlang->story;
        $this->view->standardList = $standardList;
        $this->view->fieldList    = $fieldList;
        $this->view->field        = $field;
        $this->view->canAdd       = strpos($this->config->customlang->story->canAdd, $field) !== false;

        $this->display();
    }
}


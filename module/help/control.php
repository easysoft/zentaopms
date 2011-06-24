<?php
/*
 * The control file of help module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class help extends control
{
    /**
     * Get the help info of a field..
     * 
     * @param string $module 
     * @param string $method 
     * @param string $field 
     * @param string $clientLang 
     * @access public
     * @return void
     */
    public function field($module, $method, $field)
    {
        $clientLang = $this->app->getClientLang();
        include "./lang/field.$clientLang.php";

        $fieldName = '';
        $fieldNote = $this->lang->help->noHelpYet;
        if(isset($help->$module->$field))
        {
            $fieldHelp = explode('|', $help->$module->$field);
            $fieldName = $fieldHelp[0];
            if(isset($fieldHelp[1])) $fieldNote = $fieldHelp[1];
        }
        elseif($field == 'labels')
        {
            list($fieldName, $fieldNote) = explode('|', $help->file->labels);
        }
        $this->view->header->title = $fieldName;
        $this->view->fieldName = $fieldName;
        $this->view->fieldNote = $fieldNote;
        $this->display();
    }
}

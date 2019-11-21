<?php
/**
 * The control file of dev module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author    chunsheng wang <chunsheng@cnezsoft.com> 
 * @package
 * @uses      control
 * @license   LGPL
 * @version   $Id$
 * @Link      http://www.zentao.net
 */
class dev extends control
{
    /**
     * Get API of system.
     *
     * @access public
     * @return void
     */
    public function api($module = '')
    {
        $this->view->title          = $this->lang->dev->api;
        $this->view->position[]     = html::a(inlink('api'), $this->lang->dev->common);
        $this->view->position[]     = $this->lang->dev->api;

        $this->view->tables         = $this->dev->getTables();
        $this->view->tab            = 'api';
        $this->view->selectedModule = $module;
        $this->view->apis           = $module ? $this->dev->getAPIs($module) : array();
        $this->view->modules        = $this->dev->getModules();
        $this->display();
    }

    /**
     * Get schema of database.
     *
     * @param  string $table
     * @access public
     * @return void
     */
    public function db($table = '')
    {
        $this->view->title         = $this->lang->dev->db;
        $this->view->position[]    = html::a(inlink('api'), $this->lang->dev->common);
        $this->view->position[]    = $this->lang->dev->db;

        $this->view->tables        = $this->dev->getTables();
        $this->view->selectedTable = $table;
        $this->view->tab           = 'db';
        $this->view->fields        = $table ? $this->dev->getFields($table) : array();
        $this->display();
    }

    /**
     * Editor.
     * 
     * @access public
     * @return void
     */
    public function editor()
    {
        $this->view->title      = $this->lang->dev->editor;
        $this->view->position[] = html::a(inlink('api'), $this->lang->dev->common);
        $this->view->position[] = $this->lang->dev->editor;

        $this->view->tab = 'editor';
        $this->display();
    }

    /**
     * Translate.
     * 
     * @access public
     * @return void
     */
    public function translate()
    {
        $this->view->title      = $this->lang->dev->translate;
        $this->view->position[] = $this->lang->dev->translate;

        $this->display();
    }
}

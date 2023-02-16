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

    /**
     * Lang item.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function langItem($type = 'common', $module = '', $method = '', $language = 'zh_cn')
    {
        $language   = str_replace('_', '-', $language);
        $moduleName = $module;
        if($type == 'common') $moduleName = 'common';
        if($type == 'second') $moduleName = $module . 'Menu';
        if($type == 'third')  $moduleName = $module . 'subMenu';

        if($_POST)
        {
            $section = '';
            if($type == 'common') $section = '&section=';
            if($type == 'first')  $section = '&section=mainNav';
            $this->loadModel('custom')->deleteItems("lang={$language}&module={$moduleName}&vision={$this->config->vision}{$section}");

            $data = fixer::input('post')->get();
            foreach($data as $langKey => $customedLang)
            {
                if(strpos($langKey, "{$moduleName}_") !== 0) continue;
                if(empty($customedLang)) continue;

                $this->custom->setItem("{$language}." . str_replace('_', '.', $langKey), $customedLang);
            }
            return $this->send(array('result' => 'success', 'locate' => 'reload', 'message' => $this->lang->saveSuccess));
        }

        $this->dev->loadDefaultLang();

        $this->view->title         = $this->lang->langItem;
        $this->view->type          = $type;
        $this->view->featureBar    = $this->lang->dev->featureBar['langItem'];
        $this->view->originalLangs = $this->dev->getOriginalLang($type, $module, $method, $language);
        $this->view->customedLangs = $this->dev->getCustomedLang($type, $module, $method, $language);
        $this->view->moduleName    = $moduleName;
        $this->view->language      = $language;
        $this->display();
    }
}

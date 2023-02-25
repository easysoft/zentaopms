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
     *  Custom menu lang item.
     *
     * @param  string $type       common|first|second|third|tag
     * @param  string $module
     * @param  string $method
     * @param  string $language   zh_cn|en|fr|de|zh_tw
     * @access public
     * @return void
     */
    public function langItem($type = 'common', $module = '', $method = '', $language = '')
    {
        $clientLang = $this->app->getClientLang();
        if(empty($language)) $language = $clientLang;
        $language = str_replace('_', '-', $language);

        if($type == 'second' and empty($module)) $module = 'my';
        $moduleName = $module;
        if($type == 'common' or $type == 'first') $moduleName = 'common';
        if($type == 'second') $moduleName = $module . 'Menu';

        if($type == 'third')
        {
            if($this->config->vision == 'lite')
            {
                $module = $module == '' ? 'kanbanProject' : $module;
                $method = $method == '' ? 'settings' : $method;
            }
            elseif(empty($method))
            {
                $module = 'my';
                $method = 'work';
            }
            $moduleName = $module . 'SubMenu';
        }

        if($type == 'tag')
        {
            if(empty($module)) $module = 'my';
            if(empty($method)) $method = 'todo';

            $moduleName = $module;
        }

        if($this->server->request_method == 'POST')
        {
            $this->dev->saveCustomedLang($common, $moduleName, $method, $language);
            return $this->send(array('result' => 'success', 'locate' => 'reload', 'message' => $this->lang->saveSuccess));
        }

        if($clientLang != $language)
        {
            $currentCommonLang = $this->config->custom->commonLang;

            $commonLang = $this->dev->getOriginalLang('common', '', '', $language);
            $commonLang = array_merge($commonLang, $this->dev->getCustomedLang('common', '', '', $language));
            foreach($commonLang as $commonKey => $langValue)
            {
                $upperKey = '$' . strtoupper($commonKey);
                if(isset($this->config->custom->commonLang[$upperKey])) $this->config->custom->commonLang[$upperKey] = $langValue;
            }

            $this->view->currentLangs      = $this->dev->getOriginalLang($type, $module, $method, $clientLang);
            $this->view->currentCommonLang = $currentCommonLang;
        }

        $this->view->title         = $this->lang->langItem;
        $this->view->type          = $type;
        $this->view->originalLangs = $this->dev->getOriginalLang($type, $module, $method, $language);
        $this->view->customedLangs = $this->dev->getCustomedLang($type, $module, $method, $language);
        $this->view->menuTree      = $this->dev->getMenuTree($type, $module, $method);
        $this->view->moduleName    = $moduleName;
        $this->view->module        = $module;
        $this->view->method        = $method;
        $this->view->language      = str_replace('-', '_', $language);
        $this->display();
    }

    /**
     * Reset customed menu lang.
     *
     * @param  string $type       common|first|second|third|tag
     * @param  string $module
     * @param  string $method
     * @param  string $language   zh_cn|en|fr|de|zh_tw
     * @param  string $confirm    no|yes
     * @access public
     * @return void
     */
    public function resetLang($type = 'common', $module = '', $method = '', $language = 'zh_cn', $confirm = 'no')
    {
        if($confirm == 'no') return print(js::confirm($this->lang->dev->confirmRestore, inlink('resetLang', "type={$type}&module={$module}&method={$method}&language={$language}&confirm=yes")));

        $language = str_replace('_', '-', $language);
        $section  = '';
        if($type == 'common') $section = '&section=';
        if($type == 'first')  $section = '&section=mainNav';
        if($type == 'tag')    $section = str_replace('_', '-', "&section=featureBar-{$method}");
        $this->loadModel('custom')->deleteItems("lang={$language}&module={$module}&vision={$this->config->vision}{$section}");
        if($type == 'common' and $this->config->custom->URSR)
        {
            $oldValue = $this->dao->select('*')->from(TABLE_LANG)->where('`key`')->eq($this->config->custom->URSR)->andWhere('section')->eq('URSRList')->andWhere('lang')->eq($language)->andWhere('module')->eq('custom')->fetch('value');
            if($oldValue)
            {
                $oldValue = json_decode($oldValue);
                $_POST    = array();
                $_POST['SRName'] = zget($oldValue, 'defaultSRName', $oldValue->SRName);
                $_POST['URName'] = zget($oldValue, 'defaultURName', $oldValue->URName);
                $this->custom->updateURAndSR($this->config->custom->URSR, $language);
            }
        }

        return print(js::reload('parent'));
    }
}

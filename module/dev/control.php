<?php
/**
 * The control file of dev module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
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
     * @param  string $module
     * @param  int    $apiID
     * @access public
     * @return void
     */
    public function api($module = 'restapi', $apiID = 1)
    {
        if($module == 'restapi') return print($this->fetch('dev', 'restAPI', "apiID=$apiID"));

        $this->view->title          = $this->lang->dev->api;

        $this->view->tab            = 'api';
        $this->view->selectedModule = $module;
        $this->view->apis           = $module ? $this->dev->getAPIs($module) : array();
        $this->view->moduleTree     = $this->dev->getTree($module, 'module');
        $this->display();
    }

    /**
     * Get rest api list.
     *
     * @param  int    $apiID
     * @access public
     * @return void
     */
    public function restAPI($apiID = 1)
    {
        list($api, $typeList, $menu) = $this->dev->getAPIData($apiID);
        if($api) $api->desc = htmlspecialchars_decode($api->desc);

        $this->view->title          = $this->lang->dev->api;
        $this->view->selectedModule = 'restapi';
        $this->view->moduleTree     = $menu;
        $this->view->typeList       = $typeList;
        $this->view->api            = $api;
        $this->view->apiID          = $apiID;
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
        if(empty($table)) $table = 'zt_todo';

        $this->view->title         = $this->lang->dev->db;
        $this->view->tableTree     = $this->dev->getTree($table, 'table');
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

        $this->view->tab = 'editor';
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
            $this->dev->saveCustomedLang($type, $moduleName, $method, $language);
            return $this->send(array('result' => 'success', 'load' => true, 'message' => $this->lang->saveSuccess));
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

            $currentLangs      = $this->dev->getOriginalLang($type, $module, $method, $clientLang);
            $currentCommonLang = $currentCommonLang;
        }

        $this->view->title             = $this->lang->langItem;
        $this->view->type              = $type;
        $this->view->originalLangs     = $this->dev->getOriginalLang($type, $module, $method, $language);
        $this->view->customedLangs     = $this->dev->getCustomedLang($type, $module, $method, $language);
        $this->view->menuTree          = $this->dev->getMenuTree($type, $module, $method);
        $this->view->moduleName        = $moduleName;
        $this->view->module            = $module;
        $this->view->method            = $method;
        $this->view->language          = str_replace('-', '_', $language);
        $this->view->currentLangs      = isset($currentLangs) ? $currentLangs : array();
        $this->view->currentCommonLang = isset($currentCommonLang) ? $currentCommonLang : array();
        $this->display();
    }

    /**
     * Reset customed menu lang.
     *
     * @param  string $type       common|first|second|third|tag
     * @param  string $module
     * @param  string $method
     * @param  string $language   zh_cn|en|fr|de|zh_tw
     * @access public
     * @return void
     */
    public function resetLang($type = 'common', $module = '', $method = '', $language = 'zh_cn')
    {
        $section  = '';
        $language = str_replace('_', '-', $language);
        if($type == 'common') $section = '&section=';
        if($type == 'first')  $section = '&section=mainNav';
        if($type == 'tag')
        {
            if($this->config->vision == 'lite' and isset($this->config->dev->liteTagMethod["$module-$method"])) $method = $this->config->dev->liteTagMethod["$module-$method"];
            $section = str_replace('_', '-', "&section=featureBar-{$method}");
            $this->dao->delete()->from(TABLE_LANG)->where('lang')->eq($language)->andWhere('module')->eq($module)->andWhere('section')->like("moreSelects-$method%")->andWhere('vision')->eq($this->config->vision)->exec();
        }

        $key = '';
        if($type == 'common') $key = '&key=projectCommon,productCommon,executionCommon';

        $this->loadModel('custom')->deleteItems("lang={$language}&module={$module}&vision={$this->config->vision}{$section}{$key}");
        if($this->config->vision == 'rnd' and $type == 'common' and $this->config->custom->URSR)
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

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }
}

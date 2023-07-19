<?php
/**
 * The control file of ops of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jiangxiu Peng <pengjiangxiu@cnezsoft.com>
 * @package     ops
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class ops extends control
{
    /**
     * Index. 
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->createLink('deploy', 'browse'));
    }

    public function provider()
    {
        $this->setting('serverroom', 'provider');
    }

    public function city()
    {
        $this->setting('serverroom', 'city');
    }
    
    public function cpuBrand()
    {
        $this->setting('host', 'cpuBrand');
    }

    public function os($field = 'osVersion')
    {
        $this->setting('host', $field);
    }

    public function setting($module = 'serverroom', $field = 'provider', $currentLang = '')
    {
        if($module == 'host') $module = 'zahost';
        if($field == 'osVersion') $field = 'linux';

        $fieldList = $field . 'List';
        if($_POST)
        {
            $this->loadModel('custom');
            $lang = $_POST['lang'];
            $this->custom->deleteItems("lang=$lang&module=$module&section=$fieldList");
            foreach($_POST['keys'] as $index => $key)
            {
                $key    = htmlspecialchars($key);
                $value  = htmlspecialchars($_POST['values'][$index]);
                $system = htmlspecialchars($_POST['systems'][$index]);

                $this->custom->setItem("{$lang}.$module.$fieldList.{$key}.{$system}", $value);
            }
            if(dao::isError()) die(js::error(dao::getError()));
            $target = isonlybody() ? 'parent.parent' : 'parent';
            $lang   = str_replace('-', '_', $lang);
            die(js::locate($this->createLink('ops', $this->methodName, "field={$field}"), $target));
        }

        $langModule = $module;
        if($langModule == 'host') $langModule = 'zahost';

        $this->app->loadLang('custom');
        $this->app->loadLang($langModule);

        if(empty($currentLang)) $currentLang = str_replace('-', '_', $this->app->getClientLang());

        $this->view->title = $this->lang->ops->setting;
        $this->view->position[] = $this->lang->ops->setting;

        $this->view->module      = $module;
        $this->view->langModule  = $langModule;
        $this->view->field       = $field;
        $this->view->fieldList   = $fieldList;
        $this->view->currentLang = $currentLang;
        $this->display();
    }
}

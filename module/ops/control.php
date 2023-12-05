<?php
/**
 * The control file of ops of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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

    /**
     * 管理机房供应商信息。
     * Manger provider options of serverroom. 
     * 
     * @param string $currentLang
     * @access public
     * @return void
     */
    public function provider($currentLang = '')
    {
        $this->setting('serverroom', 'provider', 'provider', $currentLang);
    }

    /**
     * 管理机房城市信息。
     * Manger city options of serverroom. 
     * 
     * @param string $currentLang
     * @access public
     * @return void
     */
    public function city($currentLang = '')
    {
        $this->setting('serverroom', 'city', 'city', $currentLang);
    }
    
    /**
     * 管理主机CPU品牌信息。
     * Manger cpuBrand options of host. 
     * 
     * @param string $currentLang
     * @access public
     * @return void
     */
    public function cpuBrand($currentLang = '')
    {
        $this->setting('host', 'cpuBrand', 'cpuBrand', $currentLang);
    }

    /**
     * 管理主机系统版本信息。
     * Manger OS options of host. 
     * 
     * @param string $currentLang
     * @param string $field
     * @access public
     * @return void
     */
    public function os($currentLang = '', $field = 'linux')
    {
        $this->setting('host', 'os', $field, $currentLang);
    }

    /**
     * 自定义语言项。
     * Manger options of lang. 
     * @param string $currentLang
     * @access public
     * @return void
     */
    public function setting($module, $method, $field = 'provider', $currentLang = '')
    {
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
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $lang = str_replace('-', '_', $lang);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('ops', $method, "lang=$lang&field=$field")));
        }

        $this->app->loadLang('custom');
        $this->app->loadLang($module);

        if(empty($currentLang)) $currentLang = str_replace('-', '_', $this->app->getClientLang());

        $this->view->title      = $this->lang->ops->setting;
        $this->view->position[] = $this->lang->ops->setting;

        $this->view->module      = $module;
        $this->view->field       = $field;
        $this->view->fieldList   = $fieldList;
        $this->view->currentLang = $currentLang;
        $this->display();
    }
}

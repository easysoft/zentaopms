<?php
/**
 * The control file of screen module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@cnezsoft.com>
 * @package     task
 * @version     $Id: control.php 5106 2022-11-18 17:15:54Z $
 * @link        https://www.zentao.net
 */
class screen extends control
{
    /**
     * Browse screen list.
     *
     * @param  int $dimensionID
     * @access public
     * @return void
     */
    public function browse($dimensionID = 0)
    {
        $dimensionID = $this->loadModel('dimension')->setSwitcherMenu($dimensionID);
        $this->checkShowGuide();

        $this->view->title      = $this->lang->screen->common;
        $this->view->screens    = $this->screen->getList($dimensionID);
        $this->view->dimension  = $dimensionID;
        $this->display();
    }

    /**
     * Check whether show guide.
     *
     * @access public
     * @return void
     */
    public function checkShowGuide()
    {
        $this->app->loadLang('admin');
        $this->loadModel('setting');

        $isUpdate = $this->setting->getItem("owner=system&module=bi&key=update2BI");
        if(empty($isUpdate))
        {
            $this->view->showGuide = false;
            return;
        }

        $lang     = (strpos($this->app->getClientLang(), 'zh') !== false) ? 'zh' : 'en';
        $version  = $this->config->edition == 'pms' ? 'pms' : 'biz';
        $imageURL = "static/images/bi_guide_{$version}_{$lang}.png";

        $moduleKey   = $version . 'Guide';
        $guides      = $this->setting->getItem("owner=system&module=bi&key={$moduleKey}");
        $haveSeen    = explode(',', $guides);
        $afterSeen   = array_merge($haveSeen, array($this->app->user->account));
        $this->setting->setItem("system.bi.{$moduleKey}", implode(',', array_unique($afterSeen)));

        $this->view->showGuide  = in_array($this->app->user->account, $haveSeen) ? false : true;
        $this->view->imageURL   = $imageURL;
        $this->view->version    = $version;
    }

    /**
     * View screen.
     *
     * @param  int $screenID
     * @param  int $year
     * @param  int $month
     * @param  int $dept
     * @param  string $account
     * @access public
     * @return void
     */
    public function view($screenID, $year = 0, $month = 0, $dept = 0, $account = '')
    {
        if(empty($year))  $year  = date('Y');
        if(empty($month)) $month = date('n');

        if($screenID == 3)
        {
            echo $this->fetch('report', 'annualData', "year=$year&month=$month&dept=$dept&account=$account");
            return;
        }

        $screen = $this->screen->getByID($screenID, $year, $month, $dept, $account);

        $this->view->title  = $screen->name;
        $this->view->screen = $screen;

        if($screenID == 5)
        {
            $this->loadModel('execution');
            $this->view->executions = $this->screen->getBurnData();
            $this->view->date       = date('Y-m-d H:i:s');
            $this->display('screen', 'burn');
        }
        else
        {
            $this->view->year    = $year;
            $this->view->month   = $month;
            $this->view->dept    = $dept;
            $this->view->account = $account;
            $this->display();
        }
    }
}

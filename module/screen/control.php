<?php
/**
 * The control file of screen module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
        $dimensionID = $this->commonAction($dimensionID);

        $this->view->title   = $this->lang->screen->common;
        $this->view->screens = $this->screen->getList($dimensionID);
        $this->display();
    }

    /**
     * View screen.
     *
     * @param  int $screenID
     * @param  int $year
     * @param  int $dept
     * @param  string $account
     * @access public
     * @return void
     */
    public function view($screenID, $year = 2022, $dept = 0, $account = '')
    {
        if($screenID == 3) $this->locate($this->createLink('report', 'annualData'));

        $screen = $this->screen->getByID($screenID, $year, $dept, $account);
        $this->view->title  = $screen->name;
        $this->view->screen = $screen;

        if($screenID == 5)
        {
            $this->loadModel('execution');
            $this->view->executions = $this->screen->getBurnData();
            $this->view->date       = date('Y-m-d h:i:s');
            $this->display('screen', 'burn');
        }
        else
        {
            $this->view->year    = $year;
            $this->view->dept    = $dept;
            $this->view->account = $account;
            $this->display();
        }
    }
}

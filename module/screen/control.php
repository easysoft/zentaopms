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
     * Construct function, load dimension.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $appName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
        $this->loadModel('dimension');
    }

    /**
     * Common Action.
     *
     * @param  int    $dimensionID
     * @param  bool   $setMenu
     * @access public
     * @return void
     */
    public function commonAction($dimensionID = 0, $setMenu = true)
    {
        $dimensions = $this->dimension->getList();

        $dimensionID = $this->dimension->saveState($dimensionID, $dimensions);
        if($setMenu) $this->dimension->setMenu($dimensionID);
        $this->loadModel('setting')->setItem($this->app->user->account . 'common.dimension.lastDimension', $dimensionID);

        return $dimensionID;
    }

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
        if($screenID == 5)
        {
            $this->loadModel('execution');
            $this->view->title      = $this->lang->screen->common;
            $this->view->executions = $this->screen->getBurnData();
            $this->view->date       = date('Y-m-d h:i:s', time());
            $this->display('screen', 'burn');
        }
        else
        {
            $this->view->title   = $this->lang->screen->common;
            $this->view->year    = $year;
            $this->view->dept    = $dept;
            $this->view->account = $account;
            $this->view->screen  = $this->screen->getByID($screenID, $year, $dept, $account);
            $this->display();
        }
    }
}

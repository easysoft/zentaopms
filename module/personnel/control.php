<?php
/**
 * The control file of personnel of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     personnel
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class personnel extends control
{
    /**
     * Get a list of people who can be accessed.
     *
     * @param  int    $programID
     * @param  int    $deptID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function accessible($programID = 0, $deptID = 0, $browseType='browse', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->loadModel('program');
        $this->lang->navGroup->program     = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction() . $this->program->getPGMSwitcher($programID);
        $this->program->setPGMViewMenu($programID);

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = $browseType == 'browse' ? 0 : (int)$param;
        $actionURL = $this->createLink('personnel', 'accessible', "pargramID=$programID&deptID=$deptID&param=myQueryID&type=bysearch");
        $this->personnel->buildSearchForm($queryID, $actionURL);

        $this->view->title      = $this->lang->personnel->accessible;
        $this->view->position[] = $this->lang->personnel->accessible;

        $this->view->deptID     = $deptID;
        $this->view->programID  = $programID;
        $this->view->orderBy    = $orderBy;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->browseType = $browseType;
        $this->view->dept       = $this->loadModel('dept')->getByID($deptID);
        $this->view->deptTree   = $this->personnel->getTreeMenu($deptID = 0, array('personnelModel', 'createMemberLink'), $programID);

        $this->display();
    }

    /**
     * Access to investable personnel.
     *
     * @param  int    $browseType
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function putInto($programID = 0, $browseType = 'all', $orderBy = 'id_desc')
    {
        $this->loadModel('program');
        $this->lang->navGroup->program     = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction() . $this->program->getPGMSwitcher($programID);
        $this->program->setPGMViewMenu($programID);

        $inputPersonnel = $this->personnel->getInputPersonnel($programID, $browseType, $orderBy);

        $this->view->title      = $this->lang->personnel->putInto;
        $this->view->position[] = $this->lang->personnel->putInto;

        $this->view->programID      = $programID;
        $this->view->orderBy        = $orderBy;
        $this->view->browseType     = $browseType;
        $this->view->inputPersonnel = $inputPersonnel;

        $this->display();
    }
}

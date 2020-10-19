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
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function accessible($programID = 0, $deptID = 0, $browseType='browse', $param = 0, $orderBy = 't2.id_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->loadModel('program');
        $this->app->loadLang('user');
        $this->lang->navGroup->program     = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction() . $this->program->getPGMSwitcher($programID);
        $this->program->setPGMViewMenu($programID);

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = $browseType == 'bysearch' ? (int)$param : 0;
        $actionURL = $this->createLink('personnel', 'accessible', "pargramID=$programID&deptID=$deptID&browseType=bysearch&quertID=myQueryID");
        $deptList  = $this->loadModel('dept')->getDeptPairs($deptID);
        $this->config->personnel->accessible->search['params']['dept']['values']   = $deptList;
        $this->config->personnel->accessible->search['params']['role']['values']   = $this->lang->user->roleList;
        $this->config->personnel->accessible->search['params']['gender']['values'] = $this->lang->user->genderList;
        $this->personnel->buildSearchForm($queryID, $actionURL);

        $personnelList = $this->personnel->getAccessiblePersonnel($programID, $deptID, $browseType, $orderBy, $queryID, $pager);

        $this->view->title      = $this->lang->personnel->accessible;
        $this->view->position[] = $this->lang->personnel->accessible;

        $this->view->deptID        = $deptID;
        $this->view->programID     = $programID;
        $this->view->recTotal      = $recTotal;
        $this->view->recPerPage    = $recPerPage;
        $this->view->pageID        = $pageID;
        $this->view->pager         = $pager;
        $this->view->param         = $param;
        $this->view->orderBy       = $orderBy;
        $this->view->browseType    = $browseType;
        $this->view->personnelList = $personnelList;
        $this->view->dept          = $this->dept->getByID($deptID);
        $this->view->deptList      = $deptList;
        $this->view->deptTree      = $this->personnel->getTreeMenu($deptID = 0, array('personnelModel', 'createMemberLink'), $programID);

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

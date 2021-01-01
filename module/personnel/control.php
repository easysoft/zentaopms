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
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function accessible($programID = 0, $deptID = 0, $browseType='browse', $param = 0, $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->setProgramNavMenu($programID);
        $this->app->loadLang('user');

        $program = $this->loadModel('program')->getPGMByID($programID);

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = $browseType == 'bysearch' ? (int)$param : 0;
        $actionURL = $this->createLink('personnel', 'accessible', "pargramID=$programID&deptID=$deptID&browseType=bysearch&quertID=myQueryID");
        $this->config->personnel->accessible->search['params']['role']['values']   = $this->lang->user->roleList;
        $this->config->personnel->accessible->search['params']['gender']['values'] = $this->lang->user->genderList;
        $this->personnel->buildSearchForm($queryID, $actionURL);

        $this->view->title      = $this->lang->personnel->accessible;
        $this->view->position[] = $this->lang->personnel->accessible;

        $this->view->deptID        = $deptID;
        $this->view->programID     = $programID;
        $this->view->acl           = $program->acl;
        $this->view->recTotal      = $recTotal;
        $this->view->recPerPage    = $recPerPage;
        $this->view->pageID        = $pageID;
        $this->view->pager         = $pager;
        $this->view->param         = $param;
        $this->view->browseType    = $browseType;
        $this->view->personnelList = $this->personnel->getAccessiblePersonnel($programID, $deptID, $browseType, $queryID, $pager);
        $this->view->deptList      = $this->loadModel('dept')->getOptionMenu();
        $this->view->dept          = $this->dept->getByID($deptID);
        $this->view->deptTree      = $this->dept->getTreeMenu($deptID = 0, array(new personnelModel, 'createMemberLink'), $programID);

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
    public function putInto($programID = 0)
    {
        $this->setProgramNavMenu($programID);

        $this->view->title          = $this->lang->personnel->putInto;
        $this->view->position[]     = $this->lang->personnel->putInto;
        $this->view->inputPersonnel = $this->personnel->getInputPersonnel($programID);

        $this->display();
    }

    /**
     * Get white list personnel.
     *
     * @param  int    $objectID
     * @param  string $module     personnel|program|project|product
     * @param  string $objectType program|project|product|sprint
     * @param  string $orderby
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function whitelist($objectID = 0, $module = 'personnel', $objectType = 'program', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($module == 'personnel') $this->setProgramNavMenu($objectID);

        if($module == 'product')
        {
            $moduleIndex = array_search('product', $this->lang->noMenuModule);
            if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);
        }

        /* Load lang and set session. */
        $this->app->loadLang('user');
        $this->app->session->set('whitelistBrowse', $this->app->getURI(true));

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Set back link. */
        if($module == 'program')
        {
            $goback = $this->session->PRJBrowse ? $this->session->PRJBrowse : $this->createLink('program', 'PRJBrowse');
            $this->view->goback = $goback;
        }

        $this->view->title      = $this->lang->personnel->whitelist;
        $this->view->position[] = $this->lang->personnel->whitelist;

        $this->view->pager      = $pager;
        $this->view->objectID   = $objectID;
        $this->view->whitelist  = $this->personnel->getWhitelist($objectID, $objectType, $orderBy, $pager);
        $this->view->depts      = $this->loadModel('dept')->getOptionMenu();
        $this->view->module     = $module;

        $this->display();
    }

    /**
     * Adding users to the white list.
     *
     * @param  int     $objectID
     * @param  int     $deptID
     * @param  string  $objectType  program|project|product|sprint
     * @param  string  $module
     * @access public
     * @return void
     */
    public function addWhitelist($objectID = 0, $deptID = 0, $objectType = 'program', $module = 'personnel')
    {
        if($module == 'personnel') $this->setProgramNavMenu($objectID);
        $this->app->loadLang('project');

        if($_POST)
        {
            $this->personnel->addWhitelist($objectType, $objectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => $this->getError()));

            $moduleMethod = $module == 'program' ? 'PRJWhitelist' : 'whitelist';
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink($module, $moduleMethod, "objectID=$objectID")));
        }

        $this->loadModel('dept');
        $deptUsers = empty($deptID) ? array() : $this->dept->getDeptUserPairs($deptID);

        $this->view->title      = $this->lang->personnel->addWhitelist;
        $this->view->position[] = $this->lang->personnel->addWhitelist;

        $this->view->objectID   = $objectID;
        $this->view->objectType = $objectType;
        $this->view->module     = $module;
        $this->view->deptID     = $deptID;
        $this->view->deptUsers  = $deptUsers;
        $this->view->whitelist  = $this->personnel->getWhitelist($objectID, $objectType);
        $this->view->depts      = $this->dept->getOptionMenu();
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->dept       = $this->dept->getByID($deptID);

        $this->display();
    }

    /*
     * Removing users from the white list.
     *
     * @param  int     $id
     * @param  string  $confirm
     * @access public
     * @return void
     */
    public function unbindWhielist($id = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->personnel->confirmDelete, inLink('unbindWhielist',"id=$id&confirm=yes")));
        }
        else
        {
            $acl = $this->dao->select('*')->from(TABLE_ACL)->where('id')->eq($id)->fetch();
            if(empty($acl)) die(js::reload('parent'));

            $objectTable  = $acl->objectType == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            $whitelist    = $this->dao->select('whitelist')->from($objectTable)->where('id')->eq($acl->objectID)->fetch('whitelist');
            $newWhitelist = str_replace(',' . $acl->account, '', $whitelist);
            $this->dao->update($objectTable)->set('whitelist')->eq($newWhitelist)->where('id')->eq($acl->objectID)->exec();
            $this->dao->delete()->from(TABLE_ACL)->where('id')->eq($id)->exec();

            if($acl->objectType == 'product') $this->personnel->deleteProgramWhitelist($acl->objectID, $acl->account);
            if($acl->objectType == 'sprint')  $this->personnel->deleteProjectWhitelist($acl->objectID, $acl->account);

            die(js::reload('parent'));
        }
    }

    /*
     * Setting up the program's navigation menu.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function setProgramNavMenu($programID = 0)
    {
        $this->loadModel('program');
        $this->lang->navGroup->program     = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction() . $this->program->getPGMSwitcher($programID, true);
        $this->program->setPGMViewMenu($programID);
    }
}

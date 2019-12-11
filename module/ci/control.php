<?php
/**
 * The control file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: control.php 5144 2019-12-11 06:37:03Z chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class ci extends control
{
    /**
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        /* Load need modules. */
        $this->loadModel('credential');
        $this->loadModel('user');
    }

    /**
     * CI index page.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->display();
    }

    /**
     * Browse credentials.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseCredential($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->credential->common . $this->lang->colon . $this->lang->credential->list;
        $this->view->credentialList   = $this->ci->listCredential($orderBy, $pager);

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = $this->lang->credential->common;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module      = 'credential';
        $this->display();
    }

    /**
     * Create a credential.
     *
     * @access public
     * @return void
     */
    public function createCredential()
    {
        if($_POST)
        {
            $this->ci->createCredential();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseCredential')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->credential->create . $this->lang->colon . $this->lang->credential->common;
        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseCredential'), $this->lang->credential->common);
        $this->view->position[]    = $this->lang->credential->create;

        $this->view->module      = 'credential';
        $this->display();
    }

    /**
     * Edit a credential.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function editCredential($id)
    {
        $credential = $this->ci->getCredentialByID($id);
        if($_POST)
        {
            $this->ci->updateCredential($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseCredential')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->credential->edit . $this->lang->colon . $credential->name;

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseCredential'), $this->lang->credential->common);
        $this->view->position[]    = $this->lang->credential->edit;

        $this->view->credential    = $credential;

        $this->view->module      = 'credential';
        $this->display();
    }

    /**
     * Delete a credential.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function deleteCredential($id)
    {
        $this->ci->delete(TABLE_CREDENTIAL, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }


    /**
     * Browse jenkinss.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseJenkins($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->jenkins->list;
        $this->view->jenkinsList   = $this->ci->listJenkins($orderBy, $pager);

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = $this->lang->jenkins->common;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module      = 'jenkins';
        $this->display();
    }

    /**
     * Create a jenkins.
     *
     * @access public
     * @return void
     */
    public function createJenkins()
    {
        if($_POST)
        {
            $this->ci->createJenkins();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseJenkins')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->jenkins->create . $this->lang->colon . $this->lang->jenkins->common;
        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseJenkins'), $this->lang->jenkins->common);
        $this->view->position[]    = $this->lang->jenkins->create;

        $this->view->module      = 'jenkins';
        $this->display();
    }

    /**
     * Edit a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function editJenkins($id)
    {
        $jenkins = $this->ci->getJenkinsByID($id);
        if($_POST)
        {
            $this->ci->updateJenkins($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseJenkins')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->jenkins->edit . $this->lang->colon . $jenkins->name;

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseJenkins'), $this->lang->jenkins->common);
        $this->view->position[]    = $this->lang->jenkins->edit;

        $this->view->jenkins    = $jenkins;

        $this->view->module      = 'jenkins';
        $this->display();
    }

    /**
     * Delete a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function deleteJenkins($id)
    {
        $this->ci->delete(TABLE_JENKINS, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }
}

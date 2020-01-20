<?php

/**
 * The control file of cijenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class cijenkins extends control
{
    /**
     * cijenkins constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
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
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->ci->list;
        $this->view->jenkinsList   = $this->cijenkins->listAll($orderBy, $pager);

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = $this->lang->jenkins->common;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module      = 'cijenkins';
        $this->display();
    }

    /**
     * Create a jenkins.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->cijenkins->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->jenkins->create . $this->lang->colon . $this->lang->jenkins->common;
        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browse'), $this->lang->jenkins->common);
        $this->view->position[]    = $this->lang->jenkins->create;

        $this->view->credentialsList  = $this->loadModel('cicredentials')->listForSelection("type='token' or type='account'");
        $this->view->module      = 'cijenkins';

        $this->display();
    }

    /**
     * Edit a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $jenkins = $this->cijenkins->getByID($id);
        if($_POST)
        {
            $this->cijenkins->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->jenkins->edit . $this->lang->colon . $jenkins->name;

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browse'), $this->lang->jenkins->common);
        $this->view->position[]    = $this->lang->jenkins->edit;

        $this->view->jenkins    = $jenkins;
        $this->view->credentialsList  = $this->loadModel('cicredentials')->listForSelection("type='token' or type='account'");

        $this->view->module      = 'cijenkins';
        $this->display();
    }

    /**
     * Delete a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->cijenkins->delete(TABLE_JENKINS, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }
}
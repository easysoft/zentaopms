<?php

/**
 * The control file of cicredentials module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class cicredentials extends control
{
    /**
     * ci constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
    }

    /**
     * Browse credentials
     *
     * @param string $orderBy
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->credentials->common . $this->lang->colon . $this->lang->ci->list;
        $this->view->credentialsList   = $this->cicredentials->listAll($orderBy, $pager);

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = $this->lang->credentials->common;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module      = 'cicredentials';
        $this->display();
    }

    /**
     * Create a credentials.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->cicredentials->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->credentials->create . $this->lang->colon . $this->lang->credentials->common;
        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browse'), $this->lang->credentials->common);
        $this->view->position[]    = $this->lang->credentials->create;

        $this->view->module      = 'cicredentials';
        $this->display();
    }

    /**
     * Edit a credentials.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $credentials = $this->cicredentials->getByID($id);
        if($_POST)
        {
            $this->cicredentials->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->credentials->edit . $this->lang->colon . $credentials->name;

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browse'), $this->lang->credentials->common);
        $this->view->position[]    = $this->lang->credentials->edit;

        $this->view->credentials    = $credentials;

        $this->view->module      = 'cicredentials';
        $this->display();
    }

    /**
     * Delete a credentials.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->cicredentials->delete(TABLE_CREDENTIALS, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }
}
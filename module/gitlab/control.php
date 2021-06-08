<?php

/**
 * The control file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class gitlab extends control
{
    /**
     * Browse gitlab.
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
        if(common::hasPriv('gitlab', 'create')) $this->lang->TRActions = html::a(helper::createLink('gitlab', 'create'), "<i class='icon icon-plus'></i> " . $this->lang->gitlab->create, '', "class='btn btn-primary'");

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->browse;
        $this->view->position[] = $this->lang->gitlab->common;
        $this->view->position[] = $this->lang->gitlab->browse;

        $this->view->gitlabList = $this->gitlab->getList($orderBy, $pager);
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Create a gitlab.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $tokenValid = $this->gitlab->getPermissionsByToken($this->post->url, $this->post->token);
            if($tokenValid['result'] == 'fail') $this->send($tokenValid);

            $gitlabID = $this->gitlab->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($this->viewType == 'json') $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $gitlabID));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->create;

        $this->view->position[] = html::a(inlink('browse'), $this->lang->gitlab->common);
        $this->view->position[] = $this->lang->gitlab->create;

        $this->display();
    }

    /**
     * bind a gitlab.
     *
     * @access public
     * @return void
     */
    public function bind()
    {
        var_dump($this->gitlab->getPermissionsByToken('http://127.0.0.1:8090/','rpz2PZKriNE6TD34QtDA'));
        die;

        $this->display();
    }

    /**
     * Edit a gitlab.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $gitlab = $this->gitlab->getByID($id);
        if($_POST)
        {
            $tokenValid = $this->gitlab->getPermissionsByToken($this->post->url, $this->post->token);
            if($tokenValid['result'] == 'fail') $this->send($tokenValid);

            $this->gitlab->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->position[] = html::a(inlink('browse'), $this->lang->gitlab->common);
        $this->view->position[] = $this->lang->gitlab->edit;

        $this->view->title  = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->edit;
        $this->view->gitlab = $gitlab;

        $this->display();
    }

    /**
     * Delete a gitlab.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id, $confim = 'no')
    {
        if($confim != 'yes') die(js::confirm($this->lang->gitlab->confirmDelete, inlink('delete', "id=$id&confirm=yes")));

        $this->gitlab->delete(TABLE_PIPLINE, $id);
        die(js::reload('parent'));
    }

    /**
     * Ajax get gitlab token permissions.
     *
     * @param  string    $host
     * @param  string    $token
     * @access public
     * @return void
     */
    public function ajaxCheckToken($host, $token)
    {
        $host  = helper::safe64Decode($host);
        $permissions = $this->gitlab->getPermissionsByToken($host, $token);
        $this->send($permissions);
    }
}

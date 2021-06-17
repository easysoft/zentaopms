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
            if(strpos($host, 'http') !== 0) $this->send(array('result' => 'fail', 'message' => array('url' => array($this->lang->gitlab->hostError))));
            if(!$this->post->token) $this->send(array('result' => 'fail', 'message' => array('token' => array($this->lang->gitlab->tokenError))));

            $user = $this->gitlab->apiGetCurrentUser($this->post->url, $this->post->token);

            if(!is_object($user)) $this->send(array('result' => 'fail', 'message' => array('url' => array($this->lang->gitlab->hostError))));
            if(isset($user->is_admin) and $user->is_admin == true) $this->send(array('result' => 'success'));
            $this->send(array('result' => 'fail', 'message' => array('token' => array($this->lang->gitlab->tokenError))));

            $gitlabID = $this->gitlab->create();

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->create;

        $this->view->position[] = html::a(inlink('browse'), $this->lang->gitlab->common);
        $this->view->position[] = $this->lang->gitlab->create;

        $this->display();
    }

    /**
     * Bind gitlab user to zentao users.
     *
     * @access public
     * @return void
     */
    public function bindUser($gitlabID)
    {
        $userPairs     = $this->loadModel('user')->getPairs();
        if($_POST)
        {
            $users = $this->post->zentaoUsers;
            $accountList = array();
            $repeatUsers = array();
            foreach($users as $openID => $user)
            {
                if(empty($user)) continue;
                if(isset($accountList[$user])) $repeatUsers[] = zget($userPairs, $user);
                $accountList[$user] = $openID;
            }

            if(count($repeatUsers)) $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->gitlab->bindUserError, join(',', $repeatUsers))));

            $user = new stdclass;
            $user->providerID   = $gitlabID;
            $user->providerType = 'gitlab';

            foreach($users as $openID => $account)
            {
                if(!$account) continue;
                $user->account = $account;
                $user->openID  = $openID;

                $this->dao->delete(TABLE_OAUTH)
                          ->where('openID')->eq($user->openID)
                          ->andWhere('providerType')->eq('gitlab')
                          ->andWhere('providerID')->eq($id)
                          ->andWhere('account')->eq($user->account)
                          ->exec();

                $this->dao->insert(TABLE_OAUTH)->data($user)->exec();
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->sever->http_referer));
        }

        $gitlab      = $this->gitlab->getByID($gitlabID);
        $zentaoUsers = $this->dao->select('account,email,realname')->from(TABLE_USER)->fetchAll('account');

        $this->view->title         = $this->lang->gitlab->bindUser;
        $this->view->userPairs     = $userPairs;
        $this->view->gitlabUsers   = $this->gitlab->apiGetUsers($gitlab);
        $this->view->matchedResult = $this->gitlab->getMatchedUsers($gitlabID, $this->view->gitlabUsers, $zentaoUsers);
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
            $tokenValid = $this->gitlab->apiGetCurrentUser($this->post->url, $this->post->token);
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

        $this->gitlab->delete(TABLE_PIPELINE, $id);
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
        $host = helper::safe64Decode($host);
        $permissions = $this->gitlab->apiGetCurrentUser($host, $token);
        $this->send($permissions);
    }

    public function createHook($gitlab_id, $project_id, $url, $token)
    {
        $res = $this->gitlab->apiCreateHook($gitlab_id, $project_id, $url, $token);
        return $res;
    }

}

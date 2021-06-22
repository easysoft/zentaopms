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
            $this->checkToken();
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
            $this->checkToken();
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
     * Bind gitlab user to zentao users.
     *
     * @access public
     * @return void
     */
    public function bindUser($gitlabID)
    {
        $userPairs = $this->loadModel('user')->getPairs();

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

            $this->dao->delete()
                      ->from(TABLE_OAUTH)
                      ->where('providerType')->eq($user->providerType)
                      ->andWhere('providerID')->eq($user->providerID)
                      ->exec();

            foreach($users as $openID => $account)
            {
                if(!$account) continue;
                $user->account = $account;
                $user->openID  = $openID;

                $this->dao->delete()
                          ->from(TABLE_OAUTH)
                          ->where('openID')->eq($user->openID)
                          ->andWhere('providerType')->eq($user->providerType)
                          ->andWhere('providerID')->eq($user->providerID)
                          ->andWhere('account')->eq($user->account)
                          ->exec();

                $this->dao->insert(TABLE_OAUTH)->data($user)->exec();
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
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
     * Bind product and gitlab projects.
     * 
     * @param  int    $gitlabID 
     * @access public
     * @return void
     */
    public function bindProduct($gitlabID)
    {
        $this->view->projectPairs   = $this->gitlab->getProjectPairs($gitlabID);
        $this->view->title          = $this->lang->gitlab->bindProduct;
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
     * Check post token has admin permissions.
     * 
     * @access public
     * @return void
     */
    public function checkToken()
    {
        if(strpos($this->post->url, 'http') !== 0) $this->send(array('result' => 'fail', 'message' => array('url' => array($this->lang->gitlab->hostError))));
        if(!$this->post->token) $this->send(array('result' => 'fail', 'message' => array('token' => array($this->lang->gitlab->tokenError))));

        $user = $this->gitlab->apiGetCurrentUser($this->post->url, $this->post->token);

        if(!is_object($user)) $this->send(array('result' => 'fail', 'message' => array('url' => array($this->lang->gitlab->hostError))));
        if(!isset($user->is_admin) or !$user->is_admin) $this->send(array('result' => 'fail', 'message' => array('token' => array($this->lang->gitlab->tokenError))));
    }

    /**
     * Webhook api.
     * 
     * @access public
     * @return void
     */
    public function webhook()
    {
        $product = $this->get->product;
        $gitlab  = $this->get->gitlab;
        $project = $this->get->project;

        $json = '{"object_kind":"note","event_type":"note","user":{"id":1,"name":"Administrator","username":"root","avatar_url":"https://www.gravatar.com/avatar/e64c7d89f26bd1972efa854d13d7dd61?s=80\u0026d=identicon","email":"admin@example.com"},"project_id":36,"project":{"id":36,"name":"zenops202106dgd","description":"repo for dingguodong dev env","web_url":"http://192.168.1.161:51080/root/zenops202106dgd","avatar_url":null,"git_ssh_url":"ssh://git@192.168.1.161:51022/root/zenops202106dgd.git","git_http_url":"http://192.168.1.161:51080/root/zenops202106dgd.git","namespace":"Administrator","visibility_level":0,"path_with_namespace":"root/zenops202106dgd","default_branch":"master","ci_config_path":null,"homepage":"http://192.168.1.161:51080/root/zenops202106dgd","url":"ssh://git@192.168.1.161:51022/root/zenops202106dgd.git","ssh_url":"ssh://git@192.168.1.161:51022/root/zenops202106dgd.git","http_url":"http://192.168.1.161:51080/root/zenops202106dgd.git"},"object_attributes":{"attachment":null,"author_id":1,"change_position":null,"commit_id":null,"created_at":"2021-06-22 03:04:00 UTC","discussion_id":"1e66ea56b811ac13502252c62e1d22a6ddcf4340","id":705,"line_code":null,"note":"124ds q ew 1234 142dsf ssdfgsdg","noteable_id":32,"noteable_type":"Issue","original_position":null,"position":null,"project_id":36,"resolved_at":null,"resolved_by_id":null,"resolved_by_push":null,"st_diff":null,"system":false,"type":null,"updated_at":"2021-06-22 03:04:00 UTC","updated_by_id":null,"description":"124ds q ew 1234 142dsf ssdfgsdg","url":"http://192.168.1.161:51080/root/zenops202106dgd/-/issues/2#note_705"},"repository":{"name":"zenops202106dgd","url":"ssh://git@192.168.1.161:51022/root/zenops202106dgd.git","description":"repo for dingguodong dev env","homepage":"http://192.168.1.161:51080/root/zenops202106dgd"},"issue":{"author_id":1,"closed_at":null,"confidential":false,"created_at":"2021-06-16 10:52:00 UTC","description":"the issue description","discussion_locked":null,"due_date":null,"id":32,"iid":2,"last_edited_at":null,"last_edited_by_id":null,"milestone_id":null,"moved_to_id":null,"duplicated_to_id":null,"project_id":36,"relative_position":1026,"state_id":1,"time_estimate":0,"title":"test issue","updated_at":"2021-06-22 03:04:00 UTC","updated_by_id":1,"url":"http://192.168.1.161:51080/root/zenops202106dgd/-/issues/2","total_time_spent":0,"human_total_time_spent":null,"human_time_estimate":null,"assignee_ids":[],"assignee_id":null,"labels":[{"id":38,"title":"zentao task","color":"#0033CC","project_id":36,"created_at":"2021-06-16 12:04:23 UTC","updated_at":"2021-06-16 12:04:23 UTC","template":false,"description":"task label from zentao","type":"ProjectLabel","group_id":null},{"id":40,"title":"zentao task : 121","color":"#428BCA","project_id":36,"created_at":"2021-06-21 08:58:17 UTC","updated_at":"2021-06-21 08:58:17 UTC","template":false,"description":"121","type":"ProjectLabel","group_id":null}],"state":"opened"}}';
        //$json = file_get_contents('php://input');
        $requestBody = json_decode($json);
        $request = $this->gitlab->webhookParseBody($requestBody);
        
        exit;
        
        switch($request->type)
        {
            case 'issue_create':
                $this->createTaskFromIssue($request);
                break;
            case 'issue_update':
                $this->updateTaskFromIssue($request);
                break;
        }

        $logFile = $this->app->getLogRoot() . 'webhook.'. date('Ymd') . '.log.php';
        if(!file_exists($logFile)) file_put_contents($logFile, '<?php die(); ?' . '>');
        
        $fh = @fopen($logFile, 'a');
        if($fh)
        {
            fwrite($fh, date('Ymd H:i:s') . ": " . $this->app->getURI() . "\n");
            fwrite($fh, "JSON:   " . $json . "\n");
            fwrite($fh, "GET:   " . print_r($_GET, true) . "\n");
            fwrite($fh, "Body:  " . var_export($requestBody, true) . "\n");
            fclose($fh);
        }

        $this->view->result = 'success';
        $this->view->status = 'ok';
        $this->view->data   = 'ougiugjvh';
        $this->display();
    }
}

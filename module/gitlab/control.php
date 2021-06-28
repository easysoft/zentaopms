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
        $this->view->projectPairs = $this->gitlab->getProjectPairs($gitlabID);
        $this->view->title        = $this->lang->gitlab->bindProduct;
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
        $this->gitlab->webhookCheckToken();
        $product = $this->get->product;
        $gitlab  = $this->get->gitlab;
        $project = $this->get->project;

        $input = '{"object_kind":"issue","event_type":"issue","user":{"id":1,"name":"Administrator","username":"root","avatar_url":"https://www.gravatar.com/avatar/e64c7d89f26bd1972efa854d13d7dd61?s=80\u0026d=identicon","email":"admin@example.com"},"project":{"id":36,"name":"zenops202106dgd","description":"repo for dingguodong dev env","web_url":"http://192.168.1.161:51080/root/zenops202106dgd","avatar_url":null,"git_ssh_url":"ssh://git@192.168.1.161:51022/root/zenops202106dgd.git","git_http_url":"http://192.168.1.161:51080/root/zenops202106dgd.git","namespace":"Administrator","visibility_level":0,"path_with_namespace":"root/zenops202106dgd","default_branch":"master","ci_config_path":null,"homepage":"http://192.168.1.161:51080/root/zenops202106dgd","url":"ssh://git@192.168.1.161:51022/root/zenops202106dgd.git","ssh_url":"ssh://git@192.168.1.161:51022/root/zenops202106dgd.git","http_url":"http://192.168.1.161:51080/root/zenops202106dgd.git"},"object_attributes":{"author_id":1,"closed_at":null,"confidential":false,"created_at":"2021-06-22 02:38:01 UTC","description":"扩展动作的公式保存之后再点设置按钮报js错误\nnew issue 0622 desc ee\n![elephant](http://192.168.1.161:51080/root/zenops202106dgd/uploads/9b85fd397a602b628a271ce02264f502/elephant.jpg)","discussion_locked":null,"due_date":"2021-06-25","id":33,"iid":3,"last_edited_at":"2021-06-24 02:53:52 UTC","last_edited_by_id":1,"milestone_id":null,"moved_to_id":null,"duplicated_to_id":null,"project_id":36,"relative_position":1539,"state_id":1,"time_estimate":0,"title":"new issue 0622","updated_at":"2021-06-24 02:53:52 UTC","updated_by_id":1,"url":"http://192.168.1.161:51080/root/zenops202106dgd/-/issues/3","total_time_spent":0,"human_total_time_spent":null,"human_time_estimate":null,"assignee_ids":[8],"assignee_id":8,"labels":[{"id":38,"title":"zentao_objectType","color":"#0033CC","project_id":36,"created_at":"2021-06-16 12:04:23 UTC","updated_at":"2021-06-22 05:17:29 UTC","template":false,"description":"task","type":"ProjectLabel","group_id":null},{"id":40,"title":"zentao_task/12738","color":"#428BCA","project_id":36,"created_at":"2021-06-21 08:58:17 UTC","updated_at":"2021-06-24 02:46:14 UTC","template":false,"description":"type:bug,id:1234,url:https://back.zcorp.cc/pms/project-browse-1291-all.html","type":"ProjectLabel","group_id":null}],"state":"opened","action":"update"},"labels":[{"id":38,"title":"zentao_objectType","color":"#0033CC","project_id":36,"created_at":"2021-06-16 12:04:23 UTC","updated_at":"2021-06-22 05:17:29 UTC","template":false,"description":"task","type":"ProjectLabel","group_id":null},{"id":40,"title":"zentao_task/12738","color":"#428BCA","project_id":36,"created_at":"2021-06-21 08:58:17 UTC","updated_at":"2021-06-24 02:46:14 UTC","template":false,"description":"type:bug,id:1234,url:https://back.zcorp.cc/pms/project-browse-1291-all.html","type":"ProjectLabel","group_id":null}],"changes":{},"repository":{"name":"zenops202106dgd","url":"ssh://git@192.168.1.161:51022/root/zenops202106dgd.git","description":"repo for dingguodong dev env","homepage":"http://192.168.1.161:51080/root/zenops202106dgd"},"assignees":[{"id":8,"name":"dingguodong","username":"dingguodong","avatar_url":"https://www.gravatar.com/avatar/11302c6dd9e26e9f8e4517538f51e4a2?s=80\u0026d=identicon","email":"dingguodong@easycorp.ltd"}]}';
        //$input       = file_get_contents('php://input');
        $requestBody = json_decode($input);
        $result      = $this->gitlab->webhookParseBody($requestBody, $gitlab);

        $logFile = $this->app->getLogRoot() . 'webhook.'. date('Ymd') . '.log.php';
        if(!file_exists($logFile)) file_put_contents($logFile, '<?php die(); ?' . '>');
        
        $fh = @fopen($logFile, 'a');
        if($fh)
        {
            fwrite($fh, date('Ymd H:i:s') . ": " . $this->app->getURI() . "\n");
            fwrite($fh, "JSON: \n  " . $input . "\n");
            fwrite($fh, "Parsed object: {$result->objectType} :\n  " . print_r($result->object, true) . "\n");
            fclose($fh);
        }

        if($result->action = 'updateissue') $this->gitlab->webhookSyncIssue($gitlab, $result);

        $this->view->result = 'success';
        $this->view->status = 'ok';
        $this->view->data   = $result->object;
        $this->display();
    }

    public function test($id = 11)
    {
        $gitlabID = 1; $projectID = 7;
        $task = $this->loadModel('task')->getByID($id);
        $issue = $this->gitlab->taskToIssue($gitlabID, $projectID, $task);
        $issue = $this->gitlab->apiCreateIssue($gitlabID, $projectID, $issue);
        $this->gitlab->saveSyncedIssue('task', $task, $gitlabID, $issue);
        exit;
    }
}

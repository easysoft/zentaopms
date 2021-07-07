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

        $this->view->position[] = $this->lang->gitlab->common;
        $this->view->position[] = $this->lang->gitlab->browse;

        $this->view->title      = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->browse;
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

            /* Delete binded users and save new relationship.*/
            $this->dao->delete()->from(TABLE_OAUTH)->where('providerType')->eq($user->providerType)->andWhere('providerID')->eq($user->providerID)->exec();
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

        $input       = file_get_contents('php://input');
        $requestBody = json_decode($input);
        $result      = $this->gitlab->webhookParseBody($requestBody, $gitlab);

        $logFile = $this->app->getLogRoot() . 'webhook.'. date('Ymd') . '.log.php';
        if(!file_exists($logFile)) file_put_contents($logFile, '<?php die(); ?' . '>');
        
        $fh = @fopen($logFile, 'a');
        if($fh)
        {
            fwrite($fh, date('Ymd H:i:s') . ": " . $this->app->getURI() . "\n");
            fwrite($fh, "JSON: \n  " . $input . "\n");
            fwrite($fh, "Parsed object: {$result->issue->objectType} :\n  " . print_r($result->object, true) . "\n");
            fclose($fh);
        }

        if($result->action == 'updateissue' and isset($result->changes->assignees)) $this->gitlab->webhookAssignIssue($result);

        //if($result->action = 'reopenissue') $this->gitlab->webhookIssueReopen($gitlab, $result);

        if($result->action == 'closeissue') $this->gitlab->webhookCloseIssue($result);

        if($result->action == 'updateissue') $this->gitlab->webhookSyncIssue($gitlab, $result);

        $this->view->result = 'success';
        $this->view->status = 'ok';
        $this->view->data   = $result->object;
        $this->display();
    }

    public function test($id = 11)
    {
        $gitlabID = 1; $projectID = 7;
        $task = $this->loadModel('task')->getByID($id);
        $relations = $this->gitlab->getRelationByObject('task', $task->id);

        a($relations);exit;
        $issue = $this->gitlab->taskToIssue($gitlabID, $projectID, $task);
        $issue = $this->gitlab->apiCreateIssue($gitlabID, $projectID, $issue);
        $this->gitlab->saveSyncedIssue('task', $task, $gitlabID, $issue);
        exit;
    }

    /**
     * Import gitlab issue to zentaopms. 
     * 
     * @access public
     * @return void
     */
    public function importIssue()
    {
        $productIDList  = explode(',', $this->get->product);
        $gitlabID       = $this->get->gitlab;
        $projectID      = $this->get->project;
        
        
        if($_POST)
        {
            $executionList  = $this->post->executionList;
            $objectTypeList = $this->post->objectTypeList;
            $productList    = $this->post->productList;
            foreach($executionList as $issueID => $executionID)
            {
                if($executionID) 
                {
                    $objectType = $this->config->gitlab->objectTypes[$objectTypeList[$issueID]];
                    $issue = $this->gitlab->apiGetSingleIssue($gitlabID, $projectID, $issueID);
                    $issue->objectType    = $objectType;
                    $issue->objectID      = 0; // meet the required parameters for issueToZentaoObject.
                    if(isset($issue->assignee)) $issue->assignee_id   = $issue->assignee->id;
                    $issue->updated_by_id = $issue->author->id; // here can be replaced by current zentao user.

                    $object = $this->gitlab->issueToZentaoObject($issue, $gitlabID);
                    if($objectType == 'task')
                    {
                        $objectID = $this->loadModel('task')->createTaskFromGitlabIssue($object, $executionID);
                    }

                    if($objectType == 'bug')
                    {
                    }

                    if($objectType == 'story')
                    {
                    }
                    
                    $object->id        = $objectID;
                    $object->product   = $productList[$issueID];
                    $object->execution = $executionID;
                    $this->gitlab->saveImportedIssue($gitlabID, $projectID, $objectType, $objectID, $issue, $object);

                }
                else
                {
                    if($productList[$issueID] != 0) $this->send(array('result' => 'fail', 'message' => $this->lang->gitlab->importIssueError, 'locate' => $this->server->http_referer));
                }
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
        }

        $savedIssueIDList = $this->dao->select('BID as issueID')->from(TABLE_RELATION)
                                 ->where('relation')->eq('gitlab')
                                 ->andWhere('product')->in($productIDList)
                                 ->fetchAll('issueID');
        $iids = '';
        foreach($savedIssueIDList as $savedIssueID) $iids = $iids . $savedIssueID->issueID . ',';
        $options = '&not[iids]=' . trim($iids, ',');
        $gitlabIssues = $this->gitlab->apiGetIssues($gitlabID, $projectID, $options); //TODO(dingguodong) when no issues here?

        $products = array();
        $products[] = '';
        foreach($productIDList as $productID)
        {
            $products[$productID] = $this->loadModel("product")->getByID($productID)->name;
        }

        $this->view->products        = $products;
        $this->view->gitlabID        = $gitlabID;
        $this->view->gitlabProjectID = $projectID;
        $this->view->objectTypes     = $this->config->gitlab->objectTypes;

        $this->view->gitlabIssues    = $gitlabIssues;
        $this->display();
    }

    /**
     * Ajax get executions by productID.
     * 
     * @param  int    $productID 
     * @access public
     * @return string
     */
    public function ajaxGetExecutionsByProduct($productID)
    {
        if(!$productID) $this->send(array('message' => array()));
        
        $executions = $this->loadModel('product')->getAllExecutionPairsByProduct($productID);
        $options = "<option value=''></option>";
        foreach($executions as $index =>$execution)
        {
            $options .= "<option value='{$index}' data-name='{$execution}'>{$execution}</option>";
        }
        die($options);
    }

}

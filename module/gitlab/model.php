<?php
/**
 * The model file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class gitlabModel extends model
{
    /**
     * Get a gitlab by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->loadModel('pipeline')->getByID($id);
    }

    /**
     * Get gitlab list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->loadModel('pipeline')->getList('gitlab', $orderBy, $pager);
    }

    /**
     * Get gitlab pairs
     *
     * @return array
     */
    public function getPairs()
    {
        return $this->loadModel('pipeline')->getPairs('gitlab');
    }

    /**
     * Get gitlab user id zentao account pairs of one gitlab.
     * 
     * @param  int    $gitlab 
     * @access public
     * @return void
     */
    public function getUserIdAccountPairs($gitlab)
    {
        return $this->dao->select('openID,account')->from(TABLE_OAUTH)
                    ->where('providerType')->eq('gitlab')
                    ->andWhere('providerID')->eq($gitlab)
                    ->fetchPairs();
    }

    /**
     * Get zentao account gitlab user id pairs of one gitlab.
     * 
     * @param  int    $gitlab 
     * @access public
     * @return void
     */
    public function getUserAccountIdPairs($gitlab)
    {
        return $this->dao->select('account,openID')->from(TABLE_OAUTH)
                    ->where('providerType')->eq('gitlab')
                    ->andWhere('providerID')->eq($gitlab)
                    ->fetchPairs();
    }

    /**
     * Create a gitlab.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        return $this->loadModel('pipeline')->create('gitlab');
    }

    /**
     * Update a gitlab.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        return $this->loadModel('pipeline')->update($id);
    }

    /**
     * Get current user.
     *
     * @param  string   $host
     * @param  string   $token
     * @access public
     * @return array
     */
    public function apiGetCurrentUser($host, $token)
    {
        $api = rtrim($host, '/') . "/api/v4/user?private_token=$token";
        return json_decode(commonModel::http($api));
    }

    /**
     * Get gitlab user list.
     *
     * @param  string   $host
     * @param  string   $token
     * @access public
     * @return array
     */

    public function apiGetUsers($gitlab)
    {
        $api      = rtrim($gitlab->url, '/') . '/api/v4/users?private_token=' . $gitlab->token;
        $response = json_decode(commonModel::http($api));

        if (!$response) return array();
        $users = array();

        foreach($response as $gitlabUser)
        {
            $user = new stdclass;
            $user->id       = $gitlabUser->id;
            $user->realname = $gitlabUser->name;
            $user->account  = $gitlabUser->username;
            $user->email    = $gitlabUser->email;
            $user->avatar   = $gitlabUser->avatar_url;

            $users[] = $user;
        }
        return $users;
    }

    /**
     * Get matched gitlab users.
     * 
     * @param  array    $gitlabUsers 
     * @param  array    $zentaoUsers 
     * @access public
     * @return array
     */
    public function getMatchedUsers($gitlabID, $gitlabUsers, $zentaoUsers)
    {
        $matches = new stdclass;
        foreach($gitlabUsers as $gitlabUser)
        {
            foreach($zentaoUsers as $zentaoUser)
            {
                if($gitlabUser->account  == $zentaoUser->account)  $matches->accounts[$gitlabUser->account][] = $zentaoUser->account;
                if($gitlabUser->realname == $zentaoUser->realname) $matches->names[$gitlabUser->realname][]   = $zentaoUser->account;
                if($gitlabUser->email    == $zentaoUser->email)    $matches->emails[$gitlabUser->email][]     = $zentaoUser->account;
            }
        }

        $bindedUsers = $this->dao->select('openID,account')
                                 ->from(TABLE_OAUTH)
                                 ->where('providerType')->eq('gitlab')
                                 ->andWhere('providerID')->eq($gitlabID)
                                 ->fetchPairs();

        $matchedUsers = array();
        foreach($gitlabUsers as $gitlabUser)
        {
            if(isset($bindedUsers[$gitlabUser->id])) 
            {
                $gitlabUser->zentaoAccount = $bindedUsers[$gitlabUser->id];
                $matchedUsers[] = $gitlabUser;
                continue;
            }

            $matchedZentaoUsers = array();
            if(isset($matches->accounts[$gitlabUser->account])) $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->accounts[$gitlabUser->account]);
            if(isset($matches->emails[$gitlabUser->email]))     $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->emails[$gitlabUser->email]);
            if(isset($matches->names[$gitlabUser->realname]))   $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->names[$gitlabUser->realname]);

            $matchedZentaoUsers = array_unique($matchedZentaoUsers);
            if(count($matchedZentaoUsers) == 1)
            {
                $gitlabUser->zentaoAccount = current($matchedZentaoUsers);
                $matchedUsers[] = $gitlabUser;
            }
        }

        return $matchedUsers;
    }

    /**
     * Get projects of one gitlab.
     * 
     * @param  int    $gitlabID 
     * @access public
     * @return void
     */
    public function apiGetProjects($gitlabID)
    {   
        $gitlab = $this->getByID($gitlabID);
        if(!$gitlab) return array();
        $host  = rtrim($gitlab->url, '/');
        $host .= '/api/v4/projects';

        $allResults = array();
        for($page = 1; true; $page ++) 
        {  
            $results = json_decode(commonModel::http($host . "?private_token={$gitlab->token}&simple=true&membership=true&page={$page}&per_page=100"));
            if(empty($results) or $page > 10) break;
            $allResults = $allResults + $results;
        }   
        return $allResults;
    }

    public function getProjectPairs($gitlabID)
    {
        $projects = $this->apiGetProjects($gitlabID);
        $projectPairs = array();
        foreach($projects as $project)
        {
            $projectPairs[$project->id] = $project->name_with_namespace;
        }

        return $projectPairs;
    }

    public function getProjectDisplayName($gitlabID, $projectID)
    {
        return array_key_exists($gitlabID, $projectID) ? $this->gitlab->getProjectPairs($gitlabID)[$projectID]: "";
    }

    /**
     * Get gitlab api base url with access_token
     * 
     * @param  int    $id 
     * @access public
     * @return string gitlab api base url with access_token
     */
    public function getApiRoot($id)
    {
        $gitlab = $this->getByID($id);
        if(!$gitlab) return "";
        $gitlab_url = rtrim($gitlab->url, '/').'/api/v4%s'."?private_token={$gitlab->token}";
        return $gitlab_url; 
    }

    /**
     * Bind gitlab project.
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getProjectsByExecution($executionID)
    {
        $products      = $this->loadModel('execution')->getProducts($executionID, false);
        $productIdList = array_keys($products);

        return $this->dao->select('AID,BID as gitlabProject')
                         ->from(TABLE_RELATION)
                         ->where('relation')->eq('interrated') 
                         ->andWhere('AType')->eq('gitlab')
                         ->andWhere('BType')->eq('gitlabProject') 
                         ->andWhere('product')->in($productIdList) 
                         ->fetchGroup('AID');
    }

    /**
     * Create relationship between zentao product and  gitlab project.
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function saveRelation($products, $gitlabID, $gitlabProjectID)
    {
        $programs = $this->dao->select('id,program')->from(TABLE_PRODUCT)->where('id')->in($products)->fetchPairs();

        $relation = new stdclass;
        $relation->execution = 0;
        $relation->AType     = 'gitlab';
        $relation->AID       = $gitlabID;
        $relation->AVersion  = '';
        $relation->relation  = 'interrated';
        $relation->BType     = 'gitlabProject';
        $relation->BID       = $gitlabProjectID;
        $relation->BVersion  = '';
        $relation->extra     = '';

        foreach($products as $product)
        {
            $relation->project = zget($programs, $product, 0);
            $relation->product = $product;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }
        return true;
    }

    /**
     * Create webhook for zentao.
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function createWebhook($products, $gitlabID, $projectID)
    {
        $urls = $this->getWebhookUrls($gitlabID, $projectID);

        foreach($products as $index => $product)
        {
            $url  = sprintf($this->config->gitlab->zentaoApiWebhookUrl, commonModel::getSysURL(), $product, $gitlabID, $projectID);
            if(! array_key_exists($url, array_flip($urls)))
            {
                $response = $this->apiCreateHook($gitlabID, $projectID, $url, $this->config->gitlab->zentaoApiWebhookToken);
            }
        }
        return true;
    }

    /**
     * Get hooks.
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function apiGetHooks($gitlabID, $projectID)
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $apiPath = "/projects/{$projectID}/hooks";
        $url = sprintf($apiRoot, $apiPath);
        $response = json_decode(commonModel::http($url));
        return $response;
    }

    /**
     * Get specific hook. 
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @param  int    $hookID 
     * @access public
     * @return void
     */
    public function apiGetHook($gitlabID, $projectID, $hookID)
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $apiPath = "/projects/$projectID/hooks/$hookID)";
        $url = sprintf($apiRoot, $apiPath);
        $response = commonModel::http($url);
        return $response;
    }  

    /**
     * Get webhook urls 
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @access public
     * @return array $urls;
     */
    public function getWebhookUrls($gitlabID, $projectID)
    {
        $urls = array();
        $webhooks = $this->apiGetHooks($gitlabID, $projectID);
        foreach($webhooks as $index => $webhook)
        {
            $urls[] = $webhook->url;
        }
        return $urls;
    }

    /**
     * Create hook.
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @param  int    $url 
     * @param  int    $token 
     * @access public
     * @return void
     */
    public function apiCreateHook($gitlabID, $projectID, $url, $token)
    {  
        $apiRoot = $this->getApiRoot($gitlabID);

        $postData = new stdclass;
        $postData->enable_ssl_verification = "false";
        $postData->issues_events           = "true";
        $postData->merge_requests_events   = "true";
        $postData->push_events             = "true";
        $postData->tag_push_events         = "true";
        $postData->url                     = $url;
        $postData->token                   = $token;

        $apiPath = "/projects/{$projectID}/hooks";
        $url = sprintf($apiRoot, $apiPath);
        $response = commonModel::http($url, $postData); 
        return $response;
    }

    /**
     * Delete hook. 
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @param  int    $hookID 
     * @access public
     * @return void
     */
    public function apiDeleteHook($gitlabID, $projectID, $hookID)
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $apiPath = "/projects/{$projectID}/hooks/{$hookID}";
        $url = sprintf($apiRoot, $apiPath);
        $response = commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'delete'));
        return $response;
    }

    /**
     * Update hook. 
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @param  int    $hookID 
     * @access public
     * @return void
     */
    public function apiUpdateHook($gitlabID, $projectID, $hookID)
    {
        $apiRoot = $this->getApiRoot($gitlabID);

        $postData = new stdclass;
        $postData->enable_ssl_verification = "false";
        $postData->issues_events           = "true";
        $postData->merge_requests_events   = "true";
        $postData->push_events             = "true";
        $postData->tag_push_events         = "true";
        $postData->note_events             = "true";
        $postData->url                     = $url;
        $postData->token                   = $token;

        $apiPath = "/projects/{$projectID}/hooks/{$hookID}";
        $url = sprintf($apiRoot, $apiPath);
        $response = commonModel::http($url, $postData, $options = array(CURLOPT_CUSTOMREQUEST => 'put'));
        return $response;
    }

    /**
     * Create Label for gitlab project.
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @param  int    $label 
     * @access public
     * @return void
     */
    public function apiCreateLabel($gitlabID, $projectID, $label)
    {
        $apiRoot = $this->getApiRoot($gitlabID);

        if(empty($label->name) or empty($label->color)) return false;

        $apiPath = "/projects/{$projectID}/labels/";
        $url = sprintf($apiRoot, $apiPath);
        $response = commonModel::http($url, $label);
        return $response;
    }

    /**
     * Get labels of project. 
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function apiGetLabels($gitlabID, $projectID)
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $apiPath = "/projects/{$projectID}/labels/";
        $url = sprintf($apiRoot, $apiPath);
        $response = commonModel::http($url);
        $labels = json_decode($response);
        return $labels;
    }

    /**
     * Check if predefined label exist in project. 
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function isLabelExists($gitlabID, $projectID)
    {
        $labels = $this->apiGetLabels($gitlabID, $projectID);
        if(empty($labels)) return false;
        foreach($labels as $label)
        {
            if(strpos($label->name, $this->config->gitlab->taskLabel->name) == 0) return true;
            if(strpos($label->name, $this->config->gitlab->bugLabel->name) == 0) return true;
            if(strpos($label->name, $this->config->gitlab->storyLabel->name) == 0) return true;
        }

        return false;
    }

    /**
     * Create predefined labels for project. 
     * 
     * @param  int    $gitlabID 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function initLabels($gitlabID, $projectID)
    {
        if($this->isLabelExists($gitlabID, $projectID)) return true;

        $taskLabel = new stdclass();
        $taskLabel->name            = $this->config->gitlab->taskLabel->name;
        $taskLabel->description     = $this->config->gitlab->taskLabel->description;
        $taskLabel->color           = $this->config->gitlab->taskLabel->color;
        $taskLabel->priority        = $this->config->gitlab->taskLabel->priority;

        $bugLabel = new stdclass();
        $bugLabel->name             = $this->config->gitlab->bugLabel->name;
        $bugLabel->description      = $this->config->gitlab->bugLabel->description;
        $bugLabel->color            = $this->config->gitlab->bugLabel->color;
        $bugLabel->priority         = $this->config->gitlab->bugLabel->priority;

        $storyLabel = new stdclass();
        $storyLabel->name             = $this->config->gitlab->storyLabel->name;
        $storyLabel->description      = $this->config->gitlab->storyLabel->description;
        $storyLabel->color            = $this->config->gitlab->storyLabel->color;
        $storyLabel->priority         = $this->config->gitlab->storyLabel->priority;
        
        $this->apiCreateLabel($gitlabID, $projectID, $taskLabel);
        $this->apiCreateLabel($gitlabID, $projectID, $bugLabel);
        $this->apiCreateLabel($gitlabID, $projectID, $storyLabel);

        return;
    }

    /**
     * Sync task to gitlab issue.
     * 
     * @param  int    $taskID 
     * @param  int    $gitlab 
     * @param  int    $gitlabProject 
     * @access public
     * @return void
     */
    public function syncTask($taskID, $gitlab, $gitlabProject)
    {
        $task = $this->loadModel('task')->getByID($taskID);
        $syncedIssue = $this->getSyncedIssue($objectType = 'task', $objectID = $taskID, $gitlab);

        $issue = $this->taskToIssue($gitlab, $gitlabProject, $task);
        if($syncedIssue) $issue = $this->apiUpdateIssue($gitlab, $gitlabProject, $syncedIssue, $issue);
        $issue = $this->apiCreateIssue($gitlab, $gitlabProject, $issue);

        $this->saveSyncedIssue('task', $task, $gitlab, $issue);
    }
    
    /**
     * Get synced issue from relation table.
     * 
     * @param  string    $objectType 
     * @param  int       $objectID 
     * @param  int       $gitlab 
     * @access public
     * @return object
     */
    public function getSyncedIssue($objectType, $objectID, $gitlab)
    {
        return $this->dao->select('*')->from(TABLE_RELATION)
            ->where('AType')->eq($objectType)
            ->andWhere('AID')->eq($objectID)
            ->andWhere('extra')->eq($gitlab)
            ->fetch();
    }

    /**
     * Save synced issue to relation table.
     * 
     * @param  string   $objectType 
     * @param  object   $object 
     * @param  int      $gitlab 
     * @param  object   $issue 
     * @access public
     * @return void
     */
    public function saveSyncedIssue($objectType, $object, $gitlab, $issue)
    {
        $relation = new stdclass;
        $relation->execution = zget($object, 'execution', 0);
        $relation->AType     = $objectType;
        $relation->AID       = $object->id;
        $relation->AVersion  = '';
        $relation->relation  = 'gitlab';
        $relation->BType     = 'issue';
        $relation->BID       = $issue->iid;
        $relation->BVersion  = $issue->project_id;
        $relation->extra     = $gitlab;
        $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
    }

    /**
     * Parse task to issue.
     * 
     * @param  int    $gitlabID 
     * @param  int    $gitlabProjectID 
     * @param  object    $task 
     * @access public
     * @return object
     */
    public function taskToIssue($gitlabID, $gitlabProjectID, $task)
    {
        $map = $this->config->gitlab->maps->task;
        $issue = new stdclass;
        $gitlabUsers = $this->getUserAccountIdPairs($gitlabID);
        foreach($map as $taskField => $config)
        {
            $value = '';
            list($field, $optionType, $options) = explode('|', $config);
            if($optionType == 'field') $value = $task->$taskField;
            if($optionType == 'userPairs') $value = zget($gitlabUsers, $task->$taskField);
            if($optionType == 'configItems') 
            {
                $value = zget($this->config->gitlab->$options, $task->$taskField, '');
            }
            if($value) $issue->$field = $value;
        }
        return $issue;
    }

    public function apiCreateIssue($gitlabID, $projectID, $issue)
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $apiPath = "/projects/{$projectID}/issues/";
        foreach($this->config->gitlab->skippedFields->issueCreate as $field)
        {
            if(isset($issue->$field)) unset($issue->$field);
        }

        $url = sprintf($apiRoot, $apiPath);
        return json_decode(commonModel::http($url, $issue));
    }

    /**
     * Update issue with new attribute using gitlab API.
     * 
     * @param  int       $gitlabID 
     * @param  int       $projectID 
     * @param  int       $issueID 
     * @param  object    $attribute 
     * @access public
     * @return object
     */
    public function apiUpdateIssue($gitlabID, $projectID, $issueID, $attribute)
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $apiPath = "/projects/{$projectID}/issues/{$issueID}";
        $url = sprintf($apiRoot, $apiPath);
        $response =  json_decode(commonModel::http($url, $attribute, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        return $response;
    }    

    public function pushTask($gitlabID, $projectID, $task)
    {
        $task->label = $this->config->gitlab->taskLabel->name;
        $response = $this->apiCreateIssue($gitlabID, $projectID, $task);
        return $response;
    }

    public function pushBug($gitlabID, $projectID, $bug)
    {
        $bug->label = $this->config->gitlab->bugLabel->name;
        $response = $this->apiCreateIssue($gitlabID, $projectID, $bug);
        return $response;
    }

    /**
     * Parse webhook body function.
     * 
     * @param  object    $body 
     * @param  int       $gitlabID
     * @access public
     * @return object
     */
    public function webhookParseBody($body, $gitlabID)
    {
        $type = zget($body, 'object_kind', '');
        if(!$type or !is_callable(array($this, "webhookParse{$type}"))) return false;
        return call_user_func_array(array($this, "webhookParse{$type}"), array('body' => $body, $gitlabID));
    }

    /**
     * Parse Webhook issue.
     * 
     * @param  object    $body 
     * @param  int       $gitlabID 
     * @access public
     * @return object
     */
    public function WebhookParseIssue($body, $gitlabID)
    {
        $object = $this->parseObjectFromLabel($body->labels);
        if(empty($object)) return null;

        $issue = new stdclass;
        $issue->action     = $body->object_attributes->action . $body->object_kind;
        $issue->issue      = $body->object_attributes;
        $issue->objectType = $object->type;
        $issue->objectID   = $object->id;

        if(!is_callable(array($this, "issueTo{$object->type}"))) return false;
        $issue->object = call_user_func_array(array($this, "issueTo{$object->type}"), array('body' => $issue->issue, 'gitlab' => $gitlabID));
        return $issue;
    }

    /**
     * Webhook parse note.
     * 
     * @param  object    $body 
     * @access public
     * @return void
     */
    public function webhookParseNote($body)
    {
        $request = new stdclass;
        $request->type = $body->object_kind;
        if(isset($body->issue)) $body->issue = $this->parseIssue($body->issue);

        $request->labels      = $body->labels;
        $request->project     = $body->project->id;
        $request->labels      = $labels;
        $request->type        = $labelType;
        $request->typeID      = $labelTypeID; 

        $request->project     = $body->project->id;
        $request->title       = $issue->title;
        $request->description = $issue->description;
        $request->action      = $issue->action;
        $request->created_at  = $issue->created_at;
        $request->due_date    = $issue->due_date;
        
        $request->assignees   = $issue->assignee_id;
        $request->url         = $issue->url;
    }

    /**
     * Parse zentao object from labels.
     * 
     * @param  array    $labels 
     * @access public
     * @return object|false
     */
    public function parseObjectFromLabel($labels)
    {
        $object = null;
        foreach($labels as $label) 
        {
            if(preg_match($this->config->gitlab->labelPattern->task, $label->title))
            {
                list($prefix, $id) = explode('/', $label->title);
                $object = new stdclass;
                $object->type = 'task';
                $object->id   = $id;
            }
        }
        return $object;
    }

    /**
     * Parse issue from gitlab.
     * 
     * @param  object    $issue 
     * @access public
     * @return object
     */
    public function parseIssue($issue)
    {
        $object = $this->parseObjectFromLabels($issue->labels);
        if(!$object) return false;
        if($object->type == 'task')  $object->object = $this->issue2Task($issue);
        if($object->type == 'story') $object->object = $this->issue2Story($issue);
        if($object->type == 'bug')   $object->object = $this->issue2Bug($issue);
        return $object;
    }

    public function issueToTask($issue, $gitlabID)
    {
        $task        = new stdclass;
        $maps        = $this->config->gitlab->maps->task;
        $gitlabUsers = $this->getUserAccountIdPairs($gitlabID);

        foreach($maps as $taskField => $config)
        {
            $value = '';
            list($field, $optionType, $options) = explode('|', $config);
            if($optionType == 'field') $value = $issue->$field;
            if($optionType == 'userPairs') $value = zget($gitlabUsers, $issue->$field);
            if($optionType == 'configItems' and isset($issue->$field)) $value = array_search($issue->$field, $this->config->gitlab->$options);
            if($value) $task->$taskField = $value;
        }
        return $task;
    }

    public function issueToStory($issue)
    {
    }

    public function issueToBug($issue)
    {

    }

    /**
     * Get account in zentaopms.
     * 
     * @param  int    $gitlabID 
     * @param  int    $userID 
     * @access public
     * @return string|false
     */
    public function getAccount($gitlabID, $userID)
    {
        return $this->dao->select('account')->from(TABLE_OAUTH)
                    ->where('providerType')->eq('gitlab')
                    ->andWhere('providerID')->eq($gitlabID)
                    ->andWhere('openID')->eq($userID)
                    ->fetch('account');
    }

    public function getGitlabUserID($gitlabID, $account)
    {
        return $this->dao->select('openID')->from(TABLE_OAUTH)
                    ->where('providerType')->eq('gitlab')
                    ->andWhere('providerID')->eq($gitlabID)
                    ->andWhere('account')->eq($account)
                    ->fetch('openID');
    }

    /**
     * Get gitlab issue from relation by object type and id.
     * 
     * @param  string    $objectType 
     * @param  int       $objectID 
     * @access public
     * @return object
     */
    public function getGitlabIssueFromRelation($objectType,$objectID)
    {
        return $this->dao->select('extra as gitlabID,BVersion as projectID,BID as issueID,AType,AID')->from(TABLE_RELATION)
                    ->where('relation')->eq('gitlab')
                    ->andWhere('AType')->eq($objectType)
                    ->andWhere('AID')->eq($objectID)
                    ->fetch();
    }
}

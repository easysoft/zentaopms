<?php
/**
 * 禅道API的project issues资源类
 * 版本V1
 * 目前适用于Gitlab
 *
 * The project issues entry point of zentaopms
 * Version 1
 */
class projectIssuesEntry extends entry
{
    public function get($productID)
    {
        if(!is_numeric($productID)) return $this->sendError(400, 'The project_id is not supported');

        $this->setParam('timeFormat', 'utc');

        $taskFields = 'id,status';
        $taskStatus = array('' => '');
        $taskStatus['opened'] = 'wait,doing,done,pause';
        $taskStatus['closed'] = 'closed';

        $storyFields = 'id,status';
        $storyStatus = array('' => '');
        $storyStatus['opened'] = 'draft,active,changed';
        $storyStatus['closed'] = 'closed';

        $bugFields = 'id,status';
        $bugStatus = array('' => '');
        $bugStatus['opened'] = 'active,resolved';
        $bugStatus['closed'] = 'closed';

        $productID = (int)$productID;
        $status    = $this->param('status', '');
        $label     = $this->param('label', '');
        $search    = $this->param('search', '');
        $page      = intval($this->param('page', 1));
        $limit     = intval($this->param('limit', 20));
        $order     = $this->param('order', 'openedDate_desc');

        $orderParams = explode('_', $order);
        $order       = $orderParams[0];
        $sort        = (isset($orderParams[1]) and strtolower($orderParams[1]) == 'asc') ? 'asc' : 'desc';

        if($status == 'all') $status = '';
        if(!in_array($status, array('opened', 'closed', ''))) return $this->sendError(400, 'The status is not supported');

        switch($order)
        {
        case 'openedDate':
            $taskFields  .= ',openedDate';
            $storyFields .= ',openedDate';
            $bugFields   .= ',openedDate';
            break;
        case 'title':
            $taskFields  .= ',name as title';
            $storyFields .= ',title';
            $bugFields   .= ',title';
            break;
        case 'lastEditedDate':
            $taskFields  .= ",if(lastEditedDate < '1970-01-01 01-01-01', openedDate, lastEditedDate) as lastEditedDate";
            $storyFields .= ",if(lastEditedDate < '1970-01-01 01-01-01', openedDate, lastEditedDate) as lastEditedDate";
            $bugFields   .= ",if(lastEditedDate < '1970-01-01 01-01-01', openedDate, lastEditedDate) as lastEditedDate";
            break;
        default:
            $this->sendError(400, 'The order is not supported');
        }

        $issues = array();

        $executions = $this->dao->select('project')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($productID)->fetchPairs();
        $tasks = $this->dao->select($taskFields)->from(TABLE_TASK)->where('execution')->in(array_values($executions))
                 ->beginIF($search)->andWhere('name')->like("%$search%")->fi()
                 ->beginIF($status)->andWhere('status')->in($taskStatus[$status])->fi()
                 ->andWhere('deleted')->eq(0)
                 ->fetchAll();
        foreach($tasks as $task) $issues[] = array('id' => $task->id, 'type' => 'task', 'order' => $task->$order, 'status' => $this->getKey($task->status, $taskStatus));

        $stories = $this->dao->select($storyFields)->from(TABLE_STORY)->where('product')->eq($productID)
                 ->beginIF($search)->andWhere('title')->like("%$search%")->fi()
                 ->beginIF($status)->andWhere('status')->in($storyStatus[$status])->fi()
                 ->andWhere('deleted')->eq(0)
                 ->fetchAll();
        foreach($stories as $story)
        {
            $issues[] = array('id' => $story->id, 'type' => 'story', 'order' => $story->$order, 'status' => $this->getKey($story->status, $storyStatus)); 
        }

        $bugs = $this->dao->select($bugFields)->from(TABLE_BUG)->where('product')->eq($productID)
                ->beginIF($search)->andWhere('title')->like("%$search%")->fi()
                ->beginIF($status)->andWhere('status')->in($bugStatus[$status])->fi()
                ->andWhere('deleted')->eq(0)
                ->fetchAll();
        foreach($bugs as $bug)
        {
            $issues[] = array('id' => $bug->id, 'type' => 'bug', 'order' => $bug->$order, 'status' => $this->getKey($bug->status, $bugStatus)); 
        }

        array_multisort(array_column($issues, 'order'), $sort == 'asc' ? SORT_ASC : SORT_DESC, $issues);
        $total  = count($issues);
        $issues = $page < 1 ? array() : array_slice($issues, ($page-1) * $limit, $limit);

        $result = $this->processIssues($issues);
        $this->send(200, array('page' => $page, 'total' => $total, 'limit' => $limit, 'issues' => $result));
    }

    /**
     * Process issues, format fields.
     *
     * @param  array  $issues
     * @param  int    $page
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function processIssues($issues)
    {
        $this->app->loadLang('task');
        $this->app->loadLang('story');
        $this->app->loadLang('bug');

        $tasks   = array();
        $stories = array();
        $bugs    = array();
        foreach($issues as $issue)
        {
            if($issue['type'] == 'story') $stories[] = $issue['id'];
            if($issue['type'] == 'task')  $tasks[]   = $issue['id'];
            if($issue['type'] == 'bug')   $bugs[]    = $issue['id'];
        }

        if(!empty($tasks))   $tasks   = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($tasks)->fetchAll('id');
        if(!empty($stories)) $stories = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($stories)->fetchAll('id');
        if(!empty($bugs))    $bugs    = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($bugs)->fetchAll('id');

        $result = array();
        foreach($issues as $issue)
        {
            $r = new stdclass();
            if($issue['type'] == 'task')
            {
                $task = $tasks[$issue['id']];

                $r->id             = 'task-' . $task->id;
                $r->title          = $task->name;
                $r->labels         = array($this->app->lang->task->common, zget($this->app->lang->task->typeList, $task->type));
                $r->pri            = $task->pri;
                $r->assignedTo     = $this->getAssignedUsers('task', $task);
                $r->openedDate     = $task->openedDate;
                $r->openedBy       = $this->getUser($task->openedBy);
                $r->lastEditedDate = $task->lastEditedDate < '1970-01-01 01:01:01' ? $task->openedDate : $task->lastEditedDate;
                $r->lastEditedBy   = $task->lastEditedDate < '1970-01-01 01:01:01' ? $task->openedBy   : $task->lastEditedBy;
                $r->status         = $issue['status'];
                $r->url            = $this->createLink('task', 'view', "taskID=$task->id");
            }
            else if($issue['type'] == 'story')
            {
                $story = $stories[$issue['id']];

                $r->id             = 'story-' . $story->id;
                $r->title          = $story->title;
                $r->labels         = array($this->app->lang->story->common, zget($this->app->lang->story->categoryList, $story->category));
                $r->pri            = $story->pri;
                $r->assignedTo     = $this->getAssignedUsers('story', $story);
                $r->openedDate     = $story->openedDate;
                $r->openedBy       = $this->getUser($story->openedBy);
                $r->lastEditedDate = $story->lastEditedDate < '1970-01-01 01:01:01' ? $story->openedDate : $story->lastEditedDate;
                $r->lastEditedBy   = $story->lastEditedDate < '1970-01-01 01:01:01' ? $story->openedBy   : $story->lastEditedBy;
                $r->status         = $issue['status'];
                $r->url            = $this->createLink('story', 'view', "storyID=$story->id");
            }
            else if($issue['type'] == 'bug')
            {
                $bug = $bugs[$issue['id']];

                $r->id             = 'bug-' . $bug->id;
                $r->title          = $bug->title;
                $r->labels         = array($this->app->lang->bug->common, zget($this->app->lang->bug->typeList, $bug->type));
                $r->pri            = $bug->pri;
                $r->assignedTo     = $this->getAssignedUsers('bug', $bug);
                $r->openedDate     = $bug->openedDate;
                $r->openedBy       = $this->getUser($bug->openedBy);
                $r->lastEditedDate = $bug->lastEditedDate < '1970-01-01 01:01:01' ? $bug->openedDate : $bug->lastEditedDate;
                $r->lastEditedBy   = $bug->lastEditedDate < '1970-01-01 01:01:01' ? $bug->openedBy   : $bug->lastEditedBy;
                $r->status         = $issue['status'];
                $r->url            = $this->createLink('bug', 'view', "bugID=$bug->id");
            }

            $result[] = $this->format($r, 'openedDate:time,lastEditedDate:time');
        }

        return $result;
    }

    /**
     * Get key in array by value.
     *
     * @param  string $value
     * @param  array  $array
     * @access private
     * @return string
     */
    private function getKey($value, $array)
    {
        foreach($array as $key => $values)
        {
            if($values and strpos($values, $value) !== FALSE) return $key;
        }

        return '';
    }

    /**
     * Create url of issue.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $vars
     * @access private
     * @return string
     */
    private function createLink($module, $method, $vars)
    {
        $link = helper::createLink($module, $method, $vars, 'html');
        if($this->config->requestType == 'GET')
        {
            $pos  = strpos($link, '.php');
            $link = '/index' . substr($link, $pos);
        }
        return common::getSysURL() . $link;
    }

    /**
     * Get the detail of the user.
     *
     * @param  string    $account
     * @access public
     * @return object
     */
    public function getUser($account)
    {
        $this->loadModel('user');
        $user = $this->user->getById($account, $feild = 'account');

        $detail = new stdclass;
        $detail->id       = $user->id;
        $detail->account  = $user->account;
        $detail->realname = $user->realname;
        $detail->avatar   = $user->avatar != "" ? common::getSysURL() . $user->avatar : "https://www.gravatar.com/avatar/" . md5($user->account) . "?d=identicon&s=80";
        $detail->url      = $this->createLink('user', 'profile', "userID={$user->id}");

        return $detail;
    }

    /**
     * Get the details of assigned users.
     *
     * @param  string    $objectType
     * @param  object    $object
     * @access public
     * @return object|array
     */
    public function getAssignedUsers($objectType, $object)
    {
        $users = $this->dao->select('account')->from(TABLE_TEAM)
                                              ->where('type')->eq($objectType)
                                              ->andWhere('root')->eq($object->id)
                                              ->fetchAll();

        if($users)
        {
            $details = array();
            foreach($users as $user)
            {
                $details[] = $this->getUser($user->account);
            }
            return $details;
        }

        return ($object->assignedTo == "" or $object->assignedTo == "closed") ? array() : array($this->getUser($object->assignedTo));
    }

    public function getDefaultAvatar()
    {
        return "https://www.gravatar.com/avatar/" . md5(rand(10,113450)) . "?d=identicon&s=80";
    }
}

<?php
/**
 * The productissue entry point of ZenTaoPMS.
 * It is only used by Gitlab.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class productIssuesEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function get($productID)
    {
        if(!is_numeric($productID)) $this->sendError(400, 'The product_id is not supported');

        $taskFields = 'id,status';
        $taskStatus = array();
        $taskStatus['opened'] = 'wait,doing,done,pause';
        $taskStatus['closed'] = 'closed';

        $storyFields = 'id,status';
        $storyStatus = array();
        $storyStatus['opened'] = 'draft,reviewing,active,changing';
        $storyStatus['closed'] = 'closed';

        $bugFields = 'id,status';
        $bugStatus = array();
        $bugStatus['opened'] = 'active,resolved';
        $bugStatus['closed'] = 'closed';

        $productID = (int)$productID;
        $status    = $this->param('status', '');
        $search    = $this->param('search', '');
        $page      = intval($this->param('page', 1));
        $limit     = intval($this->param('limit', 20));
        $order     = $this->param('order', 'openedDate_desc');

        $labels = $this->param('labels', '');
        $labels = $labels ? explode(',', $labels) : array();

        $orderParams = explode('_', $order);
        $order       = $orderParams[0];
        $sort        = (isset($orderParams[1]) and strtolower($orderParams[1]) == 'asc') ? 'asc' : 'desc';

        if($status == 'all') $status = '';
        if(!in_array($status, array('opened', 'closed', ''))) $this->sendError(400, 'The status is not supported');

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

        $issues      = array();
        $storyFilter = array();
        $bugFilter   = array();
        $taskFilter  = array();
        $labelTypes  = array();

        if(!empty($labels))
        {
            $this->app->loadLang('story');
            $this->app->loadLang('task');
            $this->app->loadLang('bug');

            $storyTypeMap = array_flip($this->app->lang->story->categoryList);
            $taskTypeMap  = array_flip($this->app->lang->task->typeList);
            $bugTypeMap   = array_flip($this->app->lang->bug->typeList);

            $allValidLabels = array_merge(array_keys(array_merge($storyTypeMap, $taskTypeMap, $bugTypeMap)), array($this->app->lang->story->common, $this->app->lang->task->common, $this->app->lang->bug->common));
            foreach($labels as $label)
            {
                /* Return empty result if label is not exists.*/
                if(!in_array($label, $allValidLabels)) $this->send(200, array('page' => $page, 'total' => 0, 'limit' => $limit, 'issues' => array()));

                if($label == $this->app->lang->story->common) $storyFilter[] = 'all';
                if(isset($storyTypeMap[$label])) $storyFilter[] = $storyTypeMap[$label];

                if($label == $this->app->lang->task->common) $taskFilter[] = 'all';
                if(isset($taskTypeMap[$label])) $taskFilter[] = $taskTypeMap[$label];

                if($label == $this->app->lang->bug->common) $bugFilter[] = 'all';
                if(isset($bugTypeMap[$label])) $bugFilter[] = $bugTypeMap[$label];
            }
        }

        if(!empty($storyFilter)) $labelTypes[] = 'story';
        if(!empty($taskFilter))  $labelTypes[] = 'task';
        if(!empty($bugFilter))   $labelTypes[] = 'bug';

        /* If posted labels are not conflictive. */
        if(count($labelTypes) < 2)
        {
            $storyFilter = array_unique($storyFilter);
            $taskFilter  = array_unique($taskFilter);
            $bugFilter   = array_unique($bugFilter);

            $executions = $this->dao->select('project')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($productID)->fetchPairs();

            /* Get tasks. */
            if(empty($labelTypes) or in_array('task', $labelTypes))
            {
                $query = $this->dao->select($taskFields)->from(TABLE_TASK)->where('execution')->in(array_values($executions))
                    ->beginIF($search)->andWhere('name')->like("%$search%")->fi()
                    ->beginIF($status)->andWhere('status')->in($taskStatus[$status])->fi()
                    ->andWhere('deleted')->eq(0);
                foreach($taskFilter as $filter) if($filter != 'all') $query->andWhere('type')->eq($filter);
                $tasks = $query->fetchAll();
                foreach($tasks as $task) $issues[] = array('id' => $task->id, 'type' => 'task', 'order' => $task->$order, 'status' => $this->getKey($task->status, $taskStatus));
            }

            /* Get stories. */
            if(empty($labelTypes) or in_array('story', $labelTypes))
            {
                $query = $this->dao->select($storyFields)->from(TABLE_STORY)
                    ->where('product')->eq($productID)
                    ->beginIF($search)->andWhere('title')->like("%$search%")->fi()
                    ->beginIF($status)->andWhere('status')->in($storyStatus[$status])->fi()
                    ->andWhere('deleted')->eq(0);
                foreach($storyFilter as $filter) if($filter != 'all') $query->andWhere('category')->eq($filter);
                $stories = $query->fetchAll();
                foreach($stories as $story) $issues[] = array('id' => $story->id, 'type' => 'story', 'order' => $story->$order, 'status' => $this->getKey($story->status, $storyStatus));
            }

            /* Get bugs. */
            if(empty($labelTypes) or in_array('bug', $labelTypes))
            {
                $query = $this->dao->select($bugFields)->from(TABLE_BUG)
                    ->where('product')->eq($productID)
                    ->beginIF($search)->andWhere('title')->like("%$search%")->fi()
                    ->beginIF($status)->andWhere('status')->in($bugStatus[$status])->fi()
                    ->andWhere('deleted')->eq(0);
                foreach($bugFilter as $filter) if($filter != 'all') $query->andWhere('type')->eq($filter);
                $bugs = $query->fetchAll();
                foreach($bugs as $bug) $issues[] = array('id' => $bug->id, 'type' => 'bug', 'order' => $bug->$order, 'status' => $this->getKey($bug->status, $bugStatus));
            }
        }

        array_multisort(array_column($issues, 'order'), $sort == 'asc' ? SORT_ASC : SORT_DESC, $issues);
        $total  = count($issues);
        $issues = $page < 1 ? array() : array_slice($issues, ($page-1) * $limit, $limit);

        $result = $this->processIssues($issues);
        return $this->send(200, array('page' => $page, 'total' => $total, 'limit' => $limit, 'issues' => $result));
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
        $this->app->loadLang('story');
        $this->app->loadLang('task');
        $this->app->loadLang('bug');

        $this->loadModel('entry');

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
                $r->openedDate     = $task->openedDate;
                $r->openedBy       = $task->openedBy;
                $r->lastEditedDate = $task->lastEditedDate < '1970-01-01 01:01:01' ? $task->openedDate : $task->lastEditedDate;
                $r->lastEditedBy   = $task->lastEditedDate < '1970-01-01 01:01:01' ? $task->openedBy   : $task->lastEditedBy;
                $r->status         = $issue['status'];
                $r->url            = helper::createLink('task', 'view', "taskID=$task->id");
                $r->assignedTo     = array();

                /* Get assignees for task, the task object has the type of multiple assign only so far. */
                $users = $this->dao->select('account')->from(TABLE_TEAM)
                    ->where('type')->eq('task')
                    ->andWhere('root')->eq($task->id)
                    ->fetchAll();
                if($users)
                {
                    foreach($users as $user)
                    {
                        $r->assignedTo[] = $user->account;
                    }
                }
                else
                {
                    if($task->assignedTo == "")
                    {
                        $r->assignedTo = array();
                    }
                    else
                    {
                        $r->assignedTo = array($task->assignedTo);
                    }
                }
            }
            elseif($issue['type'] == 'story')
            {
                $story = $stories[$issue['id']];

                $r->id             = 'story-' . $story->id;
                $r->title          = $story->title;
                $r->labels         = array($this->app->lang->story->common, zget($this->app->lang->story->categoryList, $story->category));
                $r->pri            = $story->pri;
                $r->openedDate     = $story->openedDate;
                $r->openedBy       = $story->openedBy;
                $r->lastEditedDate = $story->lastEditedDate < '1970-01-01 01:01:01' ? $story->openedDate : $story->lastEditedDate;
                $r->lastEditedBy   = $story->lastEditedDate < '1970-01-01 01:01:01' ? $story->openedBy   : $story->lastEditedBy;
                $r->status         = $issue['status'];
                $r->url            = helper::createLink('story', 'view', "storyID=$story->id");

                if($story->assignedTo == "")
                {
                    $r->assignedTo = array();
                }
                else
                {
                    $r->assignedTo = array($story->assignedTo);
                }
            }
            elseif($issue['type'] == 'bug')
            {
                $bug = $bugs[$issue['id']];

                $r->id             = 'bug-' . $bug->id;
                $r->title          = $bug->title;
                $r->labels         = array($this->app->lang->bug->common, zget($this->app->lang->bug->typeList, $bug->type));
                $r->pri            = $bug->pri;
                $r->openedDate     = $bug->openedDate;
                $r->openedBy       = $bug->openedBy;
                $r->lastEditedDate = $bug->lastEditedDate < '1970-01-01 01:01:01' ? $bug->openedDate : $bug->lastEditedDate;
                $r->lastEditedBy   = $bug->lastEditedDate < '1970-01-01 01:01:01' ? $bug->openedBy   : $bug->lastEditedBy;
                $r->status         = $issue['status'];
                $r->url            = helper::createLink('bug', 'view', "bugID=$bug->id");

                if($bug->assignedTo == "")
                {
                    $r->assignedTo = array();
                }
                else
                {
                    $r->assignedTo = array($bug->assignedTo);
                }
            }

            $result[] = $this->format($r, 'openedDate:time,lastEditedDate:time');
        }

        /**
         * Get all users in issues so that we can bulk get user detail later.
         *
         */
        $userList = array();
        foreach($result as $issue)
        {
            foreach($issue->assignedTo as $account)
            {
                $userList[] = $account;
            }
            $userList[] = $issue->openedBy;
        }
        $userList    = array_unique($userList);
        $userDetails = $this->loadModel('user')->getUserDetailsForAPI($userList);

        /**
         * Set the user detail to assignedTo and openedBy.
         *
         */
        foreach($result as $issue)
        {
            foreach($issue->assignedTo as $key => $account)
            {
                if($account == 'closed')
                {
                    $issue->assignedTo = array();
                    break;
                }
                $issue->assignedTo[$key] = $userDetails[$account];
            }
            $issue->openedBy = $userDetails[$issue->openedBy];
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
}

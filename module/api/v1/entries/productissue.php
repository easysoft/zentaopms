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
class productIssueEntry extends entry
{
    /**
     * GET method.
     *
     * @param  string $issueID, such as task-1, story-1, bug-1
     * @access public
     * @return string
     */
    public function get($issueID)
    {
        $this->loadModel('entry');

        $idParams = explode('-', $issueID);
        if(count($idParams) < 2) $this->sendError(400, 'The id of issue is wrong.');

        $type = $idParams[0];
        $id   = intval($idParams[1]);

        $issue = new stdclass();
        switch($type)
        {
            case 'story':
                $this->app->loadLang('story');
                $storyStatus = array('' => '', 'draft' => 'opened', 'reviewing' => 'opened', 'active' => 'opened', 'changing' => 'opened', 'closed' => 'closed');

                $story = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($id)->fetch();
                if(!$story) $this->send404();

                $issue->id             = $issueID;
                $issue->title          = $story->title;
                $issue->labels         = array($this->app->lang->story->common, zget($this->app->lang->story->categoryList, $story->category));
                $issue->pri            = $story->pri;
                $issue->openedDate     = $story->openedDate;
                $issue->openedBy       = $story->openedBy;
                $issue->lastEditedDate = $story->lastEditedDate < '1970-01-01 01:01:01' ? $story->openedDate : $story->lastEditedDate;
                $issue->lastEditedBy   = $story->lastEditedDate < '1970-01-01 01:01:01' ? $story->openedBy   : $story->lastEditedBy;
                $issue->status         = $storyStatus[$story->status];
                $issue->url            = helper::createLink('story', 'view', "storyID=$id");

                $storySpec   = $this->dao->select('*')->from(TABLE_STORYSPEC)->where('story')->eq($id)->andWhere('version')->eq($story->version)->fetch();
                $issue->desc = $storySpec->spec;

                $issue->assignedTo = $story->assignedTo == "" ? array() : array($story->assignedTo);

                break;

            case 'bug':
                $this->app->loadLang('bug');
                $bugStatus = array('' => '', 'active' => 'opened', 'resolved' => 'opened', 'closed' => 'closed');

                $bug = $this->dao->select('*')->from(TABLE_BUG)->where('id')->eq($id)->fetch();
                if(!$bug) $this->send404();

                $issue->id             = $issueID;
                $issue->title          = $bug->title;
                $issue->labels         = array($this->app->lang->bug->common, zget($this->app->lang->bug->typeList, $bug->type));
                $issue->pri            = $bug->pri;
                $issue->openedDate     = $bug->openedDate;
                $issue->openedBy       = $bug->openedBy;
                $issue->lastEditedDate = helper::isZeroDate($bug->lastEditedDate) ? $bug->openedDate : $bug->lastEditedDate;
                $issue->lastEditedBy   = helper::isZeroDate($bug->lastEditedDate) ? $bug->openedBy   : $bug->lastEditedBy;
                $issue->status         = $bugStatus[$bug->status];
                $issue->url            = helper::createLink('bug', 'view', "bugID=$id");
                $issue->desc           = $bug->steps;

                $issue->assignedTo = $bug->assignedTo == "" ? array() : array($bug->assignedTo);
                break;

            case 'task':
                $this->app->loadLang('task');
                $taskStatus = array('' => '', 'wait' => 'opened', 'doing' => 'opened', 'done' => 'opened', 'pause' => 'opened', 'cancel' => 'opened', 'closed' => 'closed');

                $task = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($id)->fetch();
                if(!$task) $this->send404();

                $issue->id             = $issueID;
                $issue->title          = $task->name;
                $issue->labels         = array($this->app->lang->task->common, zget($this->app->lang->task->typeList, $task->type));
                $issue->pri            = $task->pri;
                $issue->openedDate     = $task->openedDate;
                $issue->openedBy       = $task->openedBy;
                $issue->lastEditedDate = $task->lastEditedDate < '1970-01-01 01:01:01' ? $task->openedDate : $task->lastEditedDate;
                $issue->lastEditedBy   = $task->lastEditedDate < '1970-01-01 01:01:01' ? $task->openedBy   : $task->lastEditedBy;
                $issue->status         = $taskStatus[$task->status];
                $issue->url            = helper::createLink('task', 'view', "taskID=$id");
                $issue->desc           = $task->desc;

                /* Get assignees for task, the task object has the type of multiple assign only so far. */
                $users = $this->dao->select('account')->from(TABLE_TEAM)
                    ->where('type')->eq('task')
                    ->andWhere('root')->eq($task->id)
                    ->fetchAll();
                if($users)
                {
                    foreach($users as $user) $issue->assignedTo[] = $user->account;
                }
                else
                {
                    $issue->assignedTo = $task->assignedTo == "" ? array() : array($task->assignedTo);
                }

                break;

            default:
                $this->send404();
        }

        $actions = $this->loadModel('action')->getList($type, $id);

        $issue->comments = array_values($this->processActions($type, $actions));

        /* Get all users in issues so that we can get user detail later in batch. */
        $accountList = array();
        foreach($issue->assignedTo as $account) $accountList[] = $account;
        $accountList[]  = $issue->openedBy;
        $accountList    = array_unique($accountList);
        $profileList = $this->loadModel('user')->getUserDetailsForAPI($accountList);

        /* Set the user detail to assignedTo and openedBy. */
        foreach($issue->assignedTo as $key => $account)
        {
            if($account == 'closed')
            {
                $issue->assignedTo = array();
                break;
            }

            $issue->assignedTo[$key] = $profileList[$account];
        }
        $issue->openedBy = $profileList[$issue->openedBy];

        return $this->send(200, array('issue' => $this->format($issue, 'openedDate:time,lastEditedDate:time')));
    }

    /**
     * Process actions of one issue.
     *
     * @param  string    $type    bug|task|story
     * @param  array     $actions
     * @access public
     * @return array
     */
    public function processActions($type, $actions)
    {
        $accountList = array();
        foreach($actions as $action)
        {
            $accountList[] = $action->actor;
            ob_start();
            if(method_exists($this->action, "printActionForGitLab"))
            {
                $this->action->printActionForGitLab($action);
            }
            else
            {
                $this->action->printAction($action);
            }
            $action->title = ob_get_contents();
            ob_clean();

            $action->body_html = '';

            if(!empty($action->history))
            {
                ob_start();
                $this->action->printChanges($action->objectType, $action->history);
                $action->body_html = ob_get_contents();
                ob_clean();
            }

            if(!empty($action->comment))
            {
                $comment = strip_tags($action->comment) == $action->comment ? nl2br($action->comment) : $action->comment;
                $action->body_html = "{$comment}";
            }
        }

        /* Format user detail and date. */
        $accountList = array_unique($accountList);
        $profileList = $this->loadModel('user')->getUserDetailsForAPI($accountList);
        foreach($actions as $key => $action)
        {
            $action->actor = isset($profileList[$action->actor]) ? $profileList[$action->actor] : array();
            $action->date  = gmdate("Y-m-d\TH:i:s\Z", strtotime($action->date));

            /* Unset this action when actor is System. */
            if(empty($action->actor)) unset($actions[$key]);
        }

        return $actions;
    }
}

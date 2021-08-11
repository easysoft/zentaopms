<?php
/**
 * 禅道API的product issue资源类
 * 版本V1
 * 目前适用于Gitlab
 *
 * The product issue entry point of zentaopms
 * Version 1
 */
class productIssueEntry extends entry
{
    public function get($issueID)
    {
        $this->loadModel('entry');

        $idParams = explode('-', $issueID);
        if(count($idParams) < 2) $this->sendError(400, 'The id of issue is wrong.');

        $type = $idParams[0];
        $id   = intval($idParams[1]);

        $issue   = new stdclass();
        switch($type)
        {
        case 'story':
            $this->app->loadLang('story');
            $storyStatus = array('' => '', 'draft' => 'opened', 'active' => 'opened', 'changed' => 'opened', 'closed' => 'closed');

            $story = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($id)->fetch();
            if(!$story) $this->send404();

            $issue->id             = $issueID;
            $issue->title          = $story->title;
            $issue->labels         = array($this->app->lang->story->common, zget($this->app->lang->story->categoryList, $story->category));
            $issue->pri            = $story->pri;
            $issue->assignedTo     = $this->entry->getAssignees('story', $story);
            $issue->openedDate     = $story->openedDate;
            $issue->openedBy       = $this->entry->getUser($story->openedBy);
            $issue->lastEditedDate = $story->lastEditedDate < '1970-01-01 01:01:01' ? $story->openedDate : $story->lastEditedDate;
            $issue->lastEditedBy   = $story->lastEditedDate < '1970-01-01 01:01:01' ? $story->openedBy   : $story->lastEditedBy;
            $issue->status         = $storyStatus[$story->status];
            $issue->url            = helper::createLink('story', 'view', "storyID=$id");

            $storySpec   = $this->dao->select('*')->from(TABLE_STORYSPEC)->where('story')->eq($id)->andWhere('version')->eq($story->version)->fetch();
            $issue->desc = $storySpec->spec;
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
            $issue->assignedTo     = $this->entry->getAssignees('bug', $bug);
            $issue->openedDate     = $bug->openedDate;
            $issue->openedBy       = $this->entry->getUser($bug->openedBy);
            $issue->lastEditedDate = $bug->lastEditedDate < '1970-01-01 01:01:01' ? $bug->openedDate : $bug->lastEditedDate;
            $issue->lastEditedBy   = $bug->lastEditedDate < '1970-01-01 01:01:01' ? $bug->openedBy   : $bug->lastEditedBy;
            $issue->status         = $bugStatus[$bug->status];
            $issue->url            = helper::createLink('bug', 'view', "bugID=$id");
            $issue->desc           = $bug->steps;
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
            $issue->assignedTo     = $this->entry->getAssignees('task', $task);
            $issue->openedDate     = $task->openedDate;
            $issue->openedBy       = $this->entry->getUser($task->openedBy);
            $issue->lastEditedDate = $task->lastEditedDate < '1970-01-01 01:01:01' ? $task->openedDate : $task->lastEditedDate;
            $issue->lastEditedBy   = $task->lastEditedDate < '1970-01-01 01:01:01' ? $task->openedBy   : $task->lastEditedBy;
            $issue->status         = $taskStatus[$task->status];
            $issue->url            = helper::createLink('task', 'view', "taskID=$id");
            $issue->desc           = $task->desc;

            break;
        default:
            $this->send404();
        }

        $actions = $this->loadModel('action')->getList($type, $issueID);

        $this->send(200, array('issue' => $this->format($issue, 'openedDate:time,lastEditedDate:time')));
    }
}

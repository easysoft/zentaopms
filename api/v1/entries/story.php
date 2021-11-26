<?php
/**
 * The story entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class storyEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function get($storyID)
    {
        $control = $this->loadController('story', 'view');
        $control->view($storyID);

        $data = $this->getData();

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return isset($data->code) and $data->code == 404 ? $this->send404() : $this->sendError(400, $data->message);

        $story = $data->data->story;

        if(!empty($story->children)) $story->children  = array_values((array)$story->children);
        if(isset($story->planTitle)) $story->planTitle = array_values((array)$story->planTitle);
        if($story->parent > 0) $story->parentPri = $this->dao->select('pri')->from(TABLE_STORY)->where('id')->eq($story->parent)->fetch('pri');

        /* Set product name */
        $story->productName = $data->data->product->name;

        /* Set module title */
        $moduleTitle = '';
        if(empty($story->module)) $moduleTitle = '/';
        if($story->module)
        {
            $modulePath = $data->data->modulePath;
            foreach($modulePath as $key => $module)
            {
                $moduleTitle .= $module->name;
                if(isset($modulePath[$key + 1])) $moduleTitle .= '/';
            }
        }
        $story->moduleTitle = $moduleTitle;

        /* Format user for api. */
        if($story->assignedTo)   $story->assignedTo   = $this->formatUser($story->assignedTo, $data->data->users);
        if($story->closedBy)     $story->closedBy     = $this->formatUser($story->closedBy, $data->data->users);
        if($story->lastEditedBy) $story->lastEditedBy = $this->formatUser($story->lastEditedBy, $data->data->users);
        if($story->openedBy)
        {
            $usersWithAvatar = $this->loadModel('user')->getListByAccounts(array($story->openedBy), 'account');
            $story->openedBy = zget($usersWithAvatar, $story->openedBy);
        }

        $mailto = array();
        if($story->mailto)
        {
            foreach(explode(',', $story->mailto) as $account)
            {
                if(empty($account)) continue;
                $mailto[] = $this->formatUser($account, $data->data->users);
            }
        }
        $story->mailto = $mailto;

        $reviewedBy = array();
        if($story->reviewedBy)
        {
            foreach(explode(',', $story->reviewedBy) as $account)
            {
                if(empty($account)) continue;
                $reviewedBy[] = $this->formatUser($account, $data->data->users);
            }
        }
        $story->reviewedBy = $reviewedBy;

        $storyTasks = array();
        foreach($story->tasks as $executionTasks)
        {
            foreach($executionTasks as $task)
            {
                if(!isset($data->data->executions->{$task->execution})) continue;
                $storyTasks[] = $this->filterFields($task, 'id,name');
            }
        }
        $story->tasks = $storyTasks;

        $story->bugs = array();
        foreach($data->data->bugs as $bug) $story->bugs[] = $this->filterFields($bug, 'id,title');

        $story->cases = array();
        foreach($data->data->cases as $case) $story->cases[] = $this->filterFields($case, 'id,title');

        $story->requirements = array();
        foreach($data->data->relations as $relation) $story->requirements[] = $this->filterFields($relation, 'id,title');

        $story->actions = $this->loadModel('action')->processActionForAPI($data->data->actions, $data->data->users, $this->lang->story);

        $this->send(200, $this->format($story, 'openedDate:time,assignedDate:time,reviewedDate:time,lastEditedDate:time,closedDate:time'));
    }

    /**
     * PUT method.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function put($storyID)
    {
        $oldStory = $this->loadModel('story')->getByID($storyID);

        /* Set $_POST variables. */
        $fields = 'type';
        $this->batchSetPost($fields, $oldStory);
        $this->setPost('parent', 0);

        $control = $this->loadController('story', 'edit');
        $control->edit($storyID);

        $this->getData();
        $story = $this->story->getByID($storyID);
        $this->sendSuccess(200, $this->format($story, 'openedDate:time,assignedDate:time,reviewedDate:time,lastEditedDate:time,closedDate:time,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function delete($storyID)
    {
        $control = $this->loadController('story', 'delete');
        $control->delete($storyID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}

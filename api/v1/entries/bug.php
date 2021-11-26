<?php
/**
 * The bug entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class bugEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function get($bugID)
    {
        $control = $this->loadController('bug', 'view');
        $control->view($bugID);

        $data = $this->getData();

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return isset($data->code) and $data->code == 404 ? $this->send404() : $this->sendError(400, $data->message);

        $bug = $data->data->bug;

        /* Set product name */
        $bug->productName = $data->data->product->name;

        /* Set module title */
        $moduleTitle = '';
        if(empty($bug->module)) $moduleTitle = '/';
        if($bug->module)
        {
            $modulePath = $data->data->modulePath;
            foreach($modulePath as $key => $module)
            {
                $moduleTitle .= $module->name;
                if(isset($modulePath[$key + 1])) $moduleTitle .= '/';
            }
        }
        $bug->moduleTitle = $moduleTitle;

        if($bug->openedBy)     $bug->openedBy     = $this->formatUser($bug->openedBy,     $data->data->users);
        if($bug->resolvedBy)   $bug->resolvedBy   = $this->formatUser($bug->resolvedBy,   $data->data->users);
        if($bug->closedBy)     $bug->closedBy     = $this->formatUser($bug->closedBy,     $data->data->users);
        if($bug->lastEditedBy) $bug->lastEditedBy = $this->formatUser($bug->lastEditedBy, $data->data->users);
        if($bug->assignedTo)
        {
            $usersWithAvatar = $this->loadModel('user')->getListByAccounts(array($bug->assignedTo), 'account');
            $bug->assignedTo = zget($usersWithAvatar, $bug->assignedTo);
        }

        $mailto = array();
        if($bug->mailto)
        {
            foreach(explode(',', $bug->mailto) as $account)
            {
                if(empty($account)) continue;
                $mailto[] = $this->formatUser($account, $data->data->users);
            }
        }
        $bug->mailto = $mailto;

        $openedBuilds = array();
        foreach(explode(',', $bug->openedBuild) as $buildID)
        {
            if(empty($buildID)) continue;

            $openedBuild        = new stdclass();
            $openedBuild->id    = $buildID;
            $openedBuild->title = zget($data->data->builds, $buildID, '');

            $openedBuilds[] = $openedBuild;
        }
        $bug->openedBuild = $openedBuilds;

        if($bug->resolvedBuild)
        {
            $resolvedBuild = new stdclass();
            $resolvedBuild->id    = $bug->resolvedBuild;
            $resolvedBuild->title = zget($data->data->builds, $bug->resolvedBuild, '');
            $bug->resolvedBuild   = $resolvedBuild;
        }

        $bug->actions = $this->loadModel('action')->processActionForAPI($data->data->actions, $data->data->users, $this->lang->bug);

        $this->send(200, $this->format($bug, 'activatedDate:time,openedDate:time,assignedDate:time,resolvedDate:time,closedDate:time,lastEditedDate:time,deadline:date,deleted:bool'));
    }

    /**
     * PUT method.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function put($bugID)
    {
        $oldBug = $this->loadModel('bug')->getByID($bugID);

        /* Set $_POST variables. */
        $fields = 'title,project,execution,openedBuild,assignedTo,pri,severity,type,story,resolvedBy,closedBy,resolution,product,plan,task';
        $this->batchSetPost($fields, $oldBug);

        $control = $this->loadController('bug', 'edit');
        $control->edit($bugID);

        $data = $this->getData();

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->status)) return $this->sendError(400, 'error');

        $bug = $this->bug->getByID($bugID);
        $this->send(200, $this->format($bug, 'activatedDate:time,openedDate:time,assignedDate:time,resolvedDate:time,closedDate:time,lastEditedDate:time,deadline:date,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function delete($bugID)
    {
        $control = $this->loadController('bug', 'delete');
        $control->delete($bugID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}

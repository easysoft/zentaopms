<?php
/**
 * The bug entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class bugEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $bugID
     * @access public
     * @return string
     */
    public function get($bugID)
    {
        $this->resetOpenApp($this->param('tab', 'product'));

        $control = $this->loadController('bug', 'view');
        $control->view($bugID);

        $data = $this->getData();

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $bug = $data->data->bug;

        /* Set product name and status */
        $bug->productName   = $data->data->product->name;
        $bug->productStatus = $data->data->product->status;

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

        $preAndNext = $data->data->preAndNext;
        $bug->preAndNext = array();
        $bug->preAndNext['pre']  = $preAndNext->pre  ? $preAndNext->pre->id : '';
        $bug->preAndNext['next'] = $preAndNext->next ? $preAndNext->next->id : '';

        return $this->send(200, $this->format($bug, 'activatedDate:time,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,mailto:userList,resolvedBy:user,resolvedDate:time,closedBy:user,closedDate:time,lastEditedBy:user,lastEditedDate:time,deadline:date,deleted:bool'));
    }

    /**
     * PUT method.
     *
     * @param  int    $bugID
     * @access public
     * @return string
     */
    public function put($bugID)
    {
        $control = $this->loadController('bug', 'edit');

        $oldBug = $this->loadModel('bug')->getByID($bugID);

        /* Set $_POST variables. */
        $fields = 'uid,title,project,execution,openedBuild,assignedTo,pri,severity,type,story,resolvedBy,resolvedBuild,resolvedDate,closedBy,resolution,product,plan,task,module,steps,mailto,keywords,deadline,os,browser,feedbackBy';
        $this->batchSetPost($fields, $oldBug);
        $this->setPost('notifyEmail', implode(',', $this->request('notifyEmail', array())));

        $control->edit($bugID);

        $data = $this->getData();

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->status)) return $this->sendError(400, 'error');

        $bug = $this->bug->getByID($bugID);
        return $this->send(200, $this->format($bug, 'activatedDate:time,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,mailto:userList,resolvedBy:user,resolvedDate:time,closedBy:user,closedDate:time,lastEditedBy:user,lastEditedDate:time,deadline:date,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $bugID
     * @access public
     * @return string
     */
    public function delete($bugID)
    {
        $control = $this->loadController('bug', 'delete');
        if(!$this->app->user->admin)
        {
            $bug = $this->loadModel('bug')->getByID($bugID);
            $projects = explode(',', "0,{$this->app->user->view->projects}");
            if(!in_array($bug->project, $projects)) return $this->sendError(400, 'No access to the project that the bug belongs to');
        }

        $control->delete($bugID, 'yes');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}

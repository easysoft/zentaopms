<?php
/**
 * The executions entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class feedbacksEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function get()
    {
        a('yes');die;
        $control = $this->loadController('feedback', 'admin');
        $control->admin();
        $data = $this->getData();
        a($data);die;

        if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $executions = $data->data->executionStats;
        $pager      = $data->data->pager;
        $projects   = $data->data->projects;

        $data = array();
        $data['page']       = $pager->pageID;
        $data['total']      = $pager->recTotal;
        $data['limit']      = $pager->recPerPage;
        $data['executions'] = $result;
        if(!empty($withProject)) $data['projects'] = $projects;

        return $this->send(200, $data);
    }

    /**
     * POST method.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function post($projectID = 0)
    {
        $fields = 'project,code,name,begin,end,lifetime,desc,days';
        $this->batchSetPost($fields);

        $projectID = $this->param('project', $projectID);
        $this->setPost('project',   $projectID);
        $this->setPost('acl',       $this->request('acl', 'private'));
        $this->setPost('PO',        $this->request('PO', ''));
        $this->setPost('PM',        $this->request('PM', ''));
        $this->setPost('QD',        $this->request('QD', ''));
        $this->setPost('RD',        $this->request('RD', ''));
        $this->setPost('whitelist', $this->request('whitelist', array()));
        $this->setPost('products',  $this->request('products', array()));
        $this->setPost('plans',     $this->request('plans', array()));

        $control = $this->loadController('execution', 'create'); $this->requireFields('name,code,begin,end,days');
        $control->create($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $execution = $this->loadModel('execution')->getByID($data->id);

        $this->send(201, $this->format($execution, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,PM:user,PO:user,RD:user,QD:user,whitelist:userList,begin:date,end:date,realBegan:date,realEnd:date,deleted:bool'));
    }

    /**
     * Get drop menu.
     *
     * @access public
     * @return void
     */
    public function getDropMenu()
    {
        $control = $this->loadController('execution', 'ajaxGetDropMenu');
        $control->ajaxGetDropMenu($this->request('executionID', 0), $this->request('module', 'execution'), $this->request('method', 'task'), $this->request('extra', ''));

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $account  = $this->app->user->account;
        $projects = $data->data->projects;
        $dropMenu = array('involved' => array(), 'other' => array(), 'closed' => array());
        foreach($data->data->executions as $projectID => $projectExecutions)
        {
            foreach($projectExecutions as $execution)
            {
                if(helper::diffDate(date('Y-m-d'), $execution->end) > 0) $execution->delay = true;
                $teams     = $execution->teams;
                $execution = $this->filterFields($execution, 'id,project,model,type,name,code,status,PM,delay');

                $projectName = zget($projects, $execution->project, '');
                if($projectName) $execution->name = $projectName . '/' . $execution->name;

                if($execution->status == 'closed')
                {
                    $dropMenu['closed'][] = $execution;
                }
                elseif($execution->status != 'done' and $execution->status != 'closed' and ($execution->PM == $account or isset($teams->$account)))
                {
                    $dropMenu['involved'][] = $execution;
                }
                else
                {
                    $dropMenu['other'][] = $execution;
                }
            }
        }

        $this->send(200, $dropMenu);
    }
}

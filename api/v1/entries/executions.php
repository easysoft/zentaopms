<?php
/**
 * The executions entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class executionsEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function get($projectID = 0)
    {
        $appendFields = $this->param('fields', '');
        $withProject  = $this->param('withProject', '');
        if(strpos(strtolower(",{$appendFields},"), ',dropmenu,') !== false) return $this->getDropMenu();

        if($projectID)
        {
            $control = $this->loadController('project', 'execution');
            $control->execution($this->param('status', 'undone'), $projectID, $this->param('order', 'id_desc'), $this->param('product', 0), 0, $this->param('limit', 20), $this->param('page', 1));

            /* Response */
            $data = $this->getData();
            if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
            if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

            $executions = $data->data->executionStats;
            $pager      = $data->data->pager;
            $projects   = $data->data->projects;
        }
        else
        {
            $control = $this->loadController('execution', 'all');
            $control->all($this->param('status', 'all'), $this->param('order', 'id_desc'), 0, '', 0, $this->param('limit', 20), $this->param('page', 1));
            $data = $this->getData();

            if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
            if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

            $executions = $data->data->executionStats;
            $pager      = $data->data->pager;
            $projects   = $data->data->projects;
        }

        $result = array();
        foreach($data->data->executionStats as $execution)
        {
            $execution = $this->filterFields($execution, 'id,name,project,code,type,parent,begin,end,status,openedBy,openedDate,delay,progress,children,' . $appendFields);
            $result[]  = $this->format($execution, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,PM:user,PO:user,RD:user,QD:user,whitelist:userList,begin:date,end:date,realBegan:date,realEnd:date,deleted:bool');
        }

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
     * @return string
     */
    public function post($projectID = 0)
    {
        $useCode = $this->checkCodeUsed();

        $fields = 'project,name,begin,end,lifetime,desc,days,percent,parent';
        if($useCode) $fields .= 'code';

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

        $control = $this->loadController('execution', 'create');

        $requireFields = 'name,begin,end';
        if($useCode) $requireFields .= ',code';
        $this->requireFields($requireFields);

        $control->create($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $execution = $this->loadModel('execution')->getByID($data->id);

        return $this->send(201, $this->format($execution, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,PM:user,PO:user,RD:user,QD:user,whitelist:userList,begin:date,end:date,realBegan:date,realEnd:date,deleted:bool'));
    }

    /**
     * Get drop menu.
     *
     * @access public
     * @return string
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

        return $this->send(200, $dropMenu);
    }
}

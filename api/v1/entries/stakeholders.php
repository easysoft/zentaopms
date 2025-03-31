<?php
/**
 * The stakeholder entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class stakeholdersEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $programID
     * @access public
     * @return string
     */
    public function get($programID = 0)
    {
        if(!$programID) $programID = $this->param('program', 0);

        if($programID)
        {
            $control = $this->loadController('program', 'stakeholder');
            $control->stakeholder($programID, $this->param('order', 't1.id_desc'), 0, $this->param('limit', 20), $this->param('page', 1));
            $data = $this->getData();
        }

        if(isset($data->status) and $data->status == 'success')
        {
            $this->app->loadLang('stakeholder');
            $pager  = $data->data->pager;
            $users  = $data->data->users;
            $result = array();
            foreach($data->data->stakeholders as $stakeholder)
            {
                $stakeholder->roleName = zget($this->lang->user->roleList, $stakeholder->role, '');
                $stakeholder->typeName = zget($this->lang->stakeholder->fromList, $stakeholder->from, '');

                $result[] = $stakeholder;
            }

            $data = array();
            $data['page']         = $pager->pageID;
            $data['total']        = $pager->recTotal;
            $data['limit']        = (int)$pager->recPerPage;
            $data['stakeholders'] = $result;

            $withUser = $this->param('withUser', '');
            if(!empty($withUser)) $data['users'] = $users;

            return $this->send(200, $data);
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @access public
     * @return string
     */
    public function post()
    {
        $control = $this->loadController('project', 'create');

        $fields = 'name,begin,end,products';
        $this->batchSetPost($fields);

        $this->setPost('code', $this->request('code', ''));
        $this->setPost('acl', $this->request('acl', 'private'));
        $this->setPost('parent', $this->request('program', 0));
        $this->setPost('whitelist', $this->request('whitelist', array()));
        $this->setPost('PM', $this->request('PM', ''));
        $this->setPost('model', $this->request('model', 'scrum'));

        $this->requireFields('name,code,begin,end,products');

        $control->create($this->request('model', 'scrum'));

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $project = $this->loadModel('project')->getByID($data->id);

        return $this->send(201, $this->format($project, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time'));
    }

    /**
     * Get drop menu.
     *
     * @access public
     * @return string
     */
    public function getDropMenu()
    {
        $control = $this->loadController('project', 'ajaxGetDropMenu');
        $control->ajaxGetDropMenu($this->request('projectID', 0), $this->request('module', 'project'), $this->request('method', 'browse'));

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $dropMenu = array('owner' => array(), 'other' => array(), 'closed' => array());
        foreach($data->data->projects as $programID => $projects)
        {
            foreach($projects as $project)
            {
                if(helper::diffDate(date('Y-m-d'), $project->end) > 0) $project->delay = true;
                $project = $this->filterFields($project, 'id,model,type,name,code,parent,status,PM,delay');

                if($project->status == 'closed')
                {
                    $dropMenu['closed'][] = $project;
                }
                elseif($project->PM == $this->app->user->account)
                {
                    $dropMenu['owner'][] = $project;
                }
                else
                {
                    $dropMenu['other'][] = $project;
                }
            }
        }
        return $this->send(200, $dropMenu);
    }
}

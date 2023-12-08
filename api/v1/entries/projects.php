<?php
/**
 * The project entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class projectsEntry extends entry
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
        $appendFields = $this->param('fields', '');
        if(stripos(strtolower(",{$appendFields},"), ',dropmenu,') !== false) return $this->getDropMenu();

        $_COOKIE['involved'] = $this->param('involved', 0);

        $this->config->systemMode = 'ALM';

        if($programID)
        {
            $control = $this->loadController('program', 'project');
            $control->project($programID, $this->param('status', 'all'), $this->param('order', 'order_asc'), 0, $this->param('limit', 20), $this->param('page', 1));
            $data = $this->getData();
        }
        else
        {
            $control = $this->loadController('project', 'browse');
            $control->browse($programID, $this->param('status', 'all'), 0, $this->param('order', 'order_asc'), 0, $this->param('limit', 20), $this->param('page', 1));
            $data = $this->getData();
        }

        if(isset($data->status) and $data->status == 'success')
        {
            $pager  = $data->data->pager;
            $users  = $data->data->users;
            $result = array();
            foreach($data->data->projectStats as $project)
            {
                $result[] = $this->format($project, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,realBegan:date,realEnd:date,PM:user,whitelist:userList,deleted:bool');
            }

            $data = array();
            $data['page']     = $pager->pageID;
            $data['total']    = $pager->recTotal;
            $data['limit']    = (int)$pager->recPerPage;
            $data['projects'] = $result;

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
        $fields = 'name,begin,end,products,desc,hasProduct';
        $this->batchSetPost($fields);
        if(isset($_POST['products']) and !isset($_POST['hasProduct'])) $_POST['hasProduct'] = true;

        $useCode = $this->checkCodeUsed();

        if($useCode) $this->setPost('code', $this->request('code', ''));
        $this->setPost('acl', $this->request('acl', 'private'));
        $this->setPost('parent', $this->request('program', 0));
        $this->setPost('whitelist', $this->request('whitelist', array()));
        $this->setPost('PM', $this->request('PM', ''));
        $this->setPost('model', $this->request('model', 'scrum'));
        $this->setPost('parent', $this->request('parent', 0));

        $control = $this->loadController('project', 'create');

        $requireFields = 'name,begin,end,products';
        if($useCode) $requireFields .= ',code';
        $this->requireFields($requireFields);

        $control->create($this->request('model', 'scrum'));

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $project = $this->loadModel('project')->getByID($data->id);

        return $this->send(201, $this->format($project, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,realBegan:date,realEnd:date,PM:user,whitelist:userList,deleted:bool'));
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

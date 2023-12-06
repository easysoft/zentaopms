<?php
/**
 * The programs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class programsEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $_COOKIE['showClosed'] = $this->param('showClosed', 0);
        $mergeChildren = $this->param('mergeChildren', 0);

        $this->config->systemMode = 'ALM';

        $fields = $this->param('fields', '');
        if(stripos(strtolower(",{$fields},"), ",dropmenu,") !== false) return $this->getDropMenu();

        $program = $this->loadController('program', 'browse');
        $program->browse($this->param('status', 'all'), $this->param('order', 'order_asc'));

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $programs     = $data->data->programs;
        $result       = array();
        foreach($programs as $program)
        {
            $program = $this->format($program, 'begin:date,end:date,PO:user,PM:user,QD:user,RD:user,realBegan:date,realEnd:date,openedBy:user,openedDate:time,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,deleted:bool,whitelist:userList');

            if($mergeChildren)
            {
                unset($program->desc);
                $program->end = $program->end == LONG_TIME ? $this->lang->program->longTime : $program->end;

                $programBudget = $this->loadModel('project')->getBudgetWithUnit($program->budget);
                $program->labelBudget = $program->budget != 0 ? zget($this->lang->project->currencySymbol, $program->budgetUnit) . ' ' . $programBudget : $this->lang->project->future;

                if(empty($program->parent)) $result[$program->id] = $program;
                if(isset($programs->{$program->parent}))
                {
                    $parentProgram = $programs->{$program->parent};
                    if(!isset($parentProgram->children)) $parentProgram->children = array();
                    $parentProgram->children[] = $program;
                }
            }
            else
            {
                $result[] = $program;
            }
        }
        return $this->send(200, array('programs' => array_values($result)));
    }

    /**
     * POST method.
     *
     * @access public
     * @return string
     */
    public function post()
    {
        $fields = 'name,PM,budget,budgetUnit,desc,begin,end';
        $this->batchSetPost($fields);
        $this->setPost('acl', $this->request('acl', 'open'));
        $this->setPost('whitelist', $this->request('whitelist', array()));

        $control = $this->loadController('program', 'create');
        $this->requireFields('name,begin,end');

        $control->create($this->request('parent', 0));

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $program = $this->loadModel('program')->getByID($data->id);
        return $this->send(201, $this->format($program, 'begin:date,end:date,PO:user,PM:user,QD:user,RD:user,realBegan:date,realEnd:date,openedBy:user,openedDate:time,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,deleted:bool,whitelist:userList'));
    }

    /**
     * Get drop menu.
     *
     * @access public
     * @return string
     */
    public function getDropMenu()
    {
        $programs = $this->dao->select('id,name,parent,path,grade,`order`')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq('program')
            ->andWhere('id')->in($this->app->user->view->programs)
            ->beginIF(empty($_COOKIE['showClosed']))->andWhere('status')->ne('closed')->fi()
            ->orderBy('grade desc, `order`')
            ->fetchAll('id');

        $dropMenu = array();
        foreach($programs as $programID => $program)
        {
            if(empty($program->parent))
            {
                $dropMenu[] = $program;
            }
            elseif(isset($programs[$program->parent]))
            {
                if(!isset($programs[$program->parent]->children)) $programs[$program->parent]->children = array();
                $programs[$program->parent]->children[] = $program;
            }
        }

        return $this->send(200, $dropMenu);
    }
}

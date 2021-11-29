<?php
/**
 * The programs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class programsEntry extends Entry
{
    /**
     * GET method.
     *
     * @access public
     * @return void
     */
    public function get()
    {
        $_COOKIE['showClosed'] = $this->param('showClosed', 0);
        $mergeChildren = $this->param('mergeChildren', 0);

        $fields = $this->param('fields', '');
        if(stripos(strtolower(",{$fields},"), ",dropmenu,") !== false) return $this->getDropMenu();

        $program = $this->loadController('program', 'browse');
        $program->browse($this->param('status', 'all'), $this->param('order', 'order_asc'));

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $programs     = $data->data->programs;
        $progressList = $data->data->progressList;
        $users        = $data->data->users;
        $result       = array();
        foreach($programs as $program)
        {
            if(isset($progressList->{$program->id})) $program->progress = $progressList->{$program->id};
            $param = $this->format($program, 'begin:date,end:date,realBegan:date,realEnd:date,openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time,deleted:bool');

            if($mergeChildren)
            {
                unset($program->desc);
                $program->openedBy   = zget($users, $program->openedBy);
                $program->closedBy   = zget($users, $program->closedBy);
                $program->canceledBy = zget($users, $program->canceledBy);
                $program->PO         = zget($users, $program->PO);
                $program->PM         = zget($users, $program->PM);
                $program->QD         = zget($users, $program->QD);
                $program->RD         = zget($users, $program->RD);
                $program->end        = $program->end == LONG_TIME ? $this->lang->program->longTime : $program->end;

                $programBudget = in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? round((float)$program->budget / 10000, 2) . $this->lang->project->tenThousand : round((float)$program->budget, 2);
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
     * @return void
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
        $this->send(201, $this->format($program, 'openedDate:time,whitelist:stringList'));
    }

    /**
     * Get drop menu.
     *
     * @access public
     * @return void
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

        $this->send(200, $dropMenu);
    }
}

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
class ProgramsEntry extends Entry
{
    /**
     * GET method.
     *
     * @access public
     * @return void
     */
    public function get()
    {
        $program = $this->loadController('program', 'browse');
        $program->browse($this->param('status', 'all'), $this->param('order', 'order_asc'));

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'success')
        {
            $programs = $data->data->programs;
            $result   = array();
            foreach($programs as $program)
            {
                $program->progress = zget($progressList, $program->id, 0);
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

                    if(empty($program->parent)) $result[$program->parent][$program->id] = $program;
                    if(isset($programs[$program->parent]))
                    {
                        $parentProgram = $programs[$program->parent];
                        if(!isset($parentProgram->children)) $parentProgram->children = array();
                        $parentProgram->children[] = $program;
                    }
                }
                else
                {
                    $result[] = $program;
                }
            }
            return $this->send(200, array('programs' => $result));
        }
        if(isset($data->status) and $data->status == 'fail')
        {
            return $this->sendError(400, $data->message);
        }

        return $this->sendError(400, 'error');
    }
}

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

        $program = $this->loadController('program', 'browse');
        $program->browse($this->param('status', 'all'), $this->param('order', 'order_asc'));

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'success')
        {
            $programs     = (array)$data->data->programs;
            $progressList = $data->data->progressList;
            $users        = $data->data->users;
            $result       = array();
            foreach($programs as $program)
            {
                $program->progress   = zget($progressList, $program->id, 0);
                $program->openedBy   = zget($users, $program->openedBy);
                $program->closedBy   = zget($users, $program->closedBy);
                $program->canceledBy = zget($users, $program->canceledBy);
                $program->PO         = zget($users, $program->PO);
                $program->PM         = zget($users, $program->PM);
                $program->QD         = zget($users, $program->QD);
                $program->RD         = zget($users, $program->RD);
                unset($program->desc);

                $param = $this->format($program, 'begin:date,end:date,realBegan:date,realEnd:date,openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time,deleted:bool');

                if($mergeChildren)
                {
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

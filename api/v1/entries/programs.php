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
                $result[] = $this->format($program, 'begin:date,end:date,realBegan:date,realEnd:date,openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time,deleted:bool');
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

<?php
/**
 * The program entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class programEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function get($programID)
    {
        $program = $this->loadModel('program')->getByID($programID);
        if(!$program) return $this->send404();

        $this->send(200, $this->format($program, 'openedDate:time,realBegan:date,realEnd:date,deleted:bool'));
    }

    /**
     * PUT method.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function put($programID)
    {
        $oldProgram = $this->loadModel('program')->getByID($programID);

        /* Set $_POST variables. */
        $fields = 'name,PM,budget,budgetUnit,desc,begin,end,realBegan,realEnd,ac,whitelist';
        $this->batchSetPost($fields, $oldProgram);

        $control = $this->loadController('program', 'edit');
        $control->edit($programID);

        $this->getData();
        $program = $this->program->getByID($programID);
        $this->send(200, $this->format($program, 'openedDate:time'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function delete($programID)
    {
        $control = $this->loadController('program', 'delete');
        $control->delete(0, $programID, 'true');

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $this->sendSuccess(200, 'success');
    }
}

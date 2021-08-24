<?php
/**
 * 禅道API的testtasks资源类
 * 版本V1
 *
 * The testtasks entry point of zentaopms
 * Version 1
 */
class testtasksEntry extends entry
{
    public function get()
    {
        $control = $this->loadController('testtask', 'browse');
        $control->browse($this->param('productID', 0), $this->param('branch', ''), ($this->param('productID', 0) > 0 ? 'local' : 'all') . ',' . $this->param('status', 'totalStatus'), $this->param('order', 'date_desc'), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1), $this->param('begin', ''), $this->param('end', ''));
        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $pager = $data->data->pager;
        $result = array();
        foreach($data->data->tasks as $testtask)
        {
            $result[] = $this->format($testtask, 'realFinishedDate:time');
        }
        return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'testtasks' => $result));
    }
}

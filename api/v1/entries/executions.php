<?php
/**
 * The executions entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class executionsEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function get($projectID = 0)
    {
        $control = $this->loadController('execution', 'all');
        $control->all($this->param('status', 'all'), $this->param('project', $projectID), $this->param('order', 'id_desc'), 0, $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $pager  = $data->data->pager;
            $result = array();
            foreach($data->data->executionStats as $execution)
            {
                $result[] = $this->format($execution, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time,begin:date,end:date,realBegan:date,realEnd:date,deleted:bool');
            }
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'executions' => $result));
        }
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function post($projectID = 0)
    {
        $fields = 'project,code,name,begin,end,lifetime,desc,days';
        $this->batchSetPost($fields);

        $projectID = $this->param('project', $projectID);
        $this->setPost('project', $projectID);
        $this->setPost('acl', $this->request('acl', 'private'));
        $this->setPost('whitelist', $this->request('whitelist', array()));
        $this->setPost('products', $this->request('products', array()));
        $this->setPost('plans', $this->request('plans', array()));

        $control = $this->loadController('execution', 'create');
        $this->requireFields('name,code,begin,end,days');

        $control->create($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $execution = $this->loadModel('execution')->getByID($data->id);

        $this->send(201, $this->format($execution, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time'));
    }
}

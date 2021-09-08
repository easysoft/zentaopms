<?php
/**
 * The project entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
     * @access public
     * @return void
     */
    public function get()
    {
        $control = $this->loadController('project', 'browse');
        $control->browse(0, $this->param('status', 'all'), 0, $this->param('order', 'order_asc'), 0, $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $pager  = $data->data->pager;
            $result = array();
            foreach($data->data->projectStats as $project)
            {
                $result[] = $this->format($project, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time');
            }
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => (int)$pager->recPerPage, 'projects' => $result));
        }

        if(isset($data->status) and $data->status == 'fail')
        {
            return $this->sendError(400, $data->message);
        }

        // TODO There is no handle for 401.
        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @access public
     * @return void
     */
    public function post()
    {
        $fields = 'name,begin,end,products';
        $this->batchSetPost($fields);

        $this->setPost('acl', $this->request('acl', 'private'));
        $this->setPost('parent', $this->request('program', 0));
        $this->setPost('whitelist', $this->request('whitelist', array()));
        $this->setPost('PM', $this->request('PM', ''));
        $this->setPost('model', $this->request('model', 'scrum'));

        $control = $this->loadController('project', 'create');
        $this->requireFields('name,begin,end,products');

        $control->create($this->request('model', 'scrum'));

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $project = $this->loadModel('project')->getByID($data->id);

        $this->send(201, $this->format($project, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time'));
    }
}

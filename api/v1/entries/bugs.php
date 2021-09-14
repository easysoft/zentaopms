<?php
/**
 * The bugs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class bugsEntry extends entry 
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function get($productID = 0, $projectID = 0, $executionID = 0)
    {
        if(!$productID)   $productID   = $this->param('product', 0);
        if(!$projectID)   $projectID   = $this->param('project', 0);
        if(!$executionID) $executionID = $this->param('execution', 0);

        if($projectID)
        {
            $control = $this->loadController('project', 'bug');
            $control->bug($projectID, $productID, $this->param('order', 'status,id_desc'), $this->param('build', 0), $this->param('status', 'all'), 0, $this->param('limit', 20), $this->param('page', 1));
        }
        elseif($executionID)
        {
            $control = $this->loadController('execution', 'bug');
            $control->bug($executionID, $productID, $this->param('order', 'status,id_desc'), $this->param('build', 0), $this->param('status', 'all'), 0, $this->param('limit', 20), $this->param('page', 1));
        }
        elseif($productID)
        {
            $control = $this->loadController('bug', 'browse');
            $control->browse($productID, $this->param('branch', ''), $this->param('status', ''), 0, $this->param('order', ''), 0, $this->param('limit', 20), $this->param('page', 1));
        }
        else
        {
            return $this->sendError(400, 'Need product or project or execution id.');
        }

        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $bugs   = $data->data->bugs;
            $pager  = $data->data->pager;
            $result = array();
            foreach($bugs as $bug)
            {
                $result[] = $this->format($bug, 'activatedDate:time,openedDate:time,assignedDate:time,resolvedDate:time,closedDate:time,lastEditedDate:time,deadline:date,deleted:bool');
            }

            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'bugs' => $result));
        }

        if(isset($data->status) and $data->status == 'fail')
        {
            return $this->sendError(400, $data->message);
        }

        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function post($productID)
    {
        $fields = 'title,project,execution,openedBuild,assignedTo,pri,severity,type,story';
        $this->batchSetPost($fields);

        $this->setPost('product', $productID);

        $control = $this->loadController('bug', 'create');
        $this->requireFields('title,pri,severity,type,openedBuild');

        $control->create($productID);
        
        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $bug = $this->loadModel('bug')->getByID($data->id);

        $this->send(200, $this->format($bug, 'activatedDate:time,openedDate:time,assignedDate:time,resolvedDate:time,closedDate:time,lastEditedDate:time'));
    }
}

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
class projectEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function get($projectID)
    {
        $control = $this->loadController('project', 'view');
        $control->view($projectID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->sendError(400, 'error');

        if(isset($data->status) and $data->status == 'success') return $this->send(200, $this->format($data->data->project, 'begin:date,end:date,realBegan:date,realEnd:date,openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time,deleted:bool'));
        if(isset($data->status) and $data->status == 'fail')
        {
            if(isset($data->code) and $data->code == 404) $this->send404();
            return $this->sendError(400, $data->message);
        }

        $this->sendError(400, 'error');
    }

    /**
     * PUT method.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function put($projectID)
    {
        $oldProject     = $this->loadModel('project')->getByID($projectID);
        $linkedProducts = $this->project->getProducts($projectID);

        /* Set $_POST variables. */
        $fields = 'name,begin,end,acl,parent,desc,PM,whitelist';
        $this->batchSetPost($fields, $oldProject);

        $products = array();
        $plans    = array();
        foreach($linkedProducts as $product)
        {
            $products[] =  $product->id;
            $plans[]    =  $product->plan;
        }
        $this->setPost('products', $products);
        $this->setPost('plans', $plans);

        $control = $this->loadController('project', 'edit');
        $control->edit($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $project = $this->project->getByID($projectID);
        $this->send(200, $this->format($project, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function delete($projectID)
    {
        $control = $this->loadController('project', 'delete');
        $control->delete($projectID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}

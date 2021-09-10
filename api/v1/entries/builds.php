<?php
/**
 * The builds entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class buildsEntry extends entry
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
        if(!$projectID) $projectID = $this->param('project', 0);

        $control = $this->loadController('project', 'build');
        $control->build($projectID, $this->param('type', 'all'), $this->param('param', 0));
        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $result = array();
        foreach($data->data->projectBuilds as $productID => $builds)
        {
            foreach($builds as $build)
            {
                $result[] = $build;
            }
        }

        return $this->send(200, $result);
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
        if(!$projectID) $projectID = $this->param('project', 0);

        $project = $this->loadModel('project')->getByID($projectID);
        if(!$project) return $this->send404();

        $fields = 'execution,product,name,builder,date,scmPath,filePath,desc';
        $this->batchSetPost($fields);

        $control = $this->loadController('build', 'create');
        $this->requireFields('execution,product,name,builder,date');

        $control->create(0,0,$projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $build = $this->loadModel('build')->getByID($data->id);

        $this->send(201, $build);
    }
}

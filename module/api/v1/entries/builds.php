<?php
/**
 * The builds entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * @return string
     */
    public function get($projectID = 0)
    {
        if(empty($projectID)) $projectID = $this->param('project', 0);
        if(empty($projectID)) return $this->sendError(400, "Need project id.");

        $control = $this->loadController('projectbuild', 'browse');
        $control->browse($projectID, $this->param('type', 'all'), $this->param('param', 0), $this->param('order', 't1.date_desc,t1.id_desc'));
        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $result = array();
        foreach($data->data->projectBuilds as $productID => $builds)
        {
            foreach($builds as $build) $result[] = $this->format($build, 'bugs:idList,stories:idList,builder:user,deleted:bool');
        }

        return $this->send(200, array('total' => count($result), 'builds' => $result));
    }

    /**
     * POST method.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function post($projectID = 0)
    {
        if(!$projectID) $projectID = $this->param('project', 0);

        $project = $this->loadModel('project')->getByID($projectID);
        if(!$project) return $this->sendError(404, 'Error project id');
        $this->setPost('project', $projectID);

        $fields = 'execution,product,name,builder,date,scmPath,filePath,desc,branch';
        $this->batchSetPost($fields);

        $control = $this->loadController('build', 'create');
        $this->requireFields('execution,product,name,builder,date');

        $executionID = isset($_POST['execution']) ? $_POST['execution'] : 0;
        $control->create($executionID, 0, $projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $build = $this->loadModel('build')->getByID($data->id);

        return $this->send(201, $build);
    }
}

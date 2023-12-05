<?php
/**
 * The testtasks entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class testtasksEntry extends entry
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
        if($projectID) return $this->getProjectTesttasks($projectID);

        /* Get all testtasks. */
        $control   = $this->loadController('testtask', 'browse');
        $productID = $this->param('product', 0);
        $control->browse($productID, $this->param('branch', ''), ($productID > 0 ? 'local' : 'all') . ',' . $this->param('status', 'totalStatus'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1), $this->param('begin', ''), $this->param('end', ''));
        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $pager  = $data->data->pager;
        $result = array();
        foreach($data->data->tasks as $testtask)
        {
            $result[] = $this->format($testtask, 'begin:date,end:date,mailto:userList,owner:user,realFinishedDate:time');
        }

        return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'testtasks' => $result));
    }

    /**
     * Get testtasks of project.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    private function getProjectTesttasks($projectID)
    {
        $project = $this->loadModel('project')->getByID($projectID);
        if(!$project) return $this->send404();

        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));

        $testtasks = $this->loadModel('testtask')->getProjectTasks($projectID, $this->param('order', 'id_desc'), $pager);

        $result = array();
        foreach($testtasks as $testtask)
        {
            $result[] = $this->format($testtask, 'realFinishedDate:time');
        }

        return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'testtasks' => $result));
    }

    /**
     * POST method.
     *
     * @access public
     * @param  int    $projectID
     * @return string
     */
    public function post($projectID = 0)
    {
        if(!$projectID) $projectID = $this->param('project', 0);
        $productID   = $this->request('product', 0);
        $executionID = $this->request('execution', 0);
        $buildID     = $this->request('build', 0);
        if(empty($projectID))   return $this->sendError(400, 'need project id!');
        if(empty($productID))   return $this->sendError(400, 'need product id!');
        if(empty($executionID)) return $this->sendError(400, 'need execution id!');
        if(empty($buildID))     return $this->sendError(400, 'need build id!');

        /* Check whether executionID and buildID is valid. */
        $executions = $this->loadModel('product')->getExecutionPairsByProduct($productID, '', $projectID);
        $builds     = $this->loadModel('build')->getBuildPairs(array($productID), 'all', 'notrunk');
        if(!isset($executions[$executionID])) return $this->sendError(400, 'error execution id!');
        if(!isset($builds[$buildID]))         return $this->sendError(400, 'error build id!');

        $fields = 'product,execution,build,name,begin,end,owner,type,pri,status,desc';
        $this->batchSetPost($fields);

        $control = $this->loadController('testtask', 'create');
        $this->requireFields('name,begin,end');
        $control->create($productID, $executionID, $build, $projectID);

        $data = $this->getData();
        if(!isset($data->id)) return $this->sendError(400, $data->message);

        $testtask = $this->loadModel('testtask')->getByID($data->id);

        return $this->send(201, $testtask);
    }
}

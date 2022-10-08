<?php
/**
 * The testtasks entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
     * @return void
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
     * @return void
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
}

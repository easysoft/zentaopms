<?php
/**
 * The issues entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class issuesEntry extends entry
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
        if($projectID) return $this->getProjectIssues($projectID);

        /* Get my issues defaultly. */
        $control = $this->loadController('my', 'issue');
        $control->issue($this->param('type', 'assignedTo'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $pager  = $data->data->pager;
        $result = array();
        foreach($data->data->issues as $issue)
        {
            $result[] = $this->format($issue, 'createdDate:time,editedDate:time,assignedDate:time');
        }

        return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'issues' => $result));
    }

    /**
     * Get issues of project.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    private function getProjectIssues($projectID)
    {
        $project = $this->loadModel('project')->getByID($projectID);
        if(!$project) return $this->send404();

        $control = $this->loadController('issue', 'browse');
        $control->browse($projectID, $this->param('type', 'all'), 0, $this->param('order', ''), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $pager  = $data->data->pager;
        $result = array();
        foreach($data->data->issueList as $issue)
        {
            $result[] = $this->format($issue, 'createdDate:time,editedDate:time,assignedDate:time');
        }

        return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'issues' => $result));
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
        $project = $this->loadModel('project')->getByID($projectID);
        if(!$project) return $this->send404();

        $fields = 'type,title,severity,pri,assignedTo,deadline,desc';
        $this->batchSetPost($fields);

        $control = $this->loadController('issue', 'create');
        $this->requireFields('type,title,severity');

        $control->create($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and!isset($data->id)) return $this->sendError(400, $data->message);

        $issue = $this->loadModel('issue')->getByID($data->id);

        return $this->send(201, $this->format($issue, 'createdDate:time,editedDate:time,assignedDate:time'));
    }
}

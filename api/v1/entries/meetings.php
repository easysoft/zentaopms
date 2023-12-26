<?php
/**
 * The meetings entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class meetingsEntry extends entry
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
        if(empty($projectID)) return $this->sendError(400, 'Need project id.');

        $project = $this->loadModel('project')->getByID($projectID);
        if(!$project) return $this->send404();

        /* Get meetings by project. */
        $control = $this->loadController('meeting', 'browse');
        $control->browse($projectID, $this->param('status', 'all'), '', $this->param('order', 'id_desc'), 0, $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $pager  = $data->data->pager;
        $result = array();
        foreach($data->data->meetings as $risk)
        {
            $result[] = $this->format($risk, 'createdDate:time,editedDate:time');
        }

        return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'meetings' => $result));
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

        $fields = 'source,name,category,strategy,status,impact,probability,rate,identifiedDate,plannedClosedDate,actualClosedDate,resolvedBy,assignedTo,prevention,remedy,resolution';
        $this->batchSetPost($fields);

        $this->setPost('impact', $this->request('impact', 3));
        $this->setPost('probability', $this->request('probability', 3));
        $this->setPost('rate', $this->request('rate', 9));
        $this->setPost('pri', 'middle');

        $control = $this->loadController('risk', 'create');
        $this->requireFields('name');

        $control->create($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $risk = $this->loadModel('risk')->getByID($data->id);

        return $this->send(201, $this->format($risk, 'createdDate:time,editedDate:time'));
    }
}

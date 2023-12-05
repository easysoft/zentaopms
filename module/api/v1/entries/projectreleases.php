<?php
/**
 * The projectreleases entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class projectReleasesEntry extends entry
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
        if(empty($projectID)) $projectID = $this->param('project');
        if(empty($projectID)) return $this->sendError(400, 'Need project id.');

        $page    = intval($this->param('page', 1));
        $limit   = intval($this->param('limit', 20));
        $control = $this->loadController('projectrelease', 'browse');
        $control->browse($projectID, $this->param('execution', 0), $this->param('status', 'all'), $this->param('order', 't1.date_desc'), 0, $limit, $page);

        /* Response */
        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $result   = array();
            $releases = $data->data->releases;
            $pager    = $data->data->pager;
            foreach($releases as $release) $result[] = $this->format($release, 'deleted:bool,date:date,mailto:userList');

            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'releases' => $result));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        return $this->sendError(400, 'error');
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

        $fields = 'name,build,product,date,notify,mailto';
        $this->batchSetPost($fields);

        $this->setPost('desc', $this->request('desc', ''));

        $control = $this->loadController('projectrelease', 'create');
        $this->requireFields('name,date');

        $control->create($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and!isset($data->id)) return $this->sendError(400, $data->message);

        $release = $this->loadModel('projectrelease')->getByID($data->id);

        return $this->send(201, $release);
    }
}

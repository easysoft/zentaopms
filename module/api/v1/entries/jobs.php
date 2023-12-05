<?php
/**
 * The jobs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class jobsEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $control  = $this->loadController('job', 'browse');
        $pipeline = $this->param('pipeline', '');
        $orderBy  = $this->param('order', 'id_desc');

        if(empty($pipeline))
        {
            $control->browse($orderBy, 0, $this->param('limit', 100), $this->param('page', 1));

            /* Response */
            $data = $this->getData();
            if(isset($data->status) and $data->status == 'success')
            {
                $result = array();
                $pager  = $data->data->pager;
                $jobs  = $data->data->jobList;
                foreach($jobs as $job) $result[] = $this->format($job, 'deleted:bool,lastSync:datetime,synced:bool,product:idList');

                return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'jobs' => $result));
            }

            if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        }
        else
        {
            $jobs = $this->loadModel('job')->getList($orderBy, null, $this->param('engine', 'jenkins'), $pipeline);
            return $this->send(200, array('jobs' => array_values($jobs)));
        }

        return $this->sendError(400, 'error');
    }
}

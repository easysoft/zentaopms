<?php
/**
 * The model file of durationestimation of ZentaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     durationestimation
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class durationestimationModel extends model
{
    /**
     * Get the project duration estimate.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function getListByProject($projectID)
    {
        return $this->dao->select('*')->from(TABLE_DURATIONESTIMATION)->where('PRJ')->eq($projectID)->fetchAll('stage');
    }

    /**
     * Save the project duration estimate.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function save($projectID)
    {
        $this->dao->delete()->from(TABLE_DURATIONESTIMATION)->where('PRJ')->eq($projectID);
        foreach($this->post->stage as $i => $stage)
        {
            $data = new stdclass;
            $data->PRJ          = $projectID;
            $data->stage        = $stage;
            $data->workload     = $this->post->workload[$i];
            $data->worktimeRate = $this->post->worktimeRate[$i];
            $data->people       = $this->post->people[$i];
            $data->startDate    = $this->post->startDate[$i];
            $data->endDate      = $this->post->endDate[$i];
            $this->dao->insert(TABLE_DURATIONESTIMATION)->data($data)->exec();
        }

        return !dao::isError();
    }
}

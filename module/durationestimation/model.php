<?php
/**
 * The model file of durationestimation module of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     durationestimation
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
class durationestimationModel extends model
{
    public function getListByProgram($program)
    {
        return $this->dao->select('*')->from(TABLE_DURATIONESTIMATION)->where('PRJ')->eq($program)->fetchAll('stage');
    }

    public function save($program)
    {
        $this->dao->delete()->from(TABLE_DURATIONESTIMATION)->where('PRJ')->eq($program);
        foreach($this->post->stage as $i => $stage)
        {
            $estimation = new stdclass;
            $estimation->PRJ          = $program;
            $estimation->stage        = $stage;
            $estimation->workload     = $this->post->workload[$i];
            $estimation->worktimeRate = $this->post->worktimeRate[$i];
            $estimation->people       = $this->post->people[$i];
            $estimation->startDate    = $this->post->startDate[$i];
            $estimation->endDate      = $this->post->endDate[$i];
            $this->dao->insert(TABLE_DURATIONESTIMATION)->data($estimation)->exec();
        }

        return !dao::isError();
    }
}

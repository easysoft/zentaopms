<?php

/**
 * The control file of gitlabpipeline module of ZenTaoPMS.
 *
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @link        http://www.zentao.net
 */
class gitlabpipeline extends control
{
    /**
     * Run pipeline.
     *
     * @param  int    $jobID
     * @access public
     * @return string|false
     */
    public function runPipeline($jobID)
    {
        $now    = helper::now();
        $job    = $this->loadModel('job')->getByID($jobID);
        $status = $job->lastStatus;
        if($status and $status != 'success' and $status != 'create_fail')
        {
            $compile = $this->loadModel('compile')->getLastResult($jobID);
            if($compile)
            {
                $pipeline = $this->gitlabpipeline->apiGetPiplineByID($job->server, $job->pipeline, $compile->queue);
                $this->dao->update(TABLE_COMPILE)->set('status')->eq($pipeline->status)->set('updateDate')->eq($now)->where('id')->eq($compile->id)->exec();
                $this->dao->update(TABLE_JOB)->set('lastExec')->eq($now)->set('lastStatus')->eq($pipeline->status)->where('id')->eq($jobID)->exec();
            }
            echo js::alert($this->lang->gitlabpipeline->execError);
            die(js::reload('parent'));
        }

        $pipeline = $this->gitlabpipeline->apiCreatePipeline($job->server, $job->pipeline, array('ref' => 'master'));
        $status   = isset($pipeline->id) ? 'created' : 'create_fail';

        $this->dao->update(TABLE_JOB)->set('lastExec')->eq($now)->set('lastStatus')->eq($status)->where('id')->eq($jobID)->exec();

        if(!isset($pipeline->id)) return false;
        $build = new stdclass;
        $build->job         = $jobID;
        $build->name        = $job->name;
        $build->queue       = $pipeline->id; // use `queue` to save gitlab project pipeline id.
        $build->status      = $pipeline->status;
        $build->createdBy   = $this->app->user->account;
        $build->createdDate = $now;
        $this->dao->insert(TABLE_COMPILE)->data($build)->exec();

        echo js::alert(sprintf($this->lang->gitlabpipeline->sendExec, $pipeline->status));
        die(js::reload('parent'));
    }
}


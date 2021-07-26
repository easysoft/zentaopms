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
     * @return int|false
     */
    public function runPipeline($jobID)
    {
        $job = $this->loadModel('job')->getByID($jobID);
        $pipeline = $this->gitlabpipeline->apiCreatePipeline($job->server, $job->pipeline, array('ref' => 'master'));

        if(isset($pipeline->id))
        {
            a($pipeline); // todo(dingguodong) save $pipeline->id to zt_compile, zt_job.
            echo js::alert(sprintf($this->lang->gitlabpipeline->sendExec, $pipeline->status));
            die(js::reload('parent'));
        }
        else
        {
            return false;
        }
    }

}


<?php
/**
 * The model file of gitlabpipeline module of ZenTaoPMS.
 *
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @link        http://www.zentao.net
 */

class gitlabpipelineModel extends model
{
    /**
     * Get single pipline by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @param integer $pipelineID
     * @access public
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipelines.html#get-a-single-pipeline
     */

    public function apiGetPiplineByID($gitlabID, $projectID, $pipelineID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/pipelines/{$pipelineID}");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get piplines list by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @access public
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipelines.html#list-project-pipelines
     */

    public function apiGetPiplines($gitlabID, $projectID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/pipelines");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get a pipeline’s report by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @param integer $pipelineID
     * @access public
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipelines.html#get-a-pipelines-test-report
     */
    public function apiGetPiplineReport($gitlabID, $projectID, $pipelineID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/pipelines/{$pipelineID}/test_report");
        return json_decode(commonModel::http($url));
    }

    /**
     * Create a new pipeline by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @param string  $reference
     * @access public
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipelines.html#create-a-new-pipeline
     */
    public function apiCreatePipeline($gitlabID, $projectID, $reference)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/pipeline");
        return json_decode(commonModel::http($url, $reference));
    }

    /**
     * Retry jobs in a pipeline by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @param integer $pipelineID
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipelines.html#retry-jobs-in-a-pipeline
     */
    public function apiRetryPipeline($gitlabID, $projectID, $pipelineID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/pipelines/{$piplineID}/retry");
        return json_decode(commonModel::http($url));
    }

    /**
     * Cancel a pipeline’s jobs by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @param integer $pipelineID
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipelines.html#cancel-a-pipelines-jobs
     */
    public function apiPipelineCancel($gitlabID, $projectID, $piplineID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/pipelines/{$pipelineID}/cancel");
        return json_decode(commonModel::http($url));
    }

    /**
     * Delete a pipeline-·· by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @param integer $pipelineID
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipelines.html#delete-a-pipeline
     */
    public function apiDeletePipeline($gitlabID, $projectID, $piplineID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/pipelines/{$piplineID}");
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * Get all pipeline schedules by api.
     *
     * @param  integer $gitlabID
     * @param  integer $projectID
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipeline_schedules.html#get-all-pipeline-schedules
     */
    public function apiGetPiplineSchedules($gitlabID, $projectID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/pipeline_schedules");
        return json_decode(commonModel::http($url));
    }

    /**
     * Delete a pipeline schedule by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @param integer $pipelineScheduleID
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipeline_schedules.html#delete-a-pipeline-schedule
     */
    public function apiDeletePiplineSchedules($gitlabID, $projectID, $pipelineScheduleID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/pipeline_schedules/{$pipelineScheduleID}/play");
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * Run a scheduled pipeline immediately by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @param integer $pipelineScheduleID
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipeline_schedules.html#run-a-scheduled-pipeline-immediately
     */
    public function apiRunPiplineSchedule($gitlabID, $projectID, $jobID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/jobs/{$jobID}/trace");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get a log file by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @param integer $jobID
     * @return object
     * @docment https://docs.gitlab.com/ee/api/jobs.html#get-a-log-file
     */
    public function apiPipelineGetlog($gitlabID, $projectID, $jobID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/jobs/{$jobID}/trace");
        return json_decode(commonModel::http($url));
    }

    /**
     * List pipeline jobs by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @param integer $pipelineID
     * @return object
     * @docment https://docs.gitlab.com/ee/api/jobs.html#list-pipeline-jobs
     */
    public function apiGetPipelineJobs($gitlabID, $projectID, $pipelineID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/pipelines/{$pipelineID}/jobs");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get a single job by api.
     *
     * @param integer $gitlabID
     * @param integer $projectID
     * @param integer $jobID
     * @return object
     * @docment https://docs.gitlab.com/ee/api/jobs.html#get-a-single-job
     */
    public function apiGetSingleJob($gitlabID, $projectID, $jobID)
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($gitlabID), "/projects/{$projectID}/jobs/{$jobID}");
        return json_decode(commonModel::http($url));
    }
}

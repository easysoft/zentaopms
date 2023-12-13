<?php
/**
 * The model file of compile module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     compile
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class compileModel extends model
{
    /**
     * Get by id
     *
     * @param  int    $buildID
     * @access public
     * @return object
     */
    public function getByID($buildID)
    {
        return $this->dao->select('*')->from(TABLE_COMPILE)->where('id')->eq($buildID)->fetch();
    }

    /**
     * Get By Queue.
     *
     * @param  int    $queue
     * @access public
     * @return void
     */
    public function getByQueue($queue)
    {
        return $this->dao->select('*')->from(TABLE_COMPILE)->where('queue')->eq($queue)->fetch();
    }

    /**
     * Get build list.
     *
     * @param  int    $repoID
     * @param  int    $jobID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($repoID, $jobID, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('t1.id, t1.name, t1.job, t1.status, t1.createdDate, t1.testtask, t2.pipeline, t2.triggerType, t2.comment, t2.atDay, t2.atTime, t2.engine, t3.name as repoName, t4.name as jenkinsName')->from(TABLE_COMPILE)->alias('t1')
            ->leftJoin(TABLE_JOB)->alias('t2')->on('t1.job=t2.id')
            ->leftJoin(TABLE_REPO)->alias('t3')->on('t2.repo=t3.id')
            ->leftJoin(TABLE_PIPELINE)->alias('t4')->on('t2.server=t4.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.job')->ne('0')
            ->beginIF(!empty($repoID))->andWhere('t3.id')->eq($repoID)->fi()
            ->beginIF(!empty($jobID))->andWhere('t1.job')->eq($jobID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get list by jobID.
     *
     * @param  int $jobID
     * @return array
     */
    public function getListByJobID($jobID)
    {
        return $this->dao->select('id, name, status')->from(TABLE_COMPILE)
            ->where('deleted')->eq('0')
            ->andWhere('job')->ne('0')
            ->beginIF(!empty($jobID))->andWhere('job')->eq($jobID)->fi()
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

    /**
     * Get unexecuted list.
     *
     * @access public
     * @return array
     */
    public function getUnexecutedList()
    {
        return $this->dao->select('*')->from(TABLE_COMPILE)->where('status')->eq('')->andWhere('deleted')->eq('0')->fetchAll();
    }

    /**
     * Get last result.
     *
     * @param  int    $jobID
     * @access public
     * @return object
     */
    public function getLastResult($jobID)
    {
        return $this->dao->select('*')->from(TABLE_COMPILE)->where('job')->eq($jobID)->andWhere('status')->ne('')->orderBy('createdDate_desc')->limit(1)->fetch();
    }

    /**
     * Get success jobs by job id list.
     *
     * @param  array  $jobIDList
     * @access public
     * @return array
     */
    public function getSuccessJobs($jobIDList)
    {
        return $this->dao->select('job')->from(TABLE_COMPILE)
            ->where('job')->in($jobIDList)
            ->andWhere('status')->eq('success')
            ->fetchPairs();
    }

    /**
     * Get build url.
     *
     * @param  object $jenkins
     * @access public
     * @return object
     */
    public function getBuildUrl($jenkins)
    {
        $jenkinsServer   = $jenkins->url;
        $jenkinsUser     = $jenkins->account;
        $jenkinsPassword = $jenkins->token ? $jenkins->token : base64_decode($jenkins->password);

        $url = new stdclass();
        $url->userPWD = "$jenkinsUser:$jenkinsPassword";

        $detailUrl             = strpos($jenkins->pipeline, '/job/') !== false ? sprintf('%s%s/api/json', $jenkinsServer, $jenkins->pipeline) : sprintf('%s/job/%s/api/json', $jenkinsServer, $jenkins->pipeline);
        $hasParameterizedBuild = $this->loadModel('job')->checkParameterizedBuild($detailUrl, $url->userPWD);
        $buildInterface        = $hasParameterizedBuild ? 'buildWithParameters' : 'build';

        if(strpos($jenkins->pipeline, '/job/') !== false)
        {
            $url->url = sprintf("%s%s{$buildInterface}/api/json", $jenkinsServer, $jenkins->pipeline);
        }
        else
        {
            $url->url = sprintf("%s/job/%s/{$buildInterface}/api/json", $jenkinsServer, $jenkins->pipeline);
        }

        return $url;
    }

    /**
     * Get compile logs.
     *
     * @param  object $job
     * @param  object $compile
     * @access public
     * @return string
     */
    public function getLogs($job, $compile)
    {
        $logs = '';

        if($job->engine == 'jenkins')
        {
            $jenkins         = $this->loadModel('jenkins')->getByID($job->server);
            $jenkinsUser     = $jenkins->account;
            $jenkinsPassword = $jenkins->token ? $jenkins->token : base64_decode($jenkins->password);
            $userPWD         = "$jenkinsUser:$jenkinsPassword";

            $infoUrl  = sprintf('%s/job/%s/api/xml?tree=builds[id,number,queueId]&xpath=//build[queueId=%s]', $jenkins->url, $job->pipeline, $compile->queue);
            $result   = common::http($infoUrl, '', array(CURLOPT_USERPWD => $userPWD), array(), 'data', 'POST', 30, true);
            $response = $result['body'];
            $httpCode = $result[1];
            if($httpCode == 404) return '';
            if($response)
            {
                $buildInfo   = simplexml_load_string($response);
                $buildNumber = $buildInfo->number;
                if(empty($buildNumber)) return '';

                $logUrl = sprintf('%s/job/%s/%s/consoleText', $jenkins->url, $job->pipeline, $buildNumber);
                $logs   = common::http($logUrl, '', array(CURLOPT_USERPWD => $userPWD));
                $this->dao->update(TABLE_COMPILE)->set('logs')->eq($logs)->where('id')->eq($compile->id)->exec();
            }
        }
        else
        {
            /* Get jobs by pipeline. */
            $pipeline  = json_decode($job->pipeline);
            $projectID = isset($pipeline->project) ? $pipeline->project : '';
            $jobs      = $this->loadModel('gitlab')->apiGetJobs($job->server, $projectID, $compile->queue);

            $this->loadModel('ci');
            foreach($jobs as $gitlabJob)
            {
                if(!is_object($gitlabJob)) continue;

                if(empty($gitlabJob->duration)) $gitlabJob->duration = '-';
                $logs .= "<font style='font-weight:bold'>&gt;&gt;&gt; Job: $gitlabJob->name, Stage: $gitlabJob->stage, Status: $gitlabJob->status, Duration: $gitlabJob->duration Sec\r\n </font>";
                $logs .= "Job URL: <a href=\"$gitlabJob->web_url\" target='_blank'>$gitlabJob->web_url</a> \r\n";
                $logs .= $this->ci->transformAnsiToHtml($this->gitlab->apiGetJobLog($job->server, $projectID, $gitlabJob->id));
            }
        }

        if(!empty($logs)) $this->dao->update(TABLE_COMPILE)->set('logs')->eq($logs)->where('id')->eq($compile->id)->exec();
        return $logs;
    }

    /**
     * Save build by job
     *
     * @param  int    $jobID
     * @param  string $data
     * @param  string $type
     * @access public
     * @return void
     */
    public function createByJob($jobID, $data = '', $type = 'tag')
    {
        $job = $this->dao->select('id,name')->from(TABLE_JOB)->where('id')->eq($jobID)->fetch();

        $build = new stdClass();
        $build->job         = $job->id;
        $build->name        = $job->name;
        $build->$type       = $data;
        $build->createdBy   = $this->app->user->account;
        $build->createdDate = helper::now();

        $this->dao->insert(TABLE_COMPILE)->data($build)->exec();
    }

    /**
     * Sync compiles.
     *
     * @param  int    $repoID
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function syncCompile($repoID = 0, $jobID = 0)
    {
        if($jobID)
        {
            $jobList[$jobID] = $this->loadModel('job')->getByID($jobID);
        }
        else
        {
            $jobList = $this->loadModel('job')->getList($repoID);
        }

        $jenkinsPairs = $this->loadModel('pipeline')->getList('jenkins');
        $gitlabPairs  = $this->loadModel('pipeline')->getList('gitlab');

        foreach($jobList as $job)
        {
            if($job->engine == 'jenkins')
            {
                $server = zget($jenkinsPairs, $job->server);
                $this->syncJenkinsBuildList($server, $job);
            }
            else
            {
                $server = zget($gitlabPairs, $job->server);
                $this->syncGitLabBuildList($server, $job);
            }
        }
    }

    /**
     * Sync jenkins build list.
     *
     * @param  object $jenkins
     * @param  object $job
     * @access public
     * @return void
     */
    public function syncJenkinsBuildList($jenkins, $job)
    {
        if(empty($jenkins->account)) return;

        $jenkinsUser     = $jenkins->account;
        $jenkinsPassword = $jenkins->token ? $jenkins->token : base64_decode($jenkins->password);

        /* Get build list by API. */
        if(strpos($job->pipeline, '/job/') !== false)
        {
            $url = sprintf('%s%sapi/json?tree=builds[id,number,result,queueId,timestamp]', $jenkins->url, $job->pipeline);
        }
        else
        {
            $url = sprintf('%s/job/%s/api/json?tree=builds[id,number,result,queueId,timestamp]', $jenkins->url, $job->pipeline);
        }
        $response = common::http($url, '', array(CURLOPT_USERPWD => "$jenkinsUser:$jenkinsPassword"));
        if(!$response) return false;

        $compilePairs = $this->dao->select('queue,job')->from(TABLE_COMPILE)->where('job')->eq($job->id)->andWhere('queue')->gt(0)->fetchPairs();
        $jobInfo      = json_decode($response);
        if(empty($jobInfo)) return;

        foreach($jobInfo->builds as $build)
        {
            $lastSyncTime = strtotime($job->lastSyncDate);
            if($build->timestamp < $lastSyncTime * 1000) break;
            if(isset($compilePairs[$build->queueId])) continue;

            $data = new stdclass();
            $data->name      = $job->name;
            $data->job       = $job->id;
            $data->queue     = $build->queueId;
            $data->status    = $build->result == 'SUCCESS' ? 'success' : 'failure';
            $data->createdBy = 'guest';

            $buildTime = is_int($build->timestamp) ? $build->timestamp / 1000 : round($build->timestamp);
            $data->createdDate = date('Y-m-d H:i:s', $buildTime);
            $data->updateDate  = $data->createdDate;

            $this->dao->insert(TABLE_COMPILE)->data($data)->exec();
        }

        $now = helper::now();
        $this->dao->update(TABLE_JOB)->set('lastSyncDate')->eq($now)->where('id')->eq($job->id)->exec();
    }

    /**
     * Sync gitlab build list.
     *
     * @param  object $gitlab
     * @param  object $job
     * @access public
     * @return void
     */
    public function syncGitlabBuildList($gitlab, $job)
    {
        if(empty($gitlab->id)) return;

        $pipeline  = json_decode($job->pipeline);
        $projectID = isset($pipeline->project) ? $pipeline->project : '';
        $ref       = isset($pipeline->reference) ? $pipeline->reference : '';
        $url       = sprintf($this->loadModel('gitlab')->getApiRoot($gitlab->id, false), "/projects/{$projectID}/pipelines");

        /* Get build list by API. */
        for($page = 1; true; $page++)
        {
            $param   = $job->lastSyncDate ? '&updated_after=' . date('YYYY-MM-DDThh:mm:ssZ', strtotime($job->lastSyncDate)) : '';
            $builds  = json_decode(commonModel::http($url . "&ref={$ref}&order_by=id&sort=asc&page={$page}&per_page=100" . $param));
            if(!is_array($builds)) break;

            if(!empty($builds))
            {
                $queueIDList = array();
                foreach($builds as $build) $queueIDList[] = $build->id;
                $compilePairs = $this->dao->select('queue,job')->from(TABLE_COMPILE)->where('job')->eq($job->id)->andWhere('queue')->in($queueIDList)->fetchPairs();

                foreach($builds as $build)
                {
                    if(isset($compilePairs[$build->id])) continue;

                    $data = new stdclass();
                    $data->name        = $job->name;
                    $data->job         = $job->id;
                    $data->queue       = $build->id;
                    $data->status      = $build->status;
                    $data->createdBy   = 'guest';
                    $data->createdDate = date('Y-m-d H:i:s', strtotime($build->created_at));
                    $data->updateDate  = date('Y-m-d H:i:s', strtotime($build->updated_at));

                    $this->dao->insert(TABLE_COMPILE)->data($data)->exec();
                }
            }

            if(count($builds) < 100) break;
        }

        $now = helper::now();
        $this->dao->update(TABLE_JOB)->set('lastSyncDate')->eq($now)->where('id')->eq($job->id)->exec();
    }

    /**
     * Execute compile
     *
     * @param  object $compile
     * @access public
     * @return bool
     */
    public function exec($compile)
    {
        $job = $this->dao->select('t1.id,t1.name,t1.repo,t1.engine,t1.pipeline,t2.name as jenkinsName,t2.url,t2.account,t2.token,t2.password,t1.triggerType,t1.customParam,t1.server')
            ->from(TABLE_JOB)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.server=t2.id')
            ->where('t1.id')->eq($compile->job)
            ->fetch();
        if(!$job) return false;

        $compileID = $compile->id;
        $repo      = $this->loadModel('repo')->getByID($job->repo);

        if($job->triggerType == 'tag')
        {
            $lastTag = $this->loadModel('job')->getLastTagByRepo($repo, $job);
            if($lastTag)
            {
                $job->lastTag = $lastTag;
                $this->dao->update(TABLE_JOB)->set('lastTag')->eq($lastTag)->where('id')->eq($job->id)->exec();
            }

            $this->dao->update(TABLE_COMPILE)->set('tag')->eq($lastTag)->where('id')->eq($compile->id)->exec();
        }

        $this->loadModel('job');
        $result = new stdclass();
        if($job->engine == 'gitlab')  $result = $this->job->execGitlabPipeline($job, $compileID);
        if($job->engine == 'jenkins') $result = $this->job->execJenkinsPipeline($job, $repo, $compileID);
        if(!$result) return false;

        $this->dao->update(TABLE_COMPILE)->data($result)->where('id')->eq($compileID)->exec();
        $this->dao->update(TABLE_JOB)
            ->set('lastStatus')->eq($result->status)
            ->set('lastExec')->eq($compile->updateDate)
            ->where('id')->eq($job->id)
            ->exec();

        return !dao::isError();
    }
}

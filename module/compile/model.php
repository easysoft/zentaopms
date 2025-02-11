<?php
declare(strict_types=1);
/**
 * The model file of compile module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     compile
 * @version     $Id$
 * @link        https://www.zentao.net
 * @property    jobModel $job
 */
class compileModel extends model
{
    /**
     * Get by id
     *
     * @param  int    $buildID
     * @access public
     * @return object|false
     */
    public function getByID(int $buildID): object|false
    {
        return $this->dao->select('*')->from(TABLE_COMPILE)->where('id')->eq($buildID)->fetch();
    }

    /**
     * Get build list.
     *
     * @param  int    $repoID
     * @param  int    $jobID
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(int $repoID, int $jobID, string $browseType = '', int $queryID = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        $compileQuery = '';
        if($browseType == 'bySearch')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('compileQuery', $query->sql);
                $this->session->set('compileForm', $query->form);
            }
            elseif(!$this->session->compileQuery)
            {
                $this->session->set('compileQuery', ' 1 = 1');
            }

            $compileQuery = $this->session->compileQuery;
            $compileQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $compileQuery);
            $compileQuery = preg_replace('/t1.`(engine|repo|triggerType)`/', 't2.`$1`', $compileQuery);

            $this->session->set('compileQueryCondition', $compileQuery, $this->app->tab);
            $this->session->set('compileOnlyCondition', true, $this->app->tab);
        }

        if(strpos($orderBy, 'id') === false) $orderBy .= ', id_desc';
        return $this->dao->select('t1.id, t1.name, t1.job, t1.status, t1.createdDate, t1.testtask, t2.pipeline, t2.triggerType, t2.svnDir, t2.comment, t2.atDay, t2.atTime, t2.engine, t2.triggerActions, t3.name as repoName, t4.name as jenkinsName')->from(TABLE_COMPILE)->alias('t1')
            ->leftJoin(TABLE_JOB)->alias('t2')->on('t1.job=t2.id')
            ->leftJoin(TABLE_REPO)->alias('t3')->on('t2.repo=t3.id')
            ->leftJoin(TABLE_PIPELINE)->alias('t4')->on('t2.server=t4.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.job')->ne('0')
            ->beginIF(!empty($repoID))->andWhere('t3.id')->eq($repoID)->fi()
            ->beginIF(!empty($jobID))->andWhere('t1.job')->eq($jobID)->fi()
            ->beginIF($compileQuery)->andWhere($compileQuery)->fi()
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
    public function getListByJobID(int $jobID): array
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
    public function getUnexecutedList(): array
    {
        return $this->dao->select('*')->from(TABLE_COMPILE)->where('status')->eq('')->andWhere('deleted')->eq('0')->fetchAll();
    }

    /**
     * Get last result.
     *
     * @param  int          $jobID
     * @access public
     * @return object|false
     */
    public function getLastResult(int $jobID): object|false
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
    public function getSuccessJobs(array $jobIDList): array
    {
        return $this->dao->select('job')->from(TABLE_COMPILE)
            ->where('job')->in($jobIDList)
            ->andWhere('status')->eq('success')
            ->fetchPairs();
    }

    /**
     * Get build url.
     *
     * @param  object $job
     * @access public
     * @return object
     */
    public function getBuildUrl(object $job): object
    {
        $url = new stdclass();
        $url->userPWD = $this->loadModel('jenkins')->getApiUserPWD($job);

        $urlPrefix = $this->compileTao->getJenkinsUrlPrefix($job->url, $job->pipeline);

        $detailUrl             = $urlPrefix . 'api/json';
        $hasParameterizedBuild = $this->loadModel('job')->checkParameterizedBuild($detailUrl, $url->userPWD);
        $buildInterface        = $hasParameterizedBuild ? 'buildWithParameters' : 'build';

        $url->url = "{$urlPrefix}{$buildInterface}/api/json";
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
    public function getLogs(object $job, object $compile): string
    {
        $logs = '';

        /* Get jobs by pipeline. */
        $pipeline = json_decode($job->pipeline);
        if($job->engine == 'jenkins')
        {
            $jenkins = $this->loadModel('pipeline')->getByID($job->server);
            $userPWD = $this->loadModel('jenkins')->getApiUserPWD($jenkins);

            $urlPrefix = $this->compileTao->getJenkinsUrlPrefix($jenkins->url, $job->pipeline);
            $infoUrl   = $urlPrefix . sprintf('api/xml?tree=builds[id,number,queueId]&xpath=//build[queueId=%s]', $compile->queue);
            $result    = common::http($infoUrl, '', array(CURLOPT_USERPWD => $userPWD), array(), 'data', 'POST', 30, true);

            /* Check error. */
            $httpCode = $result[1];
            if($httpCode == 404) return '';

            $response = $result['body'];
            if($response)
            {
                $buildInfo   = simplexml_load_string($response);
                $buildNumber = $buildInfo->number;
                if(empty($buildNumber)) return '';

                $logUrl = sprintf($urlPrefix . '%s/consoleText', $buildNumber);
                $logs   = common::http($logUrl, '', array(CURLOPT_USERPWD => $userPWD));
                $this->dao->update(TABLE_COMPILE)->set('logs')->eq($logs)->where('id')->eq($compile->id)->exec();
            }
        }
        else
        {
            $projectID = isset($pipeline->project) ? $pipeline->project : 0;
            $jobs      = $this->loadModel('gitlab')->apiGetJobs($job->server, (int)$projectID, $compile->queue);

            $this->loadModel('ci');
            foreach($jobs as $gitlabJob)
            {
                if(!is_object($gitlabJob)) continue;

                if(empty($gitlabJob->duration)) $gitlabJob->duration = '-';
                $logs .= "<font style='font-weight:bold'>&gt;&gt;&gt; Job: $gitlabJob->name, Stage: $gitlabJob->stage, Status: $gitlabJob->status, Duration: $gitlabJob->duration Sec\r\n </font>";
                $logs .= "Job URL: <a href=\"$gitlabJob->web_url\" target='_blank'>$gitlabJob->web_url</a> \r\n";
                $logs .= $this->ci->transformAnsiToHtml($this->gitlab->apiGetJobLog($job->server, (int)$projectID, $gitlabJob->id));
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
     * @return int|false
     */
    public function createByJob(int $jobID, string $data = '', string $type = 'tag'): int|false
    {
        $job = $this->dao->select('id,name')->from(TABLE_JOB)->where('id')->eq($jobID)->fetch();
        if(!$job) return false;

        $build = new stdClass();
        $build->job         = $job->id;
        $build->name        = $job->name;
        if($type) $build->$type = $data;
        $build->createdBy   = $this->app->user ? $this->app->user->account : 'system';
        $build->createdDate = helper::now();

        $this->dao->insert(TABLE_COMPILE)->data($build)->exec();
        return $this->dao->lastInsertId();
    }

    /**
     * Sync compiles.
     *
     * @param  int    $repoID
     * @param  int    $repoID
     * @access public
     * @return bool
     */
    public function syncCompile(int $repoID = 0, int $jobID = 0): bool
    {
        if($jobID)
        {
            $jobList[$jobID] = $this->loadModel('job')->getByID($jobID);
        }
        else
        {
            $jobList = $this->loadModel('job')->getList($repoID);
        }

        $servers = $this->loadModel('pipeline')->getList('');

        foreach($jobList as $job)
        {
            $server = zget($servers, $job->server);
            $method = "sync{$job->engine}BuildList";
            $this->$method($server, $job);

            $this->compileTao->updateJobLastSyncDate($job->id, helper::now());
        }
        return !dao::isError();
    }

    /**
     * Sync jenkins build list.
     *
     * @param  object $jenkins
     * @param  object $job
     * @access public
     * @return bool
     */
    public function syncJenkinsBuildList(object $jenkins, object $job): bool
    {
        if(empty($jenkins->account)) return false;
        $userPWD = $this->loadModel('jenkins')->getApiUserPWD($jenkins);

        /* Get build list by API. */
        $urlPrefix = $this->compileTao->getJenkinsUrlPrefix($jenkins->url, $job->pipeline);
        $url       = $urlPrefix . 'api/json?tree=builds[id,number,result,queueId,timestamp]';
        $response  = common::http($url, '', array(CURLOPT_USERPWD => $userPWD));
        if(!$response) return false;

        $jobInfo = json_decode($response);
        if(empty($jobInfo)) return false;

        $compilePairs = $this->dao->select('queue,job')->from(TABLE_COMPILE)->where('job')->eq($job->id)->andWhere('queue')->gt(0)->fetchPairs();
        foreach($jobInfo->builds as $build)
        {
            $lastSyncTime = strtotime($job->lastSyncDate);
            if($build->timestamp < $lastSyncTime * 1000) break;
            if(isset($compilePairs[$build->queueId])) continue;

            $this->compileTao->createByBuildInfo($job->name, $job->id, $build, 'jenkins');
        }
        return !dao::isError();
    }

    /**
     * Sync gitlab build list.
     *
     * @param  object $gitlab
     * @param  object $job
     * @access public
     * @return bool
     */
    public function syncGitlabBuildList(object $gitlab, object $job): bool
    {
        if(empty($gitlab->id)) return false;

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

                    $this->compileTao->createByBuildInfo($job->name, $job->id, $build, 'gitlab');
                }
            }

            if(count($builds) < 100) break;
        }
        return !dao::isError();
    }

    /**
     * Execute compile
     *
     * @param  object $compile
     * @access public
     * @return bool
     */
    public function exec(object $compile): bool
    {
        $job = $this->dao->select('t1.id,t1.name,t1.repo,t1.engine,t1.product,t1.pipeline,t2.name as jenkinsName,t2.url,t2.account,t2.token,t2.password,t1.triggerType,t1.customParam,t1.server,t1.lastTag')
            ->from(TABLE_JOB)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.server=t2.id')
            ->where('t1.id')->eq($compile->job)
            ->fetch();
        if(!$job) return false;

        $compileID = $compile->id;
        $repo      = $this->loadModel('repo')->getByID($job->repo);

        $this->loadModel('job');
        if(!empty($compile->tag))
        {
            $job->lastTag = $compile->tag;
            $this->job->updateLastTag($job->id, $compile->tag);

            $this->dao->update(TABLE_COMPILE)->set('tag')->eq($compile->tag)->where('id')->eq($compile->id)->exec();
        }

        $method = 'exec' . ucfirst($job->engine) . 'Pipeline';
        if(!method_exists($this->job, $method)) return false;

        $result = $this->job->$method($job, $repo, $compileID);
        if(dao::isError()) dao::getError();

        $result->updateDate = helper::now();
        $this->dao->update(TABLE_COMPILE)->data($result)->where('id')->eq($compileID)->exec();
        $this->dao->update(TABLE_JOB)
            ->set('lastStatus')->eq($result->status)
            ->set('lastExec')->eq($compile->updateDate ? $compile->updateDate : helper::now())
            ->where('id')->eq($job->id)
            ->exec();

        return !dao::isError();
    }
}

<?php
declare(strict_types=1);
/**
 * The model file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     ci
 * @link        https://www.zentao.net
 */
class ciModel extends model
{
    /**
     * Set menu.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function setMenu(int $repoID = 0)
    {
        if($repoID) $this->session->set('repoID', $repoID);

        $homeMenuModule = array('gitlab', 'gogs', 'gitea', 'jenkins', 'sonarqube');
        if(!in_array("{$this->app->moduleName}", $homeMenuModule)) common::setMenuVars('devops', (int)$this->session->repoID);

        if($this->session->repoID)
        {
            $repo = $this->loadModel('repo')->getByID($this->session->repoID);
            if(!empty($repo) && !in_array(strtolower($repo->SCM), $this->config->repo->gitServiceList)) unset($this->lang->devops->menu->mr);
            if(!$repo || !in_array($repo->SCM, $this->config->repo->notSyncSCM)) unset($this->lang->devops->menu->tag);
            if(!$repo || !in_array($repo->SCM, $this->config->repo->notSyncSCM)) unset($this->lang->devops->menu->branch);
        }
    }

    /**
     * 向jenkins发送请求以检查构建状态。
     * Send a request to jenkins to check build status.
     *
     * @param  int    $compileID
     * @access public
     * @return bool
     */
    public function checkCompileStatus(int $compileID = 0): bool
    {
        $compiles = $this->dao->select('compile.*, job.engine,job.pipeline, pipeline.name as jenkinsName,job.server,pipeline.url,pipeline.account,pipeline.token,pipeline.password')
            ->from(TABLE_COMPILE)->alias('compile')
            ->leftJoin(TABLE_JOB)->alias('job')->on('compile.job=job.id')
            ->leftJoin(TABLE_PIPELINE)->alias('pipeline')->on('job.server=pipeline.id')
            ->where('compile.status')->notIN('success, failure, create_fail, timeout, canceled')
            ->andWhere('compile.createdDate')->gt(date(DT_DATETIME1, strtotime("-1 day")))
            ->beginIf($compileID)->andWhere('compile.id')->eq($compileID)->fi()
            ->fetchAll();

        $notCompileMR = $this->dao->select('jobID,id')
            ->from(TABLE_MR)
            ->where('jobID')->gt(0)
            ->andWhere('compileStatus')->eq('created')
            ->fetchPairs();

        foreach($compiles as $compile)
        {
            $MRID = zget($notCompileMR, $compile->job, 0);
            $this->syncCompileStatus($compile, $MRID);
        }
        return !dao::isError();
    }

    /**
     * 根据编译ID获取编译信息。
     * Get compile by ID.
     *
     * @param  int    $compileID
     * @access public
     * @return object|false
     */
    public function getCompileByID(int $compileID): object|false
    {
        return $this->dao->select('t1.*, t2.pipeline,t2.product,t2.frame,t2.server,t3.name as jenkinsName,t3.url,t3.account,t3.token,t3.password')->from(TABLE_COMPILE)->alias('t1')
            ->leftJoin(TABLE_JOB)->alias('t2')->on('t1.job=t2.id')
            ->leftJoin(TABLE_PIPELINE)->alias('t3')->on('t2.server=t3.id')
            ->where('t1.id')->eq($compileID)
            ->fetch();
    }

    /**
     * 保存编译信息到数据库。
     * Save compile info to database.
     *
     * @param  string $response
     * @param  object $compile
     * @param  string $userPWD
     * @param  string $jenkinsServer
     * @access public
     * @return bool
     */
    public function saveCompile(string $response, object $compile, string $userPWD, string $jenkinsServer): bool
    {
        if(strripos($response, "404") > -1)
        {
            $jenkinsServer = strpos($compile->pipeline, '/job/') === 0 ? $jenkinsServer . $compile->pipeline : $jenkinsServer . '/job/' . $compile->pipeline;
            $infoUrl       = sprintf("%s/api/xml?tree=builds[id,number,result,queueId]&xpath=//build[queueId=%s]", $jenkinsServer, $compile->queue);
            $response      = common::http($infoUrl, '', array(CURLOPT_USERPWD => $userPWD));
            if($response && strpos($response, "<") === 0)
            {
                $buildInfo = simplexml_load_string($response);
                if(empty($buildInfo)) return false;

                $buildNumber = strtolower($buildInfo->number->__toString());
                if(empty($buildNumber)) return false;

                $result = strtolower($buildInfo->result->__toString());
                if(empty($result)) return false;
                $this->updateBuildStatus($compile, $result);

                $logUrl   = sprintf('%s/%s/consoleText', $jenkinsServer, $buildNumber);
                $response = common::http($logUrl, '', array(CURLOPT_USERPWD => $userPWD));
                $this->dao->update(TABLE_COMPILE)->set('logs')->eq($response)->where('id')->eq($compile->id)->exec();
            }
        }
        else
        {
            $queueInfo = @json_decode($response);
            $buildInfo = null;
            if($queueInfo && !empty($queueInfo->executable))
            {
                $buildUrl  = $queueInfo->executable->url . 'api/json?pretty=true';
                $response  = common::http($buildUrl, '', array(CURLOPT_USERPWD => $userPWD));
                $buildInfo = json_decode($response);
            }
            elseif(stripos($response, 'not found') !== false)
            {
                $job      = strpos($compile->pipeline, '/job/') !== false ? $compile->pipeline : '/job/' . $compile->pipeline;
                $buildUrl = "{$compile->url}{$job}/api/json?depth=1";
                $response = json_decode(common::http($buildUrl, '', array(CURLOPT_USERPWD => $userPWD)));
                if($response)
                {
                    foreach($response->builds as $build)
                    {
                        if($build->queueId == $compile->queue)
                        {
                            $buildInfo = $build;
                            break;
                        }
                    }
                }
            }

            if($buildInfo)
            {
                if(!empty($buildInfo->building))
                {
                    $this->updateBuildStatus($compile, 'building');
                }
                else
                {
                    if(empty($buildInfo->result)) return false;

                    $result = strtolower($buildInfo->result);
                    $this->updateBuildStatus($compile, $result);

                    $logUrl   = $buildInfo->url . 'logText/progressiveText/api/json';
                    $response = common::http($logUrl, '', array(CURLOPT_USERPWD => $userPWD));
                    $this->dao->update(TABLE_COMPILE)->set('logs')->eq($response)->where('id')->eq($compile->id)->exec();
                }
            }
        }

        return !dao::isError();
    }

    /**
     * 同步构建状态。
     * Sync compile status.
     *
     * @param  object $compile
     * @param  int    $MRID
     * @access public
     * @return bool
     */
    public function syncCompileStatus(object $compile, int $MRID = 0): bool
    {
        /* Max retry times is: 5. */
        if($compile->times >= 5)
        {
            $this->updateBuildStatus($compile, 'failure');

            /* Added merge request result push to xuanxuan. */
            if($MRID) $this->loadModel('message')->send('mr', $MRID, 'compilefail', 0);
            return false;
        }

        if($compile->engine == 'gitlab') return $this->syncGitlabTaskStatus($compile);

        $jenkinsServer   = $compile->url;
        $jenkinsPassword = $compile->token ? $compile->token : base64_decode($compile->password);
        $userPWD         = "{$compile->account}:{$jenkinsPassword}";
        $queueUrl        = sprintf('%s/queue/item/%s/api/json', $jenkinsServer, $compile->queue);

        $response = common::http($queueUrl, '', array(CURLOPT_USERPWD => $userPWD));
        $result   = '';

        if($this->app->rawModule != 'mr') $this->dao->update(TABLE_COMPILE)->set('times = times + 1')->where('id')->eq($compile->id)->exec();
        $this->saveCompile($response, $compile, $userPWD, $jenkinsServer);

        if($MRID && in_array($result, array('success', 'failure')))
        {
            $actionType = $result == 'success' ? 'compilepass' : 'compilefail';
            $this->loadModel('message')->send('mr', $MRID, $actionType, 0);
        }

        return !dao::isError();
    }

    /**
     * 同步gitlab任务状态。
     * Sync gitlab task status.
     *
     * @param  object $compile
     * @access public
     * @return bool
     */
    public function syncGitlabTaskStatus(object $compile): bool
    {
        /* The value of `$compile->pipeline` is like `'{"project":"46","reference":"master"}'` in current design. */
        $pipeline = json_decode($compile->pipeline);
        $compile->project = isset($pipeline->project) ? (int)$pipeline->project : (int)$compile->pipeline;

        $now      = helper::now();
        $pipeline = $this->loadModel('gitlab')->apiGetSinglePipeline($compile->server, $compile->project, $compile->queue);
        if(!isset($pipeline->id) || isset($pipeline->message)) /* The pipeline is not available. */
        {
            $this->dao->update(TABLE_JOB)->set('lastExec')->eq($now)->set('lastStatus')->eq('create_fail')->where('id')->eq($compile->job)->exec();
            return false;
        }

        $jobs = $this->gitlab->apiGetJobs($compile->server, $compile->project, $compile->queue);
        $data = new stdclass;
        $data->status     = $pipeline->status;
        $data->updateDate = $now;
        $data->logs       = '';

        foreach($jobs as $job)
        {
            if(empty($job->duration) || $job->duration == '') $job->duration = '-';
            $data->logs .= "<font style='font-weight:bold'>&gt;&gt;&gt; Job: $job->name, Stage: $job->stage, Status: $job->status, Duration: $job->duration Sec\r\n </font>";
            $data->logs .= "Job URL: <a href=\"$job->web_url\" target='_blank'>$job->web_url</a> \r\n";
            $data->logs .= $this->transformAnsiToHtml($this->gitlab->apiGetJobLog($compile->server, $compile->project, $job->id));
        }

        $this->dao->update(TABLE_COMPILE)->data($data)->where('id')->eq($compile->id)->exec();
        $this->dao->update(TABLE_JOB)->set('lastExec')->eq($now)->set('lastStatus')->eq($pipeline->status)->where('id')->eq($compile->job)->exec();

        /* Send mr message by compile status. */
        $relateMR = $this->dao->select('*')->from(TABLE_MR)->where('compileID')->eq($compile->id)->fetch();
        if($relateMR)
        {
            if($data->status == 'success') $this->loadModel('action')->create('mr', $relateMR->id, 'compilePass');
            if($data->status == 'failed')  $this->loadModel('action')->create('mr', $relateMR->id, 'compileFail');
        }

        return !dao::isError();
    }

    /**
     * 把ansi文本转换成html样式。
     * Transform ansi text to html.
     *
     * @param  string $text
     * @access public
     * @return string
     */
    public function transformAnsiToHtml(string $text): string
    {
        $text = preg_replace("/\x1B\[31;40m/", '<font style="color: red">',       $text);
        $text = preg_replace("/\x1B\[32;1m/",  '<font style="color: green">',     $text);
        $text = preg_replace("/\x1B\[32;1m/",  '<font style="color: green">',     $text);
        $text = preg_replace("/\x1B\[36;1m/",  '<font style="color: cyan">',      $text);
        $text = preg_replace("/\x1B\[0;33m/",  '<font style="color: yellow">',    $text);
        $text = preg_replace("/\x1B\[1m/",     '<font style="font-weight:bold">', $text);
        $text = preg_replace("/\x1B\[0;m/",    '</font><br>', $text);
        return preg_replace("/\x1B\[0K/",     '<br>', $text);
    }

    /**
     * 更新流水线构建状态。
     * Update ci build status.
     *
     * @param  object $build
     * @param  string $status
     * @access public
     * @return bool
     */
    public function updateBuildStatus(object $build, string $status): bool
    {
        $this->dao->update(TABLE_COMPILE)->set('status')->eq($status)->set('updateDate')->eq(helper::now())->where('id')->eq($build->id)->exec();
        $this->dao->update(TABLE_JOB)->set('lastExec')->eq(helper::now())->set('lastStatus')->eq($status)->where('id')->eq($build->job)->exec();
        if($status == 'building') return false;

        $relateMR = $this->dao->select('*')->from(TABLE_MR)->where('compileID')->eq($build->id)->fetch();
        if(empty($relateMR)) return false;

        if($status != 'success')
        {
            $newMR = new stdclass();
            $newMR->status        = 'closed';
            $newMR->mergeStatus   = 'cannot_merge_by_fail';
            $newMR->compileStatus = $status;

            $this->dao->update(TABLE_MR)->data($newMR)->where('id')->eq($relateMR->id)->exec();
        }
        elseif(isset($relateMR->synced) && $relateMR->synced == '0')
        {
            $rawMR = $this->loadModel('mr')->apiCreateMR($relateMR->hostID, $relateMR);
            if(!empty($rawMR->iid))
            {
                $newMR = new stdclass();
                $newMR->mriid     = $rawMR->iid;
                $newMR->status    = $rawMR->state;
                $newMR->synced    = '1';
                $this->dao->update(TABLE_MR)->data($newMR)->where('id')->eq($relateMR->id)->exec();
                $this->mr->linkObjects($relateMR);
            }
        }

        return !dao::isError();
    }

    /**
     * 发起一个请求。
     * Send request.
     *
     * @param  string $url
     * @param  object $data
     * @param  string $userPWD
     * @access public
     * @return string|int
     */
    public function sendRequest(string $url, object $data, string $userPWD = ''): string|int
    {
        if(!empty($data->PARAM_TAG)) $data->PARAM_REVISION = '';
        $response = common::http($url, $data, array(CURLOPT_HEADER => true, CURLOPT_USERPWD => $userPWD));

        if(preg_match("!Location: .*item/(.*)/!i", $response, $matches)) return $matches[1];
        return 0;
    }

    /**
     * 根据ztf结果更新测试单。
     * Save test task for ztf.
     *
     * @param  string $testType  unit|func
     * @param  int    $productID
     * @param  int    $compileID
     * @param  int    $taskID
     * @param  string $name
     * @access public
     * @return int|bool
     */
    public function saveTestTaskForZtf(string $testType = 'unit', int $productID = 0, int $compileID = 0, int $taskID = 0, string $name = ''): int|bool
    {
        $this->loadModel('testtask');
        if(!empty($taskID))
        {
            $testtask = $this->testtask->getByID($taskID);
            if(!$testtask) return false;

            $this->dao->update(TABLE_TESTTASK)->set('auto')->eq(strtolower($testType))->where('id')->eq($taskID)->exec();
        }
        else
        {
            if(empty($productID)) return false;

            $lastProject = $this->dao->select('t2.id,t2.project')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->eq($productID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t2.project')->ne('0')
                ->orderBy('t2.id desc')
                ->limit(1)
                ->fetch();

            $testtask = new stdclass();
            $testtask->product     = $productID;
            $testtask->name        = !empty($name) ? $name : sprintf($this->lang->testtask->titleOfAuto, date('Y-m-d H:i:s'));
            $testtask->owner       = $this->app->user->account;
            $testtask->project     = $lastProject ? $lastProject->project : 0;
            $testtask->execution   = $lastProject ? $lastProject->id : 0;
            $testtask->build       = 'trunk';
            $testtask->auto        = strtolower($testType);
            $testtask->begin       = date('Y-m-d');
            $testtask->end         = date('Y-m-d', time() + 24 * 3600);
            $testtask->status      = 'done';
            $testtask->createdBy   = $this->app->user->account;
            $testtask->createdDate = helper::now();

            $this->dao->insert(TABLE_TESTTASK)->data($testtask)->exec();
            $taskID = $this->dao->lastInsertId();
            $this->loadModel('action')->create('testtask', $taskID, 'opened');
        }

        if($compileID) $this->dao->update(TABLE_COMPILE)->set('testtask')->eq($taskID)->where('id')->eq($compileID)->exec();
        if(dao::isError()) return dao::isError();
        return $taskID;
    }
}

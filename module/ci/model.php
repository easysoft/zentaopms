<?php
/**
 * The model file of ci module of ZenTaoPMS.
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
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
    public function setMenu($repoID = 0)
    {
        if($repoID)
        {
            if(!session_id()) session_start();
            $this->session->set('repoID', $repoID);
            session_write_close();
        }
        $homeMenuModule = array('gitlab', 'gogs', 'gitea', 'jenkins', 'sonarqube');
        if(!in_array("{$this->app->moduleName}", $homeMenuModule)) common::setMenuVars('devops', $this->session->repoID);

        if($this->session->repoID)
        {
            $repo = $this->loadModel('repo')->getByID($this->session->repoID);
            if(!empty($repo) and !in_array(strtolower($repo->SCM), $this->config->repo->gitServiceList)) unset($this->lang->devops->menu->mr);

            $tab   = $this->app->tab;
            $repos = $this->repo->getRepoPairs($tab);
        }
    }

    /**
     * Send a request to jenkins to check build status.
     *
     * @param  int    $compileID
     * @access public
     * @return void
     */
    public function checkCompileStatus($compileID = 0)
    {
        $compiles = $this->dao->select('compile.*, job.engine,job.pipeline, pipeline.name as jenkinsName,job.server,pipeline.url,pipeline.account,pipeline.token,pipeline.password')
            ->from(TABLE_COMPILE)->alias('compile')
            ->leftJoin(TABLE_JOB)->alias('job')->on('compile.job=job.id')
            ->leftJoin(TABLE_PIPELINE)->alias('pipeline')->on('job.server=pipeline.id')
            ->where('compile.status')->ne('success')
            ->andWhere('compile.status')->ne('failure')
            ->andWhere('compile.status')->ne('create_fail')
            ->andWhere('compile.status')->ne('timeout')
            ->andWhere('compile.status')->ne('canceled')
            ->beginIf($compileID)->andWhere('compile.id')->eq($compileID)->fi()
            ->andWhere('compile.createdDate')->gt(date(DT_DATETIME1, strtotime("-1 day")))
            ->fetchAll();

        $notCompileMR = $this->dao->select('id,jobID')
            ->from(TABLE_MR)
            ->where('jobID')->gt(0)
            ->andWhere('compileStatus')->eq('created')
            ->fetchPairs();

        foreach($compiles as $compile) $this->syncCompileStatus($compile, $notCompileMR);
    }

    /**
     * Sync compile status.
     *
     * @param  object $compile
     * @param  array  $notCompileMR
     * @access public
     * @return void
     */
    public function syncCompileStatus($compile, $notCompileMR = array())
    {
        $MRID = array_search($compile->job, $notCompileMR);

        /* Max retry times is: 3. */
        if($compile->times >= 3)
        {
            $this->updateBuildStatus($compile, 'failure');

            /* Added merge request result push to xuanxuan. */
            if($MRID) $this->loadModel('message')->send('mr', $MRID, 'compilefail', 0);
            return false;
        }

        if($compile->engine == 'gitlab') return $this->syncGitlabTaskStatus($compile);
        $jenkinsServer   = $compile->url;
        $jenkinsUser     = $compile->account;
        $jenkinsPassword = $compile->token ? $compile->token : base64_decode($compile->password);
        $userPWD         = "$jenkinsUser:$jenkinsPassword";
        $queueUrl        = sprintf('%s/queue/item/%s/api/json', $jenkinsServer, $compile->queue);

        $response = common::http($queueUrl, '', array(CURLOPT_USERPWD => $userPWD));
        $result   = '';

        if($compile->engine != 'gitlab') $this->dao->update(TABLE_COMPILE)->set('times = times + 1')->where('id')->eq($compile->id)->exec();
        if(strripos($response, "404") > -1)
        {
            $jenkinsServer = strpos($compile->pipeline, '/job/') === 0 ? $jenkinsServer . $compile->pipeline : $jenkinsServer . '/job/' . $compile->pipeline;
            $infoUrl       = sprintf("%s/api/xml?tree=builds[id,number,result,queueId]&xpath=//build[queueId=%s]", $jenkinsServer, $compile->queue);
            $response      = common::http($infoUrl, '', array(CURLOPT_USERPWD => $userPWD));
            if($response)
            {
                $buildInfo   = simplexml_load_string($response);
                $buildNumber = strtolower($buildInfo->number);
                if(empty($buildNumber)) return false;

                $result = strtolower($buildInfo->result);
                if(empty($result)) return false;
                $this->updateBuildStatus($compile, $result);

                $logUrl   = sprintf('%s/%s/consoleText', $jenkinsServer, $buildNumber);
                $response = common::http($logUrl, '', array(CURLOPT_USERPWD => $userPWD));
                $this->dao->update(TABLE_COMPILE)->set('logs')->eq($response)->where('id')->eq($compile->id)->exec();
            }
        }
        else
        {
            $queueInfo = json_decode($response);
            if(!empty($queueInfo->executable))
            {
                $buildUrl  = $queueInfo->executable->url . 'api/json?pretty=true';
                $response  = common::http($buildUrl, '', array(CURLOPT_USERPWD => $userPWD));
                $buildInfo = json_decode($response);

                if($buildInfo->building)
                {
                    $this->updateBuildStatus($compile, 'building');
                }
                else
                {
                    $result = strtolower($buildInfo->result);
                    if(empty($result)) return false;
                    $this->updateBuildStatus($compile, $result);

                    $logUrl   = $buildInfo->url . 'logText/progressiveText/api/json';
                    $response = common::http($logUrl, '', array(CURLOPT_USERPWD => $userPWD));
                    $this->dao->update(TABLE_COMPILE)->set('logs')->eq($response)->where('id')->eq($compile->id)->exec();
                }
            }
        }

        if($MRID && in_array($result, array('success', 'failure')))
        {
            $actionType = $result == 'success' ? 'compilepass' : 'compilefail';
            $this->loadModel('message')->send('mr', $MRID, $actionType, 0);
        }
    }

    /**
     * Sync gitlab task status.
     *
     * @param  object    $compile
     * @access public
     * @return void
     */
    public function syncGitlabTaskStatus($compile)
    {
        $this->loadModel('gitlab');

        $now = helper::now();

        /* The value of `$compile->pipeline` is like `'{"project":"46","reference":"master"}'` in current design. */
        $pipeline = json_decode($compile->pipeline);
        $compile->project = isset($pipeline->project) ? $pipeline->project : $compile->pipeline;

        $pipeline = $this->gitlab->apiGetSinglePipeline($compile->server, $compile->project, $compile->queue);
        if(!isset($pipeline->id) or isset($pipeline->message)) /* The pipeline is not available. */
        {
            $pipeline->status = 'create_fail'; /* Set the status to fail. */
            $this->dao->update(TABLE_JOB)->set('lastExec')->eq($now)->set('lastStatus')->eq($pipeline->status)->where('id')->eq($compile->job)->exec();
            return false;
        }

        $jobs = $this->gitlab->apiGetJobs($compile->server, $compile->project, $compile->queue);
        $data = new stdclass;
        $data->status     = $pipeline->status;
        $data->updateDate = $now;
        $data->logs       = '';

        foreach($jobs as $job)
        {
            if(empty($job->duration) or $job->duration == '') $job->duration = '-';
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
    }

    /**
     * Transform ansi text to html.
     *
     * @param  string $text
     * @access public
     * @return string
     */
    public function transformAnsiToHtml($text)
    {
        $text = preg_replace("/\x1B\[31;40m/", '<font style="color: red">',       $text);
        $text = preg_replace("/\x1B\[32;1m/",  '<font style="color: green">',     $text);
        $text = preg_replace("/\x1B\[32;1m/",  '<font style="color: green">',     $text);
        $text = preg_replace("/\x1B\[36;1m/",  '<font style="color: cyan">',      $text);
        $text = preg_replace("/\x1B\[0;33m/",  '<font style="color: yellow">',    $text);
        $text = preg_replace("/\x1B\[1m/",     '<font style="font-weight:bold">', $text);
        $text = preg_replace("/\x1B\[0;m/",    '</font><br>', $text);
        $text = preg_replace("/\x1B\[0K/",     '<br>', $text);
        return $text;
    }

    /**
     * Update ci build status.
     *
     * @param  object $build
     * @param  string $status
     * @access public
     * @return void
     */
    public function updateBuildStatus($build, $status)
    {
        $this->dao->update(TABLE_COMPILE)->set('status')->eq($status)->where('id')->eq($build->id)->exec();
        $this->dao->update(TABLE_JOB)->set('lastExec')->eq(helper::now())->set('lastStatus')->eq($status)->where('id')->eq($build->job)->exec();

        if($status == 'building') return;

        $relateMR = $this->dao->select('*')->from(TABLE_MR)->where('compileID')->eq($build->id)->fetch();
        if(empty($relateMR)) return;

        if(isset($relateMR->synced) and $relateMR->synced == '0' and $status == 'success')
        {
            $newMR = new stdclass();
            $newMR->mergeStatus   = 'can_be_merged';
            $newMR->compileStatus = $status;

            /* Create a gitlab mr. */
            $MRObject                       = new stdclass();
            $MRObject->target_project_id    = $relateMR->targetProject;
            $MRObject->source_branch        = $relateMR->sourceBranch;
            $MRObject->target_branch        = $relateMR->targetBranch;
            $MRObject->title                = $relateMR->title;
            $MRObject->description          = $relateMR->description;
            $MRObject->remove_source_branch = $relateMR->removeSourceBranch == '1' ? true : false;
            if($relateMR->assignee)
            {
                $gitlabAssignee = $this->gitlab->getUserIDByZentaoAccount($relateMR->gitlabID, $relateMR->assignee);
                if($gitlabAssignee) $MRObject->assignee_ids = $gitlabAssignee;
            }

            $rawMR = $this->loadModel('mr')->apiCreateMR($relateMR->gitlabID, $relateMR->sourceProject, $MRObject);

            /**
            * Another open merge request already exists for this source branch.
            * The type of variable `$rawMR->message` is array.
            */
            if(isset($rawMR->message) and !isset($rawMR->iid))
            {
                $errorMessage = $rawMR->message;
                $rawMR        = $this->mr->apiGetSameOpened($relateMR->gitlabID, $relateMR->sourceProject, $MRObject->source_branch, $MRObject->target_project_id, $MRObject->target_branch);
                if(empty($rawMR) or !isset($rawMR->iid))
                {
                    $errorMessage     = $this->mr->convertApiError($errorMessage);
                    $newMR->syncError = sprintf($this->lang->mr->apiError->createMR, $errorMessage);
                }
            }
            elseif(!isset($rawMR->iid))
            {
                $newMR->syncError = $this->lang->mr->createFailedFromAPI;
            }

            if(!empty($rawMR->iid))
            {
                $newMR->mriid     = $rawMR->iid;
                $newMR->status    = $rawMR->state;
                $newMR->synced    = '1';
                $newMR->syncError = '';
            }

            $this->dao->update(TABLE_MR)->data($newMR)->where('id')->eq($relateMR->id)->exec();
            $this->mr->linkObjects($relateMR);
        }
        elseif($status != 'success')
        {
            $newMR = new stdclass();
            $newMR->status        = 'closed';
            $newMR->mergeStatus   = 'cannot_merge_by_fail';
            $newMR->compileStatus = $status;

            $this->dao->update(TABLE_MR)->data($newMR)->where('id')->eq($relateMR->id)->exec();
        }
    }

    /**
     * Send request.
     *
     * @param  string $url
     * @param  object $data
     * @param  string $userPWD
     * @access public
     * @return int
     */
    public function sendRequest($url, $data, $userPWD = '')
    {
        if(!empty($data->PARAM_TAG)) $data->PARAM_REVISION = '';
        $response = common::http($url, $data, array(CURLOPT_HEADER => true, CURLOPT_USERPWD => $userPWD));
        if(preg_match("!Location: .*item/(.*)/!", $response, $matches)) return $matches[1];
        return 0;
    }
}

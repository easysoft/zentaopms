<?php
/**
 * The model file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class ciModel extends model
{

    /**
     * Get ci job list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $decode
     * @access public
     * @return array
     */
    public function listJob($orderBy = 'id_desc', $pager = null, $decode = true)
    {
        $list = $this->dao->
        select('t1.*, t2.name repoName, t3.name as jenkinsName')->from(TABLE_CI_JOB)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.repo=t2.id')
            ->leftJoin(TABLE_JENKINS)->alias('t3')->on('t1.jenkins=t3.id')
            ->where('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $list;
    }

    /**
     * Get a ci job by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getJobByID($id)
    {
        $jenkins = $this->dao->select('*')->from(TABLE_CI_JOB)->where('id')->eq($id)->fetch();
        return $jenkins;
    }

    /**
     * Create a ci job.
     *
     * @access public
     * @return bool
     */
    public function createJob()
    {
        $job = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();
        $arr = explode("-", $job->repo);
        $job->repo = $arr[0];
        $repoType = $arr[1];

        $this->dao->insert(TABLE_CI_JOB)->data($job)
            ->batchCheck($this->config->job->create->requiredFields, 'notempty')

            ->batchCheckIF($job->triggerType === 'schedule' && $job->scheduleType == 'cron', "cronExpression", 'notempty')
            ->batchCheckIF($job->triggerType === 'schedule' && $job->scheduleType == 'custom', "scheduleDay,scheduleTime,scheduleInterval", 'notempty')
            ->batchCheckIF($repoType == 'Subversion', "svnFolder", 'notempty')

            ->autoCheck()
            ->exec();

        if ($job->triggerType === 'schedule') {
            $jobId = $this->dao->lastInsertID();

            $arr = explode(":", $job->scheduleTime);
            $hour = $arr[0];
            $min = $arr[1];

            if ($job->scheduleDay == 'everyDay') {
                $days = '1-7';
            } else if ($job->scheduleDay == 'workDay') {
                $days = '1-5';
            }

            $cron = (object)array('m' => $min, 'h' => $hour, 'dom' => '*', 'mon' => '*',
                'dow' => $days . '/' . $job->scheduleInterval, 'command' => 'moduleName=ci&methodName=exeJob&parm=' . $jobId,
                'remark' => ($this->lang->ci->extJob . $jobId), 'type' => 'zentao',
                'buildin' => '-1', 'status' => 'normal', 'lastTime' => '0000-00-00 00:00:00');
            $this->dao->insert(TABLE_CRON)->data($cron)->exec();
        }

        return true;
    }

    /**
     * Update a ci job.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function updateJob($id)
    {
        $job = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();
        $arr = explode("-", $job->repo);
        $job->repo = $arr[0];
        $repoType = $arr[1];

        $this->dao->update(TABLE_CI_JOB)->data($job)
            ->batchCheck($this->config->job->edit->requiredFields, 'notempty')

            ->batchCheckIF($job->triggerType === 'schedule' && $job->scheduleType == 'cron', "cronExpression", 'notempty')
            ->batchCheckIF($job->triggerType === 'schedule' && $job->scheduleType == 'custom', "scheduleDay,scheduleTime,scheduleInterval", 'notempty')
            ->batchCheckIF($repoType == 'Subversion', "svnFolder", 'notempty')

            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();

        if ($job->triggerType === 'schedule') {
            $command = 'moduleName=ci&methodName=exeJob&parm=' . $id;

            $arr = explode(":", $job->scheduleTime);
            $hour = $arr[0];
            $min = $arr[1];

            $jobId = $this->dao->lastInsertID();
            if ($job->scheduleDay == 'everyDay') {
                $days = '1-7';
            } else if ($job->scheduleDay == 'workDay') {
                $days = '2-6';
            }

            $this->dao->update(TABLE_CRON)
                ->set('m')->eq($min)
                ->set('h')->eq($hour)
                ->set('dom')->eq('*')
                ->set('mon')->eq('*')
                ->set('dow')->eq($days . '/' . $job->scheduleInterval)
                ->set('lastTime')->eq('0000-00-00 00:00:00')
                ->where('command')->eq($command)->exec();
        }

        return true;
    }

    /**
     * Execute ci job.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function exeJob($jobID)
    {
        $po = $this->dao->select('job.id jobId, job.name jobName, job.repo, job.jenkinsJob, jenkins.name jenkinsName,jenkins.serviceUrl,jenkins.account,jenkins.token,jenkins.password')
            ->from(TABLE_CI_JOB)->alias('job')
            ->leftJoin(TABLE_JENKINS)->alias('jenkins')->on('job.jenkins=jenkins.id')
            ->where('job.id')->eq($jobID)
            ->fetch();

        if (!$po) {
            return false;
        }

        $jenkinsServer = $po->serviceUrl;
        $jenkinsUser = $po->account;
        $jenkinsTokenOrPassword = $po->token ? $po->token : base64_decode($po->password);

        $r = '://' . $jenkinsUser . ':' . $jenkinsTokenOrPassword . '@';
        $jenkinsServer = str_replace('://', $r, $jenkinsServer);
        $buildUrl = sprintf('%s/job/%s/build/api/json', $jenkinsServer, $po->jenkinsJob);

        $po->queueItem = $this->sendBuildRequest($buildUrl);
        $this->saveBuild($po);

        return !dao::isError();
    }

    /**
     * Get jenkins build list.
     *
     * @param  int $jobID
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $decode
     * @access public
     * @return array
     */
    public function listBuild($jobID, $orderBy = 'id_desc', $pager = null, $decode = true)
    {
        $list = $this->dao->
        select('build.id, build.name, build.status, build.createdDate, job.triggerType, repo.name repoName, jenkins.name as jenkinsName')
            ->from(TABLE_CI_BUILD)->alias('build')
            ->leftJoin(TABLE_CI_JOB)->alias('job')->on('build.cijob=job.id')
            ->leftJoin(TABLE_REPO)->alias('repo')->on('job.repo=repo.id')
            ->leftJoin(TABLE_JENKINS)->alias('jenkins')->on('job.jenkins=jenkins.id')

            ->where('build.deleted')->eq('0')
            ->andWhere('build.cijob')->eq($jobID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $list;
    }

    /**
     * Get jenkins build logs.
     *
     * @param  int $buildID
     * @access public
     * @return array
     */
    public function getBuildByID($buildID)
    {
        $build = $this->dao->select('*')->from(TABLE_CI_BUILD)->where('id')->eq($buildID)->fetch();
        return $build;
    }

    /**
     * Save build to db.
     *
     * @param  object $job
     * @access public
     * @return bool
     */
    public function saveBuild($job)
    {
        $build = new stdClass();
        $build->cijob = $job->jobId;
        $build->name = $job->jobName;
        $build->queueItem = $job->queueItem;
        $build->status = $job->queueItem ? 'created' : 'create_fail';
        $build->createdBy = $this->app->user->account;
        $build->createdDate = helper::now();

        $this->dao->insert(TABLE_CI_BUILD)->data($build)->exec();
    }

    /**
     * Send a request to jenkins to check build status.
     *
     * @access public
     * @return bool
     */
    public function checkBuildStatus()
    {
        $pos = $this->dao->select('build.*, job.jenkinsJob, jenkins.name jenkinsName,jenkins.serviceUrl,jenkins.account,jenkins.token,jenkins.password')
            ->from(TABLE_CI_BUILD)->alias('build')
            ->leftJoin(TABLE_CI_JOB)->alias('job')->on('build.cijob=job.id')
            ->leftJoin(TABLE_JENKINS)->alias('jenkins')->on('job.jenkins=jenkins.id')
            ->where('build.status')->ne('success')
            ->andWhere('build.status')->ne('fail')
            ->andWhere('build.status')->ne('timeout')
            ->andWhere('build.createdDate')->gt(date(DT_DATETIME1, strtotime("-1 day")))
            ->fetchAll();

        foreach($pos as $po) {
            $jenkinsServer = $po->serviceUrl;
            $jenkinsUser = $po->account;
            $jenkinsTokenOrPassword = $po->token ? $po->token : base64_decode($po->password);

            $r = '://' . $jenkinsUser . ':' . $jenkinsTokenOrPassword . '@';
            $jenkinsServer = str_replace('://', $r, $jenkinsServer);
            $queueUrl = sprintf('%s/queue/item/%s/api/json', $jenkinsServer, $po->queueItem);

            $response = common::http($queueUrl);
            if (strripos($response,"404") > -1) { // queue expired, use another api
                $infoUrl = sprintf('%s/job/%s/%s/api/json', $jenkinsServer, $po->jenkinsJob, $po->queueItem);
                $response = common::http($infoUrl);
                $buildInfo = json_decode($response);
                $result = strtolower($buildInfo->result);
                $this->updateBuildStatus($po, $result);

                $logUrl = sprintf('%s/job/%s/%s/consoleText', $jenkinsServer, $po->jenkinsJob, $po->queueItem);
                $response = common::http($logUrl);
                $logs = json_decode($response);

                $this->dao->update(TABLE_CI_BUILD)->set('logs')->eq($response)->where('id')->eq($po->id)->exec();
            } else {
                $queueInfo = json_decode($response);

                if (!empty($queueInfo->executable)) {
                    $buildUrl = $queueInfo->executable->url . 'api/json?pretty=true';
                    $buildUrl = str_replace('://', $r, $buildUrl);

                    $response = common::http($buildUrl);
                    $buildInfo = json_decode($response);

                    if ($buildInfo->building) {
                        $this->updateBuildStatus($po, 'building');
                    } else {
                        $result = strtolower($buildInfo->result);
                        $this->updateBuildStatus($po, $result);

                        $logUrl = $buildInfo->url . 'logText/progressiveText/api/json';
                        $logUrl = str_replace('://', $r, $logUrl);

                        $response = common::http($logUrl);
                        $logs = json_decode($response);

                        $this->dao->update(TABLE_CI_BUILD)->set('logs')->eq($response)->where('id')->eq($po->id)->exec();
                    }
                }
            }
        }
    }

    /**
     * Update ci build status.
     *
     * @param  object $build
     * @param  string $status
     * @access public
     * @return bool
     */
    public function updateBuildStatus($build, $status)
    {
        $this->dao->update(TABLE_CI_BUILD)->set('status')->eq($status)->where('id')->eq($build->id)->exec();

        $this->dao->update(TABLE_CI_JOB)
            ->set('lastExec')->eq(helper::now())
            ->set('lastStatus')->eq($status)
            ->where('id')->eq($build->cijob)->exec();
    }

    /**
     * @param $url
     * @return false|mixed|string
     */
    public function sendBuildRequest($url) // not to use common http
    {
        if(!extension_loaded('curl')) return json_encode(array('result' => 'fail', 'message' => $this->lang->error->noCurlExt));

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Sae T OAuth2 v0.1');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);

        $headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);

        curl_setopt ($curl , CURLOPT_HEADER, 1 );

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, new stdClass());

        $response = curl_exec($curl);
        $errors   = curl_error($curl);
        curl_close($curl);

        if ( preg_match ( "!Location: .*item/(.*)/!", $response , $matches ) ) {
            return $matches[1];
        }

        return '';
    }
}

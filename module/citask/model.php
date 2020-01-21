<?php
/**
 * The model file of ci task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class citaskModel extends model
{
    /**
     * Get a ci task by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $jenkins = $this->dao->select('*')->from(TABLE_CI_TASK)->where('id')->eq($id)->fetch();
        return $jenkins;
    }

    /**
     * Get ci task list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $decode
     * @access public
     * @return array
     */
    public function listAll($orderBy = 'id_desc', $pager = null, $decode = true)
    {
        $list = $this->dao->
            select('t1.*, t2.name repoName, t3.name as jenkinsName')->from(TABLE_CI_TASK)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.repo=t2.id')
            ->leftJoin(TABLE_JENKINS)->alias('t3')->on('t1.jenkins=t3.id')
            ->where('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $list;
    }

    /**
     * Create a ci task.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $task = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();

        $this->dao->insert(TABLE_CI_TASK)->data($task)
            ->batchCheck($this->config->citask->requiredFields, 'notempty')

            ->batchCheckIF($task->triggerType === 'schedule' && $task->scheduleType == 'cron', "cronExpression", 'notempty')
            ->batchCheckIF($task->triggerType === 'schedule' && $task->scheduleType == 'custom', "scheduleDay,scheduleTime,scheduleInterval", 'notempty')

            ->autoCheck()
            ->exec();

        if ($task->triggerType === 'schedule') {
            $taskId = $this->dao->lastInsertID();

            if ($task->scheduleType == 'custom') {
                $arr = explode(":", $task->scheduleTime);
                $hour = $arr[0];
                $min = $arr[1];

                if ($task->scheduleDay == 'everyDay') {
                    $days = '1-7';
                } else if ($task->scheduleDay == 'workDay') {
                    $days = '1-5';
                }

                $cron = (object)array('m' => $min, 'h' => $hour, 'dom' => '*', 'mon' => '*',
                    'dow' => $days . '/' . $task->scheduleInterval, 'command' => 'moduleName=citask&methodName=exe&parm=' . $taskId,
                    'remark' => ($this->lang->citask->extTask . $taskId), 'type' => 'zentao',
                    'buildin' => '-1', 'status' => 'normal', 'lastTime' => '0000-00-00 00:00:00');
                $this->dao->insert(TABLE_CRON)->data($cron)->exec();
            } else if ($task->scheduleType == 'cron') {
                $arr = explode(' ', $task->cronExpression);
                if (count($arr) >= 6) {
                    $cron = (object)array('m' => $arr[1], 'h' => $arr[2], 'dom' => $arr[3], 'mon' => $arr[4],
                        'dow' => $arr[5], 'command' => 'moduleName=citask&methodName=exe&parm=' . $taskId,
                        'remark' => ($this->lang->citask->extTask . $taskId), 'type' => 'zentao',
                        'buildin' => '-1', 'status' => 'normal', 'lastTime' => '0000-00-00 00:00:00');
                    $this->dao->insert(TABLE_CRON)->data($cron)->exec();
                }
            }
        }

        return true;
    }

    /**
     * Update a ci task.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $task = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $this->dao->update(TABLE_CI_TASK)->data($task)
            ->batchCheck($this->config->citask->requiredFields, 'notempty')

            ->batchCheckIF($task->triggerType === 'schedule' && $task->scheduleType == 'cron', "cronExpression", 'notempty')
            ->batchCheckIF($task->triggerType === 'schedule' && $task->scheduleType == 'custom', "scheduleDay,scheduleTime,scheduleInterval", 'notempty')

            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();

        if ($task->triggerType === 'schedule') {
            $command = 'moduleName=citask&methodName=exe&parm=' . $id;

            if ($task->scheduleType == 'custom') {
                $arr = explode(":", $task->scheduleTime);
                $hour = $arr[0];
                $min = $arr[1];

                $taskId = $this->dao->lastInsertID();
                if ($task->scheduleDay == 'everyDay') {
                    $days = '1-7';
                } else if ($task->scheduleDay == 'workDay') {
                    $days = '2-6';
                }

                $this->dao->update(TABLE_CRON)
                    ->set('m')->eq($min)
                    ->set('h')->eq($hour)
                    ->set('dom')->eq('*')
                    ->set('mon')->eq('*')
                    ->set('dow')->eq($days . '/' . $task->scheduleInterval)
                    ->set('lastTime')->eq('0000-00-00 00:00:00')
                    ->where('command')->eq($command)->exec();
            } else if ($task->scheduleType == 'cron') {
                $arr = explode(' ', $task->cronExpression);
                if (count($arr) >= 6) {
                    $this->dao->update(TABLE_CRON)
                        ->set('m')->eq($arr[1])
                        ->set('h')->eq($arr[2])
                        ->set('dom')->eq($arr[3])
                        ->set('mon')->eq($arr[4])
                        ->set('dow')->eq($arr[5])
                        ->set('lastTime')->eq('0000-00-00 00:00:00')
                        ->where('command')->eq($command)->exec();
                }
            }
        }

        return true;
    }

    /**
     * Execute ci task.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function exe($taskID)
    {
        $po = $this->dao->select('task.id taskId, task.name taskName, task.repo, task.jenkinsTask, jenkins.name jenkinsName,jenkins.serviceUrl,jenkins.credentials')
            ->from(TABLE_CI_TASK)->alias('task')
            ->leftJoin(TABLE_JENKINS)->alias('jenkins')->on('task.jenkins=jenkins.id')
            ->where('task.id')->eq($taskID)
            ->fetch();

        $credentials = $this->loadModel('cicredentials')->getByID($po->credentials); // jenkins must use a token or account credentials
        if ($credentials->type === 'token') {
            $jenkinsTokenOrPassword = $credentials->token;
        } else if ($credentials->type === 'account') {
            $jenkinsTokenOrPassword = $credentials->password;
        }
        $jenkinsUser = $credentials->username;
        $jenkinsServer = $po->serviceUrl;

        $r = '://' . $jenkinsUser . ':' . $jenkinsTokenOrPassword . '@';
        $jenkinsServer = str_replace('://', $r, $jenkinsServer);
        $buildUrl = sprintf('%s/job/%s/build/api/json', $jenkinsServer, $po->jenkinsTask);

        $po->queueItem = $this->sendBuildRequest($buildUrl);
        $this->saveCibuild($po);

        return !dao::isError();
    }

    /**
     * Get jenkins build list.
     *
     * @param  int $taskID
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $decode
     * @access public
     * @return array
     */
    public function listBuild($taskID, $orderBy = 'id_desc', $pager = null, $decode = true)
    {
        $list = $this->dao->
        select('id, name, status, createdDate')->from(TABLE_CI_BUILD)
            ->where('deleted')->eq('0')
            ->andWhere('citask')->eq($taskID)
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
    public function getBuild($buildID)
    {
        $build = $this->dao->select('*')->from(TABLE_CI_BUILD)->where('id')->eq($buildID)->fetch();
        return $build;
    }

    /**
     * Save build to db.
     *
     * @param  object $task
     * @access public
     * @return bool
     */
    public function saveCibuild($task)
    {
        $build = new stdClass();
        $build->citask = $task->taskId;
        $build->name = $task->taskName;
        $build->queueItem = $task->queueItem;
        $build->status = 'created';
        $build->createdBy = $this->app->user->account;
        $build->createdDate = helper::now();

        $this->dao->insert(TABLE_CI_BUILD)->data($build)->exec();
    }

    /**
     * Update ci build status.
     *
     * @param  object $task
     * @access public
     * @return bool
     */
    public function updateCibuildStatus($build, $status)
    {
        $this->dao->update(TABLE_CI_BUILD)->set('status')->eq($status)->where('id')->eq($build->id)->exec();

        $this->dao->update(TABLE_CI_TASK)
            ->set('lastExec')->eq(helper::now())
            ->set('lastStatus')->eq($status)
            ->where('id')->eq($build->citask)->exec();
    }

    /**
     * Send a request to jenkins to check build status.
     *
     * @access public
     * @return bool
     */
    public function checkBuildStatus()
    {
        $pos = $this->dao->select('build.*, task.jenkinsTask, jenkins.name jenkinsName,jenkins.serviceUrl,jenkins.credentials')
            ->from(TABLE_CI_BUILD)->alias('build')
            ->leftJoin(TABLE_CI_TASK)->alias('task')->on('build.citask=task.id')
            ->leftJoin(TABLE_JENKINS)->alias('jenkins')->on('task.jenkins=jenkins.id')
            ->where('build.status')->ne('success')
            ->andWhere('build.status')->ne('fail')
            ->fetchAll();

        foreach($pos as $po) {
            $credentials = $this->loadModel('cicredentials')->getByID($po->credentials); // jenkins must use a token or account credentials
            if ($credentials->type === 'token') {
                $jenkinsTokenOrPassword = $credentials->token;
            } else if ($credentials->type === 'account') {
                $jenkinsTokenOrPassword = $credentials->password;
            }
            $jenkinsUser = $credentials->username;
            $jenkinsServer = $po->serviceUrl;

            $r = '://' . $jenkinsUser . ':' . $jenkinsTokenOrPassword . '@';
            $jenkinsServer = str_replace('://', $r, $jenkinsServer);
            $queueUrl = sprintf('%s/queue/item/%s/api/json', $jenkinsServer, $po->queueItem);

            $response = common::http($queueUrl);
            if (strripos($response,"404") > -1) { // queue已过期
                $infoUrl = sprintf('%s/job/%s/%s/api/json', $jenkinsServer, $po->jenkinsTask, $po->queueItem);
                $response = common::http($infoUrl);
                $buildInfo = json_decode($response);
                $result = strtolower($buildInfo->result);
                $this->updateCibuildStatus($po, $result);

                $logUrl = sprintf('%s/job/%s/%s/consoleText', $jenkinsServer, $po->jenkinsTask, $po->queueItem);
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
                        $this->updateCibuildStatus($po, 'building');
                    } else {
                        $result = strtolower($buildInfo->result);
                        $this->updateCibuildStatus($po, $result);

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

    public static function sendBuildRequest($url)
    {
        if(!extension_loaded('curl')) return json_encode(array('result' => 'fail', 'message' => $lang->error->noCurlExt));

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
//
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

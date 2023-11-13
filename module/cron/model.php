<?php
/**
 * The model file of cron module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     cron
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class cronModel extends model
{
    /**
     * Get by Id.
     *
     * @param  int    $cronID
     * @access public
     * @return object
     */
    public function getById($cronID)
    {
        return $this->dao->select('*')->from(TABLE_CRON)->where('id')->eq($cronID)->fetch();
    }

    /**
     * Get crons.
     *
     * @param  string $params
     * @access public
     * @return array
     */
    public function getCrons($params = '')
    {
        $validCrons = $this->dao->select('*')->from(TABLE_CRON)->fetchAll('id');

        $commandInMaxEdition = array(
            'moduleName=measurement&methodName=initCrontabQueue',
            'moduleName=measurement&methodName=execCrontabQueue',
            'moduleName=weekly&methodName=computeWeekly',
        );
        foreach($validCrons as $id => $cron)
        {
            if(strpos($params, 'nostop') !== false and $cron->status == 'stop') unset($validCrons[$id]);

            if($this->config->edition != 'max' and in_array($cron->command, $commandInMaxEdition)) unset($validCrons[$id]);
        }

        return $validCrons;
    }

    /**
     * Parse crons.
     *
     * @param  array    $crons
     * @access public
     * @return array
     */
    public function parseCron($crons)
    {
        $this->app->loadClass('crontab', true);

        $parsedCrons = array();
        foreach($crons as $cron)
        {
            $row = "{$cron->m} {$cron->h} {$cron->dom} {$cron->mon} {$cron->dow} {$cron->command}";
            preg_match_all('/(\S+\s+){5}|.*/', $row, $matchs);
            if($matchs[0])
            {
                try
                {
                    $parsedCron = array();
                    $parsedCron['schema']   = trim($matchs[0][0]);
                    $parsedCron['command']  = trim($matchs[0][1]);
                    $parsedCron['cron']     = CronExpression::factory($parsedCron['schema']);
                    $parsedCron['time']     = $parsedCron['cron']->getNextRunDate($cron->datetime);
                    $parsedCrons[$cron->id] = $parsedCron;
                }
                catch(InvalidArgumentException $e)
                {
                    $this->dao->update(TABLE_CRON)->set('status')->eq('stop')->where('id')->eq($cron->id)->exec();
                    continue;
                }
            }
        }

        return $parsedCrons;
    }

    /**
     * Change cron status.
     *
     * @param  int    $cronID
     * @param  string $status
     * @param  bool   $changeTime
     * @access public
     * @return bool
     */
    public function changeStatus($cronID, $status, $changeTime = false)
    {
        $data = new stdclass();
        $data->status = $status;
        if($status == 'running' or $changeTime) $data->lastTime = date(DT_DATETIME1);
        $this->dao->update(TABLE_CRON)->data($data)->where('id')->eq($cronID)->exec();
        return dao::isError() ? false : true;
    }

    /**
     * Change cron status to running
     *
     * @param int    $cronID
     * @access public
     * @return bool
     */
    public function changeStatusRunning($cronID)
    {
        $data = new stdclass();
        $data->status   = 'running';
        $data->lastTime = date(DT_DATETIME1);
        $this->dao->update(TABLE_CRON)->data($data)->where('id')->eq($cronID)->exec();
        return !dao::isError();
    }

    /**
     * Log cron.
     *
     * @param  string    $log
     * @access public
     * @return void
     */
    public function logCron($log)
    {
        $runMode = PHP_SAPI == 'cli' ? '_cli' : '';

        if(!is_writable($this->app->getLogRoot())) return false;

        $file = $this->app->getLogRoot() . "cron$runMode." . date('Ymd') . '.log.php';
        if(!is_file($file)) $log = "<?php\n die();\n" . $log;

        $fp = fopen($file, "a");
        fwrite($fp, $log);
        fclose($fp);
    }

    /**
     * Get the last executed time of cron process.
     *
     * @access public
     * @return string|null
     */
    public function getLastTime()
    {
        $lastTime =  $this->dao->select('lastTime')->from(TABLE_CRON)->where('id')->eq(1)->fetch('lastTime');
        if(!dao::isError()) return $lastTime;
        return null;
    }

    /**
     * Runable cron.
     *
     * @access public
     * @return bool
     */
    public function runable()
    {
        if(empty($this->config->global->cron)) return false;

        $lastTime = $this->getLastTime();
        if(helper::isZeroDate($lastTime) or ((time() - strtotime($lastTime)) > $this->config->cron->maxRunTime)) return true;
        if(!isset($this->config->cron->run->status)) return true;
        if($this->config->cron->run->status == 'stop') return true;

        return false;
    }

    /**
     * Check change cron.
     *
     * @access public
     * @return bool
     */
    public function checkChange()
    {
        $updatedCron = $this->dao->select('*')->from(TABLE_CRON)->where('lastTime')->notZeroDatetime()->andWhere('status')->ne('stop')->fetch();
        return $updatedCron ? true : false;
    }

    /**
     * Create cron.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $cron = fixer::input('post')
            ->add('status', 'normal')
            ->add('lastTime', null)
            ->skipSpecial('m,h,dom,mon,dow,command')
            ->get();

        if(!$this->config->features->cronSystemCall and $cron->type == 'system')
        {
            dao::$errors[] = $this->lang->cron->notice->errorType;
            return false;
        }

        $result = $this->checkRule($cron);
        if(!empty($result))
        {
            dao::$errors[] = $result;
            return false;
        }

        $this->dao->insert(TABLE_CRON)->data($cron)->autoCheck()->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * Update cron.
     *
     * @param  int    $cronID
     * @access public
     * @return bool
     */
    public function update($cronID)
    {
        $cron = fixer::input('post')
            ->skipSpecial('m,h,dom,mon,dow,command')
            ->get();

        if(!$this->config->features->cronSystemCall and $cron->type == 'system')
        {
            dao::$errors[] = $this->lang->cron->notice->errorType;
            return false;
        }

        $result = $this->checkRule($cron);
        if(!empty($result))
        {
            dao::$errors[] = $result;
            return false;
        }

        $this->dao->update(TABLE_CRON)->data($cron)->autoCheck()->where('id')->eq($cronID)->exec();
        return dao::isError() ? false : true;
    }

    /**
     * Check cron rule.
     *
     * @param  object $cron
     * @access public
     * @return string
     */
    public function checkRule($cron)
    {
        if($cron->m === ''   or preg_match('/[^0-9\*\-\/,]/', $cron->m))       return sprintf($this->lang->cron->notice->errorRule, $this->lang->cron->m);
        if($cron->h === ''   or preg_match('/[^0-9\*\-\/,]/', $cron->h))       return sprintf($this->lang->cron->notice->errorRule, $this->lang->cron->h);
        if($cron->dom === '' or preg_match('/[^0-9\*\-\/,\?LWC]/', $cron->dom))return sprintf($this->lang->cron->notice->errorRule, $this->lang->cron->dom);
        if($cron->mon === '' or preg_match('/[^0-9\*\-\/,]/', $cron->mon))     return sprintf($this->lang->cron->notice->errorRule, $this->lang->cron->mon);
        if($cron->dow === '' or preg_match('/[^0-9\*\-\/,\?LC#]/', $cron->dow))return sprintf($this->lang->cron->notice->errorRule, $this->lang->cron->dow);
        if(empty($cron->command))return sprintf($this->lang->error->notempty, $this->lang->cron->command);
        return null;
    }

    public function markCronStatus($status, $configID = 0)
    {
        if($configID)
        {
            $this->dao->update(TABLE_CONFIG)->set('value')->eq($status)->where('id')->eq($configID)->exec();
            return $configID;
        }
        else
        {
            $data = new stdclass();
            $data->owner   = 'system';
            $data->module  = 'cron';
            $data->section = 'run';
            $data->key     = 'status';
            $data->value   = $status;
            $this->dao->insert(TABLE_CONFIG)->data($data)->exec();
            return $this->dao->lastInsertID();
        }
    }

    public function getConfigID()
    {
        return $this->dao->select('*')->from(TABLE_CONFIG)
            ->where('owner')->eq('system')
            ->andWhere('module')->eq('cron')
            ->andWhere('section')->eq('run')
            ->andWhere('`key`')->eq('status')
            ->fetch('id');
    }

    /**
     * Get current cron status.
     *
     * @access public
     * @return int
     */
    public function getTurnon()
    {
        return $this->dao->select('*')->from(TABLE_CONFIG)
            ->where('owner')->eq('system')
            ->andWhere('module')->eq('common')
            ->andWhere('section')->eq('global')
            ->andWhere('`key`')->eq('cron')
            ->fetch('value');
    }

    /**
     * 调度生成队列任务.
     * Schedule, push tasks to queue.
     *
     * @param  int    $execId
     * @access public
     * @return bool
     */
    public function schedule($execId)
    {
        $now = date(DT_DATETIME1);

        $this->loadModel('setting')->setItem('system.cron.run.execId', $execId);
        $this->setting->setItem('system.cron.run.lastTime', $now);

        /* Get and parse crons. */
        $tasks = $this->dao->select('cron,MAX(`createdDate`) `datetime`')->from(TABLE_QUEUE)->groupBy('cron')->fetchAll('cron');
        $crons = $this->getCrons('nostop');
        foreach($crons as $index => $cron)
        {
            $cron->datetime = isset($tasks[$cron->id]) ? $tasks[$cron->id]->datetime : '1970-01-01';
        }

        $parsedCrons = $this->parseCron($crons);

        foreach($parsedCrons as $id => $cron)
        {
            $cronInfo = $crons[$id];

            /* Skip empty and stop cron.*/
            if(empty($cronInfo) || $cronInfo->status == 'stop') continue;

            if(!$cron['command'] || !isset($crons[$id])) continue;

            /* Check time. */
            if($now < $cron['time']->format(DT_DATETIME1)) continue;

            /* Push task into queue. */
            $task = new stdclass();
            $task->cron        = $id;
            $task->type        = $crons[$id]->type;
            $task->command     = $cron['command'];
            $task->createdDate = $now;
            $this->dao->insert(TABLE_QUEUE)->data($task)->exec();

            $log = date('G:i:s') . " schedule\ncronId: $id\nexecId: $execId\noutput: push task to queue\n\n";
            $this->logCron($log);
        }
    }

    /**
     * 执行所有定时任务.
     * Execute all tasks.
     *
     * @param  int    $execId
     * @access public
     * @return bool
     */
    public function execTasks($execId)
    {
        while(true)
        {
            $task = $this->dao->select('*')->from(TABLE_QUEUE)->where('status')->eq('wait')->andWhere('command')->ne('')->orderBy('createdDate')->fetch();
            if(!$task) break;

            $this->loadModel('common');
            $this->execTask($execId, $task);
        }
    }

    /**
     * 执行一个定时任务.
     * Execute one task.
     *
     * @param  int    $execId
     * @param  object $task
     * @access public
     * @return bool
     */
    public function execTask($execId, $task)
    {
        /* Other executor may execute the task at the same time，so we mark execId and wait 500ms to check whether we own it. */
        $this->dao->update(TABLE_QUEUE)->set('status')->eq('doing')->set('execId')->eq($execId)->where('id')->eq($task->id)->exec();
        usleep(500);

        $task = $this->dao->select('*')->from(TABLE_QUEUE)->where('id')->eq($task->id)->fetch();
        if($task->execId != $execId) return;

        /* Execution command. */
        $output = '';
        $return = '';

        unset($_SESSION['company']);
        unset($this->app->company);
        $this->common->setCompany();
        $this->common->loadConfigFromDB();

        try
        {
            if($task->type == 'zentao')
            {
                parse_str($task->command, $params);
                if(isset($params['moduleName']) and isset($params['methodName']))
                {
                    $this->app->loadConfig($params['moduleName']);
                    $output = $this->fetch($params['moduleName'], $params['methodName']);
                }
            }
            elseif($task->type == 'system')
            {
                exec($task->command, $out, $return);
                if($out) $output = implode(PHP_EOL, $out);
            }
        }
        catch(EndResponseException $endResponseException)
        {
            $output = $endResponseException->getContent();
        }
        catch(Exception $e)
        {
            $output = $e;
        }

        $this->dao->update(TABLE_QUEUE)->set('status')->eq('done')->where('id')->eq($task->id)->exec();
        $this->dao->update(TABLE_CRON)->set('lastTime')->eq(date(DT_DATETIME1))->where('id')->eq($task->cron)->exec();

        $log = date('G:i:s') . " execute\ncronId: {$task->cron}\nexecId: $execId\noutput: taskId:{$task->id}.\ncommand: {$task->command}.\nreturn : $return.\noutput : $output\n\n";
        $this->logCron($log);

        return true;
    }
}

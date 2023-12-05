<?php
/**
 * The control file of cron of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     cron
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class cron extends control
{
    /**
     * Index page.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->view->title = $this->lang->cron->common;
        $this->view->crons = $this->cron->getCrons();
        $this->display();
    }

    /**
     * Turnon cron.
     *
     * @access public
     * @return void
     */
    public function turnon()
    {
        $turnon = empty($this->config->global->cron) ? '1' : '0';
        $this->loadModel('setting')->setItem('system.common.global.cron', $turnon);
        return $this->sendSuccess(array('load' => inlink('index')));
    }

    /**
     * Open cron process.
     *
     * @access public
     * @return void
     */
    public function openProcess()
    {
        $this->display();
    }

    /**
     * Create cron.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->cron->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => inlink('index'), 'closeModal' => true));
        }

        $this->view->title = $this->lang->cron->create . $this->lang->cron->common;
        $this->display();
    }

    /**
     * Edit cron.
     *
     * @param  int    $cronID
     * @access public
     * @return void
     */
    public function edit($cronID)
    {
        if($_POST)
        {
            $this->cron->update($cronID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => inlink('index'), 'closeModal' => true));
        }

        $this->view->title = $this->lang->cron->edit . $this->lang->cron->common;
        $this->view->cron  = $this->cron->getById($cronID);
        $this->display();
    }

    /**
     * Toggle run cron.
     *
     * @param  int    $cronID
     * @param  string $status
     * @access public
     * @return void
     */
    public function toggle(int $cronID, string $status)
    {
        $this->cron->changeStatus($cronID, $status);
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Delete cron.
     *
     * @param  int    $cronID
     * @access public
     * @return void
     */
    public function delete($cronID)
    {
        $this->dao->delete()->from(TABLE_CRON)->where('id')->eq($cronID)->exec();
        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 使用Ajax请求执行定时任务.
     * Ajax execute cron.
     *
     * @param  bool $restart
     * @access public
     * @return void
     */
    public function ajaxExec($restart = false)
    {
        if(empty($this->config->global->cron)) return;

        /* Run as daemon. */
        ignore_user_abort(true);
        set_time_limit(0);
        session_write_close();

        $execId = mt_rand();
        if($restart) $this->cron->restartCron($execId);

        while(true)
        {
            /* Only one scheduler and max 4 consumers. */
            $roles = $this->applyExecRoles($execId);
            if(empty($roles))
            {
                ignore_user_abort(false);
                return;
            }

            if(in_array('scheduler', $roles)) $this->schedule($execId);
            if(in_array('consumer', $roles))  $this->consumeTasks($execId);

            sleep(20);
        }
    }

    /**
     * Schedule cron task by RoadRunner.
     *
     * @access public
     * @return void
     */
    public function rrSchedule()
    {
        if('cli' !== PHP_SAPI) return;

        set_time_limit(0);
        session_write_close();

        $execId = mt_rand();
        $this->cron->restartCron($execId);

        $this->loadModel('common');

        while(true)
        {
            if(empty($this->config->global->cron))
            {
                sleep(60);
                continue;
            }

            if($this->canSchedule($execId)) $this->schedule($execId);

            sleep(20);
        }
    }

    /**
     * Consume cron task by RoadRunner.
     *
     * @access public
     * @return void
     */
    public function rrConsume()
    {
        if('cli' !== PHP_SAPI) return;

        set_time_limit(0);
        session_write_close();

        $this->loadModel('common');

        $execId = mt_rand();
        while(true)
        {
            if(empty($this->config->global->cron))
            {
                sleep(60);
                continue;
            }

            $this->consumeTasks($execId);
            sleep(20);
        }
    }

    /**
     * 检查该execId是否可以进行调度(最近1分钟没有其他进程在调度).
     * Check the execId can schedule(No other execId scheduled 1 minutes ago).
     *
     * @param  int    $execId
     * @access protected
     * @return bool
     */
    protected function canSchedule($execId)
    {
        $settings = $this->dao->select('`key`,`value`')->from(TABLE_CONFIG)->where('owner')->eq('system')->andWhere('module')->eq('cron')->andWhere('section')->eq('scheduler')->fetchPairs();
        if(!isset($settings['execId']) || $settings['execId'] == $execId) return true;
        if(!isset($settings['lastTime']) || $settings['lastTime'] < date('Y-m-d H:i:s', strtotime('-1 minute'))) return true;

        return false;
    }

    /**
     * 检查该execId是否可以执行任务(最多允许1个调度进程，4个执行进程).
     * Check the execId can exec(1 scheduler, 4 consumers).
     *
     * @param  int    $execId
     * @access protected
     * @return array
     */
    protected function applyExecRoles($execId)
    {
        $roles = array();

        $settings = $this->dao->select('*')->from(TABLE_CONFIG)->where('owner')->eq('system')->andWhere('module')->eq('cron')->fetchAll();

        $scheduler = array('execId' => 0, 'lastTime' => '');
        $consumerCount = 0;

        $expirDate = date('Y-m-d H:i:s', strtotime('-1 minute'));
        foreach($settings as $setting)
        {
            if($setting->section == 'scheduler' && $setting->key == 'lastTime') $scheduler['lastTime'] = $setting->value;
            if($setting->section == 'scheduler' && $setting->key == 'execId')   $scheduler['execId']   = $setting->value;

            if($setting->section == 'consumer')
            {
                if($setting->value > $expirDate)
                {
                    $consumerCount ++;
                    if($consumerCount < $this->config->cron->maxConsumer && $setting->key == strval($execId)) $roles[] = 'consumer';
                }
                else
                {
                    $this->dao->delete()->from(TABLE_CONFIG)->where('id')->eq($setting->id)->exec();
                }
            }
        }

        if(in_array($scheduler['execId'], array(0, $execId)) || $scheduler['lastTime'] < $expirDate) $roles[] = 'scheduler';
        if($consumerCount < $this->config->cron->maxConsumer) $roles[] = 'consumer';

        return $roles;
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
        $this->loadModel('common');

        dao::$cache = array();

        $this->cron->updateTime('scheduler', $execId);

        /* Get and parse crons. */
        $tasks = $this->dao->select('cron,MAX(`createdDate`) `datetime`')->from(TABLE_QUEUE)->groupBy('cron')->fetchAll('cron');
        $crons = $this->cron->getCrons('nostop');
        foreach($crons as $cron)
        {
            $cron->datetime = isset($tasks[$cron->id]) ? $tasks[$cron->id]->datetime : '1970-01-01';
        }

        $parsedCrons = $this->cron->parseCron($crons);
        $now         = date(DT_DATETIME1);

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
            $this->cron->logCron($log);
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
    public function consumeTasks($execId)
    {
        while(true)
        {
            dao::$cache = array();

            $this->cron->updateTime('consumer', $execId);

            /* Consume. */
            $task = $this->dao->select('*')->from(TABLE_QUEUE)->where('status')->eq('wait')->andWhere('command')->ne('')->orderBy('createdDate')->fetch();
            if(!$task) break;

            $this->consumeTask($execId, $task);
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
    public function consumeTask($execId, $task)
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

        $this->loadModel('common');
        $this->common->setCompany();
        $this->common->loadConfigFromDB();

        try
        {
            if($task->type == 'zentao')
            {
                parse_str($task->command, $params);
                if(isset($params['moduleName']) and isset($params['methodName']))
                {
                    $this->app->loadLang($params['moduleName']);
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

        $log = date('G:i:s') . " execute\ncronId: {$task->cron}\nexecId: $execId\ntaskId: {$task->id}\ncommand: {$task->command}\nreturn : $return\noutput : $output\n\n";
        $this->cron->logCron($log);

        return true;
    }
}

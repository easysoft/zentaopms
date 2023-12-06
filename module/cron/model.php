<?php
/**
 * The model file of cron module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     cron
 * @version     $Id$
 * @link        https://www.zentao.net
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
        return $this->dao->select('*')->from(TABLE_CRON)
            ->where('1=1')
            ->beginIF(strpos($params, 'nostop') !== false)->andWhere('status')->ne('stop')->fi()
            ->beginIF($this->config->edition != 'max')
            ->andWhere('command')->ne('moduleName=measurement&methodName=initCrontabQueue')
            ->andWhere('command')->ne('moduleName=measurement&methodName=execCrontabQueue')
            ->fi()
            ->fetchAll('id');
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
            preg_match_all('/(\S+\s+){5}|.*/', $row, $matches);
            if($matches[0])
            {
                try
                {
                    $parsedCron = array();
                    $parsedCron['schema']   = trim($matches[0][0]);
                    $parsedCron['command']  = trim($matches[0][1]);
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
        $this->dao->update(TABLE_CRON)->set('lastTime')->eq(date(DT_DATETIME1))->where('`lastTime` IS NULL')->andWhere('status')->ne('stop')->exec();
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
        if(!is_writable($this->app->getLogRoot())) return false;

	$runMode = PHP_SAPI == 'cli' ? '_cli' : '';

        $file = $this->app->getLogRoot() . "cron$runMode." . date('Ymd') . '.log.php';
        if(!is_file($file)) $log = "<?php\n die();\n" . $log;

        $fp = fopen($file, "a");
        fwrite($fp, $log);
        fclose($fp);
    }

    /**
     * Get last executed time.
     *
     * @access public
     * @return string
     */
    public function getLastTime()
    {
        $cron = $this->dao->select('*')->from(TABLE_CRON)->orderBy('lastTime desc')->limit(1)->fetch();
        return isset($cron->lastTime) ? $cron->lastTime : null;
    }

    /**
     * Runnable cron.
     *
     * @access public
     * @return bool
     */
    public function runnable()
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
        $updatedCron = $this->dao->select('*')->from(TABLE_CRON)->where('`lastTime` IS NULL')->andWhere('status')->ne('stop')->fetch();
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
            dao::$errors = $result;
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
            ->add('lastTime', NULL)
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
            dao::$errors = $result;
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
     * @return array
     */
    public function checkRule($cron)
    {
        if($cron->m === ''   or preg_match('/[^0-9\*\-\/,]/', $cron->m))       return array('m' => sprintf($this->lang->cron->notice->errorRule, $this->lang->cron->m));
        if($cron->h === ''   or preg_match('/[^0-9\*\-\/,]/', $cron->h))       return array('h' => sprintf($this->lang->cron->notice->errorRule, $this->lang->cron->h));
        if($cron->dom === '' or preg_match('/[^0-9\*\-\/,\?LWC]/', $cron->dom))return array('dom' => sprintf($this->lang->cron->notice->errorRule, $this->lang->cron->dom));
        if($cron->mon === '' or preg_match('/[^0-9\*\-\/,]/', $cron->mon))     return array('mon' => sprintf($this->lang->cron->notice->errorRule, $this->lang->cron->mon));
        if($cron->dow === '' or preg_match('/[^0-9\*\-\/,\?LC#]/', $cron->dow))return array('dow' => sprintf($this->lang->cron->notice->errorRule, $this->lang->cron->dow));
        if(empty($cron->command)) return array('command' => sprintf($this->lang->error->notempty, $this->lang->cron->command));
        return array();
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
     * Restart cron.
     *
     * @access public
     * @return void
     */
    public function restartCron($execId)
    {
        $this->dao->update(TABLE_CONFIG)->set('value')->eq($execId)
            ->where('owner')->eq('system')
            ->andWhere('module')->eq('cron')
            ->andWhere('section')->eq('scheduler')
            ->andWhere('`key`')->eq($execId)
            ->exec();
        $this->dao->delete()->from(TABLE_QUEUE)->where('createdDate')->le(date("Y-m-d H:i:s", strtotime("-1 week")))->exec();

        $this->logCron(date('G:i:s') . " restart\n\n");
    }

    /**
     * Update last time of cron.
     *
     * @param string $role
     * @param int    $execId
     * @access public
     * @return void
     */
    public function updateTime($role, $execId)
    {
        $now = date(DT_DATETIME1);

        $settings = $this->dao->select('*')->from(TABLE_CONFIG)->where('owner')->eq('system')->andWhere('module')->eq('cron')->andWhere('section')->eq($role)->fetchAll('key');
        if($role == 'scheduler')
        {
            if(isset($settings['execId']))
            {
                $setting = $settings['execId'];
                if($setting->value != strval($execId)) $this->dao->update(TABLE_CONFIG)->set('value')->eq($execId)->where('id')->eq($setting->id)->exec();
            }
            else
            {
                $data = new stdclass();
                $data->owner   = 'system';
                $data->module  = 'cron';
                $data->section = 'scheduler';
                $data->key     = 'execId';
                $data->value   = $execId;

                $this->dao->insert(TABLE_CONFIG)->data($data)->exec();
            }

            if(isset($settings['lastTime']))
            {
                $setting = $settings['lastTime'];
                $this->dao->update(TABLE_CONFIG)->set('value')->eq($now)->where('id')->eq($setting->id)->exec();
            }
            else
            {
                $data = new stdclass();
                $data->owner   = 'system';
                $data->module  = 'cron';
                $data->section = 'scheduler';
                $data->key     = 'lastTime';
                $data->value   = $now;

                $this->dao->insert(TABLE_CONFIG)->data($data)->exec();
            }
        }
        else
        {
            if(isset($settings[strval($execId)]))
            {
                $setting = $settings[strval($execId)];
                $this->dao->update(TABLE_CONFIG)->set('value')->eq($now)->where('id')->eq($setting->id)->exec();
            }
            else
            {
                $data = new stdclass();
                $data->owner   = 'system';
                $data->module  = 'cron';
                $data->section = 'consumer';
                $data->key     = $execId;
                $data->value   = $now;

                $this->dao->insert(TABLE_CONFIG)->data($data)->exec();
            }
        }
    }
}

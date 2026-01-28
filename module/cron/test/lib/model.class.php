<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class cronModelTest extends baseTest
{
    protected $moduleName = 'cron';
    protected $className  = 'model';

    /**
    ¦* 运行sql重置zt_cron表。
    ¦* Truncate zt_cron and insert data again.
    ¦*
    ¦* @access public
    ¦* @return void
    ¦*/
    public function init()
    {
        global $tester, $app;
        $appPath = $app->getAppRoot();
        $sqlFile = $appPath . 'test/data/cron.sql';
        $tester->dbh->exec(file_get_contents($sqlFile));
    }

    /**
     * Get by id test.
     *
     * @param  int    $cronID
     * @access public
     * @return object
     */
    public function getByIdTest($cronID)
    {
        $objects = $this->instance->getById($cronID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get crons test.
     *
     * @param  string $params
     * @access public
     * @return array
     */
    public function getCronsTest($params = '')
    {
        $objects = $this->instance->getCrons($params);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Parse crons test.
     *
     * @param  array    $crons
     * @access public
     * @return array
     */
    public function parseCronTest($crons)
    {
        $objects = $this->instance->parseCron($crons);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Change cron status test.
     *
     * @param  int    $cronID
     * @param  string $status
     * @param  bool   $changeTime
     * @access public
     * @return bool
     */
    public function changeStatusTest($cronID, $status, $changeTime = false)
    {
        $objects = $this->instance->changeStatus($cronID, $status, $changeTime);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Log cron test.
     *
     * @param  string    $log
     * @access public
     * @return void
     */
    public function logCronTest($log)
    {
        $objects = $this->instance->logCron($log);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get last execed time test.
     *
     * @access public
     * @return string
     */
    public function getLastTimeTest()
    {
        $objects = $this->instance->getLastTime();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Runnable cron test.
     *
     * @access public
     * @return bool
     */
    public function runnableTest()
    {
        $objects = $this->instance->runnable();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Check change cron test.
     *
     * @access public
     * @return bool
     */
    public function checkChangeTest()
    {
        $objects = $this->instance->checkChange();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Create cron test.
     *
     * @param  array $param
     * @access public
     * @return int
     */
    public function createTest($param)
    {
        foreach($param as $k => $v) $_POST[$k] = $v;
        $cronID = $this->instance->create();
        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->instance->getById($cronID);
        return $objects;
    }

    /**
     * Update cron test.
     *
     * @param  int    $cronID
     * @access public
     * @return bool
     */
    public function updateTest($cronID, $param)
    {
        foreach($param as $k => $v) $_POST[$k] = $v;
        $objects = $this->instance->update($cronID);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->instance->getById($cronID);
        return $objects;
    }

    /**
     * Check cron rule test.
     *
     * @param  object $cron
     * @access public
     * @return string
     */
    public function checkRuleTest($cron)
    {
        $objects = $this->instance->checkRule($cron);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Update time test.
     *
     * @param  string $role
     * @param  int    $execId
     * @access public
     * @return int
     */
    public function updateTimeTest($role, $execId)
    {
        $this->instance->updateTime($role, $execId);

        if(dao::isError()) return dao::getError();

        if($role == 'scheduler')
        {
            $execId = $this->instance->dao->select('value')->from(TABLE_CONFIG)->where('key')->eq('execId')->fetch('value');
        }
        else
        {
            $execId = $this->instance->dao->select('`key`')->from(TABLE_CONFIG)->where('section')->eq('consumer')->orderBy('value_desc')->fetch('key');
        }

        sleep(1); // Diff time.

        return $execId;
    }

    /**
     * Restart cron test.
     *
     * @param  mixed $execId
     * @access public
     * @return array
     */
    public function restartCronTest($execId)
    {
        $this->instance->restartCron($execId);

        if(dao::isError()) return dao::getError();

        // Get updated execId in config
        $configAfter = $this->instance->dao->select('value')->from(TABLE_CONFIG)
            ->where('owner')->eq('system')
            ->andWhere('module')->eq('cron')
            ->andWhere('section')->eq('scheduler')
            ->andWhere('`key`')->eq('execId')
            ->fetch('value');

        return array('configAfter' => $configAfter);
    }
}

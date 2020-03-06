<?php
/**
 * The control file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class ci extends control
{
    /**
     * ci constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->ci->setMenu();
    }

    /**
     * Init compile queue.
     * 
     * @access public
     * @return void
     */
    public function initQueue()
    {
        $scheduleJobs = $this->loadModel('job')->getListByTriggerType('schedule');

        $week = date('w');
        $this->loadModel('compile');
        foreach($scheduleJobs as $job)
        {
            if(strpos($job->atDay, $week) !== false) $this->compile->createByJob($job->id, $job->atTime, 'atTime');
        }
        echo 'success';
    }

    /**
     * Exec compile.
     * 
     * @access public
     * @return void
     */
    public function exec()
    {
        $compiles = $this->loadModel('compile')->getUnexecutedList();
        foreach($compiles as $compile)
        {
            if($compile->atTime and date('H:i') < $compile->atTime) continue; 
            $this->compile->execByCompile($compile);
        }
        echo 'success';
    }

    /**
     * Send a request to jenkins to check build status.
     *
     * @access public
     * @return void
     */
    public function checkBuildStatus()
    {
        $this->ci->checkBuildStatus();
        if(dao::isError())
        {
            echo json_encode(dao::getError());
        }
        else
        {
            echo 'success';
        }
    }
}

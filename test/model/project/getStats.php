#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getStats();
cid=1
pid=1


*/
class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->project = $tester->loadModel('project');
    }

    /**
     * Get executions by status. 
     * 
     * @param  string $status 
     * @access public
     * @return int
     */
    public function getByStatus($status)
    {
        $executions = $this->project->getStats(0, $status);
        if(empty($executions)) return false;
        if($status != 'all')
        {
            foreach($executions as $execution)
            {
                if($execution->status != $status) return false;
            }
        }
        return count($executions);
    }

    /**
     * Get executions by project. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return int
     */
    public function getByProject($projectID)
    {
        $executions = $this->project->getStats($projectID, 'all');
        if(empty($executions)) return false;
        foreach($executions as $execution)
        {
            if($execution->project != $projectID) return false;
        }
        return count($executions);
    }

    /**
     * Get executions by order.
     * 
     * @param  string $orderBy 
     * @access public
     * @return bool
     */
    public function getListByOrder($orderBy)
    {
        $executions = $this->project->getStats(0, 'all', 0, 0, 30, $orderBy);
        return checkOrder($executions, $orderBy);
    }
}


$t = new Tester('admin');

/* GetStats(0, $status). */
r($t->getByStatus('all'))    && p() && e('600'); //查看所有执行
r($t->getByStatus('undone')) && p() && e('0');   //查看未完成的执行
r($t->getByStatus('doing'))  && p() && e('300'); //查看所有进行中的执行
r($t->getByStatus('wait'))   && p() && e('300'); //查看所有进行中的执行

/* GetStats($projectID, 'all'). */
r($t->getByProject('11'))    && p() && e('7');   //查看id为11项目的执行
r($t->getByProject('12'))    && p() && e('7');   //查看id为12项目的执行

/* GetStats(0, 'all', 0, 0, 30, $orderBy). */
r($t->getListByOrder(0, 'all', 0, 0, 30, 'id_desc'))  && p() && e('1');   //按照项目id倒序获取项目列表
r($t->getListByOrder(0, 'all', 0, 0, 30, 'name_asc')) && p() && e('1');   //按照项目名称正序获取项目列表

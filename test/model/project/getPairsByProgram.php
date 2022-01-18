#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getPairsByProgram;
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
     * Get project pairs by programID.
     *
     * @param  int $programID
     * @access public
     * @return int
     */
    public function getByProgram($programID)
    {
        $projects = $this->project->getPairsByProgram($programID);
        if(empty($projects))
        {
            $result = array();
            $result['code']    = 'fail';
            $result['message'] = 'No data.';

            return $result;
        }

        return count($projects);
    }

    /**
     * Get project pairs by status.
     *
     * @param  string $status
     * @access public
     * @return int
     */
    public function getByStatus($status)
    {
        $projects = $this->project->getPairsByProgram(2, $status);
        if(empty($projects))
        {
            $result = array();
            $result['code']    = 'fail';
            $result['message'] = 'No data.';

            return $result;
        }

        return count($projects);
    }

    /**
     * Get project list by order.
     *
     * @param  string $orderBy
     * @access public
     * @return int
     */
    public function getListByOrder($orderBy)
    {
        $projects = $this->project->getPairsByProgram(3, 'doing', $orderBy);
        return checkOrder($projects, $orderBy);
    }
}

$t = new Tester('admin');

/* Get project list by program ID. */
r($t->getByProgram('')) && p() && e('90');       //查找管理员可查看的所有项目
r($t->getByProgram(0))  && p() && e('No data.'); //查找独立项目
r($t->getByProgram(1))  && p() && e('9');        //查找管理员可查看的所属项目集ID为1的项目

/* Get project list by status. */
r($t->getByStatus('wait'))     && p() && e('3'); //查找管理员可查看的所属项目集ID为1且状态为wait的项目
r($t->getByStatus('noclosed')) && p() && e('7'); //查找管理员可查看的所属项目集ID为1且状态不为closed的项目

/* Get project list by order. */
r($t->getListByOrder('id_desc')) && p() && e('1'); //按照ID降序排序查找项目

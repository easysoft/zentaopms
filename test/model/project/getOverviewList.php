#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getOverviewList;
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
     * Get project by status.
     *
     * @param  string $status
     * @access public
     * @return int
     */
    public function getBystatus($status)
    {
        $projects = $this->project->getOverviewList('byStatus', $status);
        if(!$projects)
        {
            $result = array();
            $result['code']    = 'fail';
            $result['message'] = 'No data.';

            return $result;
        }

        if($status == 'undone') $status = 'wait,doing,suspended';

        foreach($projects as $project)
        {
            if(strpos(",$status,", $project->status) === false)
            {
                $result = array();
                $result['code']    = 'fail';
                $result['message'] = 'Error data.';

                return $result;
            }
        }

        return count($projects);
    }

    /**
     * Get project list by order.
     *
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function getListByOrder($orderBy)
    {
        $projects = $this->project->getOverviewList('byStatus', 'wait', $orderBy);
        return checkOrder($projects, $orderBy);
    }

    /**
     * Get project by ID.
     *
     * @param  int $projectID
     * @access public
     * @return void
     */
    public function getByID($projectID)
    {
        return $this->project->getOverviewList('byID', $projectID);
    }
}

$t = new Tester('admin');

/* GetOverviewList('byStatus'). */
r($t->getByStatus('wait'))   && p() && e('15'); // 获取未开始的项目
r($t->getByStatus('undone')) && p() && e('15'); // 获取状态不为done和closed的项目

/* GetOverviewList('byID', $id). */
r($t->getByID(11))    && p('11:id') && e('11'); // 根据项目ID获取项目详情
r($t->getByID(10000)) && p('id')    && e('0');  // 获取不存在的项目

/* GetOverviewList('byStatus', 'wait', $orderBy). */
r($t->getListByOrder('id_asc'))    && p() && e('1'); //按照ID正序获取项目列表
r($t->getListByOrder('name_desc')) && p() && e('1'); // 按照项目名称倒序获取项目列表

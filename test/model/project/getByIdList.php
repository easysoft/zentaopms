#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';


/**

title=测试 projectModel::getByIdList();
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
     * Check projectList get from getByIdList.
     * 
     * @param  array  $projectIdList 
     * @param  int    $count 
     * @access public
     * @return array
     */
    public function getByIdList($projectIdList, $count)
    {
        $projectList = $this->project->getByIdList($projectIdList);
        if(count($projectList) != $count) return false;
        return $projectList;
    }
}

$t = new Tester('admin');
$projectIdList = array(11,12,13);

/* GetByIdList($projectIdList). */
r(($t->getByIdList($projectIdList, 3))) && p('11:name;12:name;13:name') && e('项目1;项目2;项目3'); //获取projectIdList对应的项目名称

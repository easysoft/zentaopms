#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getParentName();
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
     * Get parentName.
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getParentName($projectID)
    {
        $program = $this->project->getParentName($projectID);
        if(empty($program)) return false;
        return $program;
    }
}

$t = new Tester('admin');

/* GetParentName($projectID). */
r($t->getParentName(11))  && p('name') && e('项目集1'); //获取id为11的项目父项目名字
r($t->getParentName(1))   && p('name') && e('项目集1'); //获取id为1的项目父项目名字
r($t->getParentName(0))   && p('name') && e('0');       //获取id为0的项目父项目名字

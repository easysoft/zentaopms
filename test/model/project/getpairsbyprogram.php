#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getPairsByProgram;
cid=1
pid=1

查找管理员可查看的所有项目 >> 90
查找独立项目 >> No data.
查找管理员可查看的所属项目集ID为1的项目 >> 9
查找管理员可查看的所属项目集ID为1且状态为wait的项目 >> 3
查找管理员可查看的所属项目集ID为1且状态不为closed的项目 >> 7

*/

/*
    public function getListByOrder($orderBy)
    {
        $projects = $this->project->getPairsByProgram(3, 'doing', $orderBy);
        return checkOrder($projects, $orderBy);
    }
}*/

$t = new Project('admin');

$t_project = array('', 0, 1, 'wait', 'noclosed');

r($t->getByProgram($t_project[0]))     && p() && e('90');       //查找管理员可查看的所有项目
r($t->getByProgram($t_project[1]))     && p() && e('No data.'); //查找独立项目
r($t->getByProgram($t_project[2]))     && p() && e('9');        //查找管理员可查看的所属项目集ID为1的项目
r($t->getByStatusPairs($t_project[3])) && p() && e('3');        //查找管理员可查看的所属项目集ID为1且状态为wait的项目
r($t->getByStatusPairs($t_project[4])) && p() && e('7');        //查找管理员可查看的所属项目集ID为1且状态不为closed的项目
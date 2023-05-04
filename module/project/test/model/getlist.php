#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');
zdTable('project')->gen(90);

/**

title=测试 projectModel::getInfoList;
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

$doingProjects     = $tester->project->getList('doing');
$waitProjects      = $tester->project->getList('wait');
$suspendedProjects = $tester->project->getList('suspended');
$closedProjects    = $tester->project->getList('closed');
$allProjects       = $tester->project->getList('all');

r(count($doingProjects))     && p()          && e('44');    //查询正在进行的项目数量
r($waitProjects)             && p('11:name') && e('项目1'); //查询wait状态的Id为11的项目名称
r(count($suspendedProjects)) && p()          && e('11');    //查询暂停状态的项目数量
r(count($closedProjects))    && p()          && e('11');    //查询关闭状态的项目数量
r(count($allProjects))       && p()          && e('90');    //查询所有状态的项目数量

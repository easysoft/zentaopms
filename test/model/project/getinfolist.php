#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel::getInfoList;
cid=1
pid=1

查询正在进行的项目数量 >> 44
查询wait状态的Id为11的项目名称 >> 项目1
查询暂停状态的项目数量 >> 11
查询关闭状态的项目数量 >> 11
查询所有状态的项目数量 >> 110

*/

global $tester;
$tester->loadModel('project');

$doingProjects     = $tester->project->getInfoList('doing');
$waitProjects      = $tester->project->getInfoList('wait');
$suspendedProjects = $tester->project->getInfoList('suspended');
$closedProjects    = $tester->project->getInfoList('closed');
$allProjects       = $tester->project->getInfoList('all');

r(count($doingProjects))     && p()          && e('44');    //查询正在进行的项目数量
r($waitProjects)             && p('11:name') && e('项目1'); //查询wait状态的Id为11的项目名称
r(count($suspendedProjects)) && p()          && e('11');    //查询暂停状态的项目数量
r(count($closedProjects))    && p()          && e('11');    //查询关闭状态的项目数量
r(count($allProjects))       && p()          && e('110');   //查询所有状态的项目数量

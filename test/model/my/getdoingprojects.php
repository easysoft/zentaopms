#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/my.class.php';
su('admin');

/**

title=测试 myModel->getDoingProjects();
cid=1
pid=1

获取doingcount数据 >> 5
获取doing状态的项目 >> 项目86,doing
获取doing状态的项目统计 >> 5

*/

$my = new myTest();

$doingCount = $my->getDoingProjectsTest()->doingCount;
$projects   = $my->getDoingProjectsTest()->projects;

r($doingCount)      && p()                && e('5');           //获取doingcount数据
r($projects)        && p('0:name,status') && e('项目86,doing');//获取doing状态的项目
r(count($projects)) && p()                && e('5');           //获取doing状态的项目统计
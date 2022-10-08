#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 testtaskModel->getProjectTasks();
cid=1
pid=1

查看项目11下的所有测试单数量 >> 1
查看项目20下的所有测试单数量 >> 1
查看项目100下的所有测试单数量 >> 0
查看项目11下的测试单1的详细信息 >> 测试单1,,wait,2022-04-08,2022-04-15
查看项目20下的所有测试单10的详细信息 >> 测试单10,,doing,2022-04-08,2022-04-15

*/

global $tester;
$tester->loadModel('testtask');

$project11Tasks  = $tester->testtask->getProjectTasks(11);
$project20Tasks  = $tester->testtask->getProjectTasks(20);
$project100Tasks = $tester->testtask->getProjectTasks(100);

r(count($project11Tasks))  && p()                                && e('1');                                     // 查看项目11下的所有测试单数量 
r(count($project20Tasks))  && p()                                && e('1');                                     // 查看项目20下的所有测试单数量
r(count($project100Tasks)) && p()                                && e('0');                                     // 查看项目100下的所有测试单数量
r($project11Tasks)         && p('1:name,type,status,begin,end')  && e('测试单1,,wait,2022-04-08,2022-04-15');   // 查看项目11下的测试单1的详细信息
r($project20Tasks)         && p('10:name,type,status,begin,end') && e('测试单10,,doing,2022-04-08,2022-04-15'); // 查看项目20下的所有测试单10的详细信息
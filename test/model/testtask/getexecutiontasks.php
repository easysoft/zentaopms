#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 testtaskModel->getExecutionTasks();
cid=1
pid=1

查看迭代101下的所有测试单数量 >> 1
查看迭代110下的所有测试单数量 >> 1
查看迭代120下的所有测试单数量 >> 0
查看迭代101下的测试单1的详细信息 >> 测试单1,,wait,2022-04-08,2022-04-15
查看迭代110下的所有测试单10的详细信息 >> 测试单10,,doing,2022-04-08,2022-04-15

*/

global $tester;
$tester->loadModel('testtask');

$execution101Tasks = $tester->testtask->getExecutionTasks(101);
$execution110Tasks = $tester->testtask->getExecutionTasks(110);
$execution120Tasks = $tester->testtask->getExecutionTasks(120);

r(count($execution101Tasks))  && p()                                && e('1');                                     // 查看迭代101下的所有测试单数量 
r(count($execution110Tasks))  && p()                                && e('1');                                     // 查看迭代110下的所有测试单数量
r(count($execution120Tasks))  && p()                                && e('0');                                     // 查看迭代120下的所有测试单数量
r($execution101Tasks)         && p('1:name,type,status,begin,end')  && e('测试单1,,wait,2022-04-08,2022-04-15');   // 查看迭代101下的测试单1的详细信息
r($execution110Tasks)         && p('10:name,type,status,begin,end') && e('测试单10,,doing,2022-04-08,2022-04-15'); // 查看迭代110下的所有测试单10的详细信息
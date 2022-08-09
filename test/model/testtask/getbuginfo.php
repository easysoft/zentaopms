#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testtask.class.php';
su('admin');

/**

title=测试 testtaskModel->getBugInfo();
cid=1
pid=1

查看根据测试单的ID获取到的数据数量 >> 8
查看根据测试单的ID获取到的数据数量 >> 1,1
查看根据测试单的ID获取到的数据数量 >> 激活,1
查看根据测试单的ID获取到的数据数量 >> 8
查看根据测试单的ID获取到的数据数量 >> 2,1
查看根据测试单的ID获取到的数据数量 >> admin,1

*/

global $tester;
$tester->loadModel('testtask');

$task1 = $tester->testtask->getBugInfo(1, 1);
$task2 = $tester->testtask->getBugInfo(2, 2);

r(count($task1))               && p()                    && e('8');       // 查看根据测试单的ID获取到的数据数量
r($task1['bugSeverityGroups']) && p('1:name,value')      && e('1,1');     // 查看根据测试单的ID获取到的数据数量
r($task1['bugStatusGroups'])   && p('active:name,value') && e('激活,1');  // 查看根据测试单的ID获取到的数据数量

r(count($task2))               && p()                    && e('8');       // 查看根据测试单的ID获取到的数据数量
r($task2['bugSeverityGroups']) && p('2:name,value')      && e('2,1');     // 查看根据测试单的ID获取到的数据数量
r($task2['bugOpenedByGroups']) && p('admin:name,value')  && e('admin,1'); // 查看根据测试单的ID获取到的数据数量
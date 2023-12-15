#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(5);
su('admin');

zdTable('team')->config('team')->gen(30);
zdTable('project')->config('execution')->gen(30);

/**

title=测试executionModel->getCanCopyObjectsTest();
timeout=0
cid=1

*/

$projectIDList = array(11, 60, 100);
$count         = array(0, 1);

$executionTester = new executionTest();
r($executionTester->getCanCopyObjectsTest($projectIDList[0], $count[0])) && p('11')  && e('敏捷项目1（1人）'); // 敏捷项目数据查询
r($executionTester->getCanCopyObjectsTest($projectIDList[1], $count[0])) && p('60')  && e('瀑布项目2（1人）'); // 瀑布项目数据查询
r($executionTester->getCanCopyObjectsTest($projectIDList[2], $count[0])) && p('126') && e('看板30（1人）');    // 看板项目数据查询
r($executionTester->getCanCopyObjectsTest($projectIDList[0], $count[1])) && p()      && e('6');                // 敏捷项目数据统计
r($executionTester->getCanCopyObjectsTest($projectIDList[1], $count[1])) && p()      && e('11');               // 瀑布项目数据统计
r($executionTester->getCanCopyObjectsTest($projectIDList[2], $count[1])) && p()      && e('4');                // 看板项目数据统计

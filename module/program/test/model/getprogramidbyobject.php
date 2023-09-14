#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';
zdTable('user')->gen(5);
zdTable('project')->config('program')->gen(40);
su('admin');

/**

title=测试 programModel::getProgramIDByObject();
timeout=0
cid=1

*/

$objectIdList = array(1, 11, 60, 100, 101, 112, 124);
$exist        = array(0, 1);

$programTester = new programTest();
r($programTester->getProgramIDByObjectTest($objectIdList[0], $exist[1])) && p() && e('1'); // 获取项目集的id
r($programTester->getProgramIDByObjectTest($objectIdList[1], $exist[1])) && p() && e('1'); // 获取敏捷项目的项目集id
r($programTester->getProgramIDByObjectTest($objectIdList[2], $exist[1])) && p() && e('2'); // 获取瀑布项目的项目集id
r($programTester->getProgramIDByObjectTest($objectIdList[3], $exist[1])) && p() && e('0'); // 获取看板项目的项目集id
r($programTester->getProgramIDByObjectTest($objectIdList[4], $exist[1])) && p() && e('1'); // 获取迭代的项目集id
r($programTester->getProgramIDByObjectTest($objectIdList[5], $exist[1])) && p() && e('2'); // 获取阶段的项目集id
r($programTester->getProgramIDByObjectTest($objectIdList[6], $exist[1])) && p() && e('0'); // 获取看板的项目集id
r($programTester->getProgramIDByObjectTest($objectIdList[1], $exist[0])) && p() && e('0'); // 获取不存在敏捷项目的项目集id
r($programTester->getProgramIDByObjectTest($objectIdList[2], $exist[0])) && p() && e('0'); // 获取不存在瀑布项目的项目集id
r($programTester->getProgramIDByObjectTest($objectIdList[3], $exist[0])) && p() && e('0'); // 获取不存在看板项目的项目集id
r($programTester->getProgramIDByObjectTest($objectIdList[4], $exist[0])) && p() && e('0'); // 获取不存在迭代的项目集id
r($programTester->getProgramIDByObjectTest($objectIdList[5], $exist[0])) && p() && e('0'); // 获取不存在阶段的项目集id
r($programTester->getProgramIDByObjectTest($objectIdList[6], $exist[0])) && p() && e('0'); // 获取不存在看板的项目集id

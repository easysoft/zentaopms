#!/usr/bin/env php
<?php
/**

title=测试 buildModel->batchUnlinkBug();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

$build = zdTable('build')->config('build');
$build->bugs->range('`1,2,3`,`4,5,6`');
$build->gen(5);

zdTable('user')->gen(5);
su('admin');

$buildIdList  = array(0, 1, 2, 6);
$bugIdList[0] = array();
$bugIdList[1] = array(1, 2);
$bugIdList[2] = array(4, 5);

$buildTester = new buildTest();
r($buildTester->batchUnlinkBugTest($buildIdList[0], $bugIdList[0])) && p()            && e('0');   // 测试buildID=0，bug ID 列表为空
r($buildTester->batchUnlinkBugTest($buildIdList[1], $bugIdList[1])) && p('1:id,bugs') && e('1,3'); // 测试buildID=1，bug ID 列表为1,2
r($buildTester->batchUnlinkBugTest($buildIdList[2], $bugIdList[2])) && p('2:id,bugs') && e('2,6'); // 测试buildID=2，bug ID 列表为4,5
r($buildTester->batchUnlinkBugTest($buildIdList[3], $bugIdList[1])) && p()            && e('0');   // 测试buildID不存在，bug ID 列表为1,2

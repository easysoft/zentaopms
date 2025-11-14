#!/usr/bin/env php
<?php
/**

title=测试 buildModel->batchUnlinkBug();
timeout=0
cid=15485

- 测试buildID=0，bug ID 列表为空 @0
- 测试buildID=1，bug ID 列表为1,2
 - 第1条的id属性 @1
 - 第1条的bugs属性 @版本1
- 测试buildID=2，bug ID 列表为4,5
 - 第2条的id属性 @2
 - 第2条的bugs属性 @版本2
- 测试buildID不存在，bug ID 列表为1,2 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

$build = zenData('build')->loadYaml('build');
$build->bugs->range('`1,2,3`,`4,5,6`');
$build->gen(5);

zenData('user')->gen(5);
su('admin');

$buildIdList  = array(0, 1, 2, 6);
$bugIdList[0] = array();
$bugIdList[1] = array(1, 2);
$bugIdList[2] = array(4, 5);

$buildTester = new buildTest();
r($buildTester->batchUnlinkBugTest($buildIdList[0], $bugIdList[0])) && p()            && e('0');       // 测试buildID=0，bug ID 列表为空
r($buildTester->batchUnlinkBugTest($buildIdList[1], $bugIdList[1])) && p('1:id,name') && e('1,版本1'); // 测试buildID=1，bug ID 列表为1,2
r($buildTester->batchUnlinkBugTest($buildIdList[2], $bugIdList[2])) && p('2:id,name') && e('2,版本2'); // 测试buildID=2，bug ID 列表为4,5
r($buildTester->batchUnlinkBugTest($buildIdList[3], $bugIdList[1])) && p()            && e('0');       // 测试buildID不存在，bug ID 列表为1,2

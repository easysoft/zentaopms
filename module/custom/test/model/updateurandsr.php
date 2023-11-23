#!/usr/bin/env php
<?php
/**

title=测试 customModel->updateURAndSR();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

zdTable('lang')->config('lang')->gen(5);
zdTable('user')->gen(5);
su('admin');

$key    = array(1, 0);
$SRName = array('', '软件需求', '软件需求1');

$dataList[] = array('SRName' => $SRName[0], 'URName' => '用户需求');
$dataList[] = array('SRName' => $SRName[1], 'URName' => '用户需求');
$dataList[] = array('SRName' => $SRName[2], 'URName' => '用户需求');

$customTester = new customTest();
r($customTester->updateURAndSRTest($key[0], $dataList[2])) && p('SRName') && e('软件需求1'); // 测试修改key为1，SRName值更改为软件需求1
r($customTester->updateURAndSRTest($key[0], $dataList[1])) && p('SRName') && e('软件需求');  // 测试修改key为1，SRName值更改为软件需求
r($customTester->updateURAndSRTest($key[0], $dataList[0])) && p('SRName') && e('软件需求');  // 测试修改key为1，SRName值更改为空
r($customTester->updateURAndSRTest($key[1], $dataList[2])) && p()         && e('0');         // 测试修改key为0，SRName值更改为软件需求1
r($customTester->updateURAndSRTest($key[1], $dataList[1])) && p()         && e('0');         // 测试修改key为0，SRName值更改为软件需求
r($customTester->updateURAndSRTest($key[1], $dataList[0])) && p()         && e('0');         // 测试修改key为0，SRName值更改为空

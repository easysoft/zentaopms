#!/usr/bin/env php
<?php
/**

title=测试 docModel->processCollector();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doccontent')->config('doccontent')->gen(10);
zdTable('docaction')->config('docaction')->gen(20);
zdTable('doc')->config('doc')->gen(10);
su('admin');

$docIdList[0] = array();
$docIdList[1] = range(1, 10);

$docTester = new docTest();
r($docTester->processCollectorTest($docIdList[0])) && p()                   && e('0');             // 测试空数据
r($docTester->processCollectorTest($docIdList[1])) && p('1:collector', ';') && e(',admin,user1,'); // 测试正常数据

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 apiModel->deleteRelease();
timeout=0
cid=1

- 测试ID为1的区块删除后的返回结果 @1

*/

global $tester;
$tester->loadModel('api');
r($tester->api->deleteRelease(1)) && p() && e('1');  // 测试ID为1的区块删除后的返回结果

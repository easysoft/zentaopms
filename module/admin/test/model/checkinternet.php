#!/usr/bin/env php
<?php
/**

title=测试 adminModel::checkInternet();
timeout=0
cid=1

- 验证网络连接 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
su('admin');

global $tester;
r($tester->loadModel('admin')->checkInternet()) && p() && e('1'); // 验证网络连接

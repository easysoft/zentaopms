#!/usr/bin/env php
<?php
/**

title=测试 adminModel::checkInternet();
cid=1
pid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
r($tester->loadModel('admin')->checkInternet()) && p() && e('1'); // 验证网络连接

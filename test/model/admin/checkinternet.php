#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 adminModel::checkInternet();
cid=1
pid=1

验证网络连接 >> 1

*/

global $tester;
$tester->loadModel('admin');
$result = (int)$tester->admin->checkInternet();

r($result) && p() && e('1'); // 验证网络连接

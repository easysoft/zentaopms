#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zdTable('account')->gen(10);

/**

title=accountModel->getByID();
timeout=0
cid=1

*/

global $tester;
$accountModel = $tester->loadModel('account');
r($accountModel->getByID(0))        && p()                   && e('0'); // 获取空数据。
r($accountModel->getByID(100))      && p()                   && e('0'); // 获取不存在的数据。
r((array)$accountModel->getByID(1)) && p() && e('1,运维账号1,qingyun'); // 获取id=1的数据。

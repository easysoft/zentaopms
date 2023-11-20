#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zdTable('account')->gen(10);

/**

title=accountModel->getList();
timeout=0
cid=1

*/

global $tester;
$accountModel = $tester->loadModel('account');
$pairs        = $accountModel->getPairs();
r(count($pairs)) && p()      && e('10');                  // 检查获取数据数。
r($pairs)        && p('1,2') && e('运维账号1,运维账号2'); // 检查部分数据。

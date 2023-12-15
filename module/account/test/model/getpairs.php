#!/usr/bin/env php
<?php

/**

title=accountModel->getPairs();
timeout=0
cid=0

- 检查获取数据数。 @10
- 检查部分数据。
 - 属性1 @运维账号1
 - 属性2 @运维账号2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zdTable('account')->gen(10);

global $tester;
$accountModel = $tester->loadModel('account');
$pairs        = $accountModel->getPairs();
r(count($pairs)) && p()      && e('10');                  // 检查获取数据数。
r($pairs)        && p('1,2') && e('运维账号1,运维账号2'); // 检查部分数据。
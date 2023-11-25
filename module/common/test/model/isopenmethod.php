#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 commonModel->isOpenMethod();
timeout=0
cid=1

- 执行$priv1 @1
- 执行$priv2 @1
- 执行$priv3 @0
- 执行$priv4 @1

*/

global $tester;
$tester->loadModel('common');

$priv1 = $tester->common->isOpenMethod('misc', 'changelog');
$priv2 = $tester->common->isOpenMethod('tutorial', 'quit');
$priv3 = $tester->common->isOpenMethod('my', 'task');
$priv4 = $tester->common->isOpenMethod('product', 'showerrornone');

r($priv1) && p() && e('1');
r($priv2) && p() && e('1');
r($priv3) && p() && e('0');
r($priv4) && p() && e('1');

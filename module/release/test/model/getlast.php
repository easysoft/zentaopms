#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->getLast();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

zdTable('product')->config('product')->gen(5);
zdTable('branch')->config('branch')->gen(5);
zdTable('release')->config('release')->gen(5);
zdTable('user')->gen(5);
su('admin');

$products = array(0, 1, 10);
$branches = array(0, 1, 10);

global $tester;
$tester->loadModel('release');
r($tester->release->getLast($products[0], $branches[0])) && p()       && e('0');     // 测试传入产品ID跟分支ID都为空的情况
r($tester->release->getLast($products[1], $branches[0])) && p('name') && e('发布3'); // 测试传入产品ID为1，分支ID为空的情况
r($tester->release->getLast($products[1], $branches[1])) && p()       && e('0');     // 测试传入产品ID为1，分支ID为1的情况
r($tester->release->getLast($products[1], $branches[2])) && p()       && e('0');     // 测试传入产品ID为1，分支ID不存在的情况
r($tester->release->getLast($products[2], $branches[0])) && p()       && e('0');     // 测试传入产品ID不存在，分支ID为空的情况
r($tester->release->getLast($products[2], $branches[1])) && p()       && e('0');     // 测试传入产品ID不存在，分支ID为1的情况
r($tester->release->getLast($products[2], $branches[2])) && p()       && e('0');     // 测试传入产品ID不存在，分支ID不存在的情况

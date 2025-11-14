#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(10);
zenData('testtask')->gen(10);
su('admin');

/**

title=testtaskModel->isClickable();
timeout=0
cid=19207

- 测试ID为1的testtask的启动按钮是否允许被点击。 @1
- 测试ID为1的testtask的区块按钮是否允许被点击。 @1
- 测试ID为1的testtask的激活按钮是否允许被点击。 @0
- 测试ID为1的testtask的关闭按钮是否允许被点击。 @1
- 测试ID为1的testtask的运行按钮是否允许被点击。 @1
- 测试ID为1的testtask的执行用例按钮是否允许被点击。 @0
- 测试ID为2的testtask的启动按钮是否允许被点击。 @0
- 测试ID为2的testtask的区块按钮是否允许被点击。 @1
- 测试ID为2的testtask的激活按钮是否允许被点击。 @0
- 测试ID为2的testtask的关闭按钮是否允许被点击。 @1
- 测试ID为2的testtask的运行按钮是否允许被点击。 @1
- 测试ID为2的testtask的执行用例按钮是否允许被点击。 @1

*/

global $tester;

$testtask = $tester->loadModel('testtask')->fetchByID(1);
r($tester->testtask->isClickable($testtask, 'start'))    && p() && e('1'); // 测试ID为1的testtask的启动按钮是否允许被点击。
r($tester->testtask->isClickable($testtask, 'block'))    && p() && e('1'); // 测试ID为1的testtask的区块按钮是否允许被点击。
r($tester->testtask->isClickable($testtask, 'activate')) && p() && e('0'); // 测试ID为1的testtask的激活按钮是否允许被点击。
r($tester->testtask->isClickable($testtask, 'close'))    && p() && e('1'); // 测试ID为1的testtask的关闭按钮是否允许被点击。
r($tester->testtask->isClickable($testtask, 'ztrun'))    && p() && e('1'); // 测试ID为1的testtask的运行按钮是否允许被点击。
r($tester->testtask->isClickable($testtask, 'runcase'))  && p() && e('0'); // 测试ID为1的testtask的执行用例按钮是否允许被点击。

$testtask = $tester->loadModel('testtask')->fetchByID(2);
r($tester->testtask->isClickable($testtask, 'start'))    && p() && e('0'); // 测试ID为2的testtask的启动按钮是否允许被点击。
r($tester->testtask->isClickable($testtask, 'block'))    && p() && e('1'); // 测试ID为2的testtask的区块按钮是否允许被点击。
r($tester->testtask->isClickable($testtask, 'activate')) && p() && e('0'); // 测试ID为2的testtask的激活按钮是否允许被点击。
r($tester->testtask->isClickable($testtask, 'close'))    && p() && e('1'); // 测试ID为2的testtask的关闭按钮是否允许被点击。
r($tester->testtask->isClickable($testtask, 'ztrun'))    && p() && e('1'); // 测试ID为2的testtask的运行按钮是否允许被点击。
r($tester->testtask->isClickable($testtask, 'runcase'))  && p() && e('1'); // 测试ID为2的testtask的执行用例按钮是否允许被点击。

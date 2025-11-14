#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->getParentStageList();
cid=17747

- 测试查询项目1产品2阶段8父阶段信息 @执行1-1
- 测试查询项目1产品3阶段8父阶段信息 @无
- 测试查询项目4产品6阶段8父阶段信息 @执行2-1-1
- 测试查询项目10产品6阶段8父阶段信息 @无
- 测试查询项目20产品6阶段8父阶段信息 @无

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('product')->loadYaml('product')->gen(10);
zenData('project')->loadYaml('project')->gen(10);
zenData('projectproduct')->loadYaml('projectproduct')->gen(10);

global $tester;
$tester->loadModel('programplan');
$tester->programplan->app->user->admin = true;

r($tester->programplan->getParentStageList(1, 8, 2)[2]) && p()    && e('执行1-1');   // 测试查询项目1产品2阶段8父阶段信息。
r($tester->programplan->getParentStageList(1, 8, 3)[0]) && p()    && e('无');        // 测试查询项目1产品3阶段8父阶段信息。
r($tester->programplan->getParentStageList(4, 8, 6)[6]) && p()    && e('执行2-1-1'); // 测试查询项目4产品6阶段8父阶段信息。
r($tester->programplan->getParentStageList(10, 8, 6))   && p('0') && e('无');        // 测试查询项目10产品父阶段信息。
r($tester->programplan->getParentStageList(20, 8, 6))   && p('0') && e('无');        // 测试查询项目20产品父阶段信息。

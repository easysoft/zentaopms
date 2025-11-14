#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('instance')->loadYaml('instance')->gen(5);
zenData('space')->loadYaml('space')->gen(5);

/**

title=instanceModel->getByName();
timeout=0
cid=16784

- 查看获取到的第一条instance
- 查询 实例id=1 清除备份结果 @0
- 查询 实例id=2 清除备份结果 @0
- 查询 实例id=3 清除备份结果 @0
- 查询 实例id=4 清除备份结果 @0
- 查询 实例id=5 清除备份结果 @0
 */

global $tester;
$tester->loadModel('instance');

$user = new stdclass();
$user->account = 'admin';

$instance = $tester->instance->getByID(1);
r($tester->instance->cleanbackup($instance, $user)) && p(1) && e('1');

$instance = $tester->instance->getByID(2);
r($tester->instance->cleanbackup($instance, $user)) && p(1) && e('1');

$instance = $tester->instance->getByID(3);
r($tester->instance->cleanbackup($instance, $user)) && p(1) && e('1');

$instance = $tester->instance->getByID(4);
r($tester->instance->cleanbackup($instance, $user)) && p(1) && e('1');

$instance = $tester->instance->getByID(5);
r($tester->instance->cleanbackup($instance, $user)) && p(1) && e('1');

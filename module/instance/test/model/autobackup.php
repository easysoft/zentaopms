#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';


zenData('instance')->gen(5);
zenData('user')->gen(5);

/**

title=instanceModel->autoBackup();
timeout=0
cid=1

- 计算 id=1的实例执行备份 是否成功 @0
- 计算 id=2的实例执行备份 是否成功 @0
- 计算 id=3的实例执行备份 是否成功 @0
- 计算 id=4的实例执行备份 是否成功 @0
- 计算 id=5的实例执行备份 是否成功 @0
 */

global $tester;
$tester->loadModel('instance');


su('admin');
$user = new stdClass();
$user->account = 'admin';

$instance = $tester->instance->getByID(1);
r(!$tester->instance->autoBackup($instance, $user)) && p(0) && e(0);

$instance = $tester->instance->getByID(2);
r(!$tester->instance->autoBackup($instance, $user)) && p(0) && e(0);

$instance = $tester->instance->getByID(3);
r(!$tester->instance->autoBackup($instance, $user)) && p(0) && e(0);

$instance = $tester->instance->getByID(4);
r(!$tester->instance->autoBackup($instance, $user)) && p(0) && e(0);

$instance = $tester->instance->getByID(5);
r(!$tester->instance->autoBackup($instance, $user)) && p(0) && e(0);
#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('instance')->gen(1);

/**

title=instanceModel->updateByID();
timeout=0
cid=16819

- 正常编辑name的情况属性name @测试编辑名称
- 编辑memory_kb属性memory_kb @~~
- 编辑disk_gb属性disk_gb @~~
- 编辑name为空，报错第name条的0属性 @『服务名称』不能为空。
- 编辑错误status第status条的0属性 @『状态』的值应当是『installationFail,creating,initializing,pulling,startup,starting,running,suspending,suspended,installing,uninstalling,stopping,stopped,destroying,destroyed,abnormal,upgrading,unknown,scheduling』。

*/

global $tester;
$tester->loadModel('instance');

$data = new stdclass();
$data->name = '测试编辑名称';

$tester->instance->updateByID(1, $data);
$instance = $tester->instance->getByID(1);
r($instance) && p('name') && e('测试编辑名称'); // 正常编辑name的情况

$data->memory_kb = 'test';
$tester->instance->updateByID(1, $data);
$instance = $tester->instance->getByID(1);
r($instance) && p('memory_kb') && e('~~'); // 编辑memory_kb

$data->disk_gb = 'test';
$tester->instance->updateByID(1, $data);
$instance = $tester->instance->getByID(1);
r($instance) && p('disk_gb') && e('~~'); // 编辑disk_gb

$data->name = '';
$tester->instance->updateByID(1, $data);
r(dao::getError()) && p('name:0') && e('『服务名称』不能为空。'); // 编辑name为空，报错

$data->name   = 'test';
$data->status = 'test';
$tester->instance->updateByID(1, $data);
r(dao::getError()) && p('status:0', '|') && e('『状态』的值应当是『installationFail,creating,initializing,pulling,startup,starting,running,suspending,suspended,installing,uninstalling,stopping,stopped,destroying,destroyed,abnormal,upgrading,unknown,scheduling』。'); // 编辑错误status

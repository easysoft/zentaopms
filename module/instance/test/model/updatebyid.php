#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('instance')->gen(1);

/**

title=instanceModel->updateByID();
timeout=0
cid=1

- 正常编辑name的情况属性name @测试编辑名称
- 编辑name为空，报错第name条的0属性 @『名称』不能为空。

*/

global $tester;
$tester->loadModel('instance');

$data = new stdclass();
$data->name = '测试编辑名称';

$tester->instance->updateByID(1, $data);
$instance = $tester->instance->getByID(1);
r($instance) && p('name') && e('测试编辑名称'); // 正常编辑name的情况

$data->name = '';
$tester->instance->updateByID(1, $data);
r(dao::getError()) && p('name:0') && e('『名称』不能为空。'); // 编辑name为空，报错
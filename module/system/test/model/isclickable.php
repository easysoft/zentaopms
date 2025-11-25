#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
/**

title=测试 systemModel::isClickable();
timeout=0
cid=18742

- 上架状态，不显示上键按钮 @0
- 上架状态，显示下键按钮 @1
- 编辑按钮正常展示 @1
- 删除按钮正常展示 @1
- 下架状态，显示上键按钮 @1
- 下架状态，不显示下键按钮 @0
- 编辑按钮正常展示 @1
- 删除按钮正常展示 @1
*/

global $tester;
$system = $tester->loadModel('system');

$default = new stdclass();
$default->status = 'active';

$action = array('active', 'inactive', 'edit', 'delete');

r($system->isClickable($default, $action[0])) && p() && e('0'); // 上架状态，不显示上键按钮
r($system->isClickable($default, $action[1])) && p() && e('1'); // 上架状态，显示下键按钮
r($system->isClickable($default, $action[2])) && p() && e('1'); // 编辑按钮正常展示
r($system->isClickable($default, $action[3])) && p() && e('1'); // 删除按钮正常展示

$default->status = 'inactive';
r($system->isClickable($default, $action[0])) && p() && e('1'); // 下架状态，显示上键按钮
r($system->isClickable($default, $action[1])) && p() && e('0'); // 下架状态，不显示下键按钮
r($system->isClickable($default, $action[2])) && p() && e('1'); // 编辑按钮正常展示
r($system->isClickable($default, $action[3])) && p() && e('1'); // 删除按钮正常展示

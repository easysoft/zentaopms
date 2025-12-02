#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zendata('system')->gen(10);

/**

title=测试 systemModel::update();
timeout=0
cid=18749

- 默认修改应用 @1
- 修改重名提示第name条的0属性 @『应用名称』已经有『应用2』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 修改空提示第name条的0属性 @『应用名称』不能为空。
- 修改非数字第product条的0属性 @『product』应当是数字。
- 修改非法状态第status条的0属性 @『状态』不符合格式，应当为:『/active|inactive/』。
*/
global $tester;
$system = $tester->loadModel('system');

$default = new stdclass();
$default->name       = '应用11';
$default->desc       = '应用描述';
$default->editedBy   = 'admin';
$default->editedDate = '2024-01-01 00:00:00';

r($system->update(1, $default)) && p() && e('1'); // 默认修改应用
$default->name = '应用2';
$system->update(1, $default);
r(dao::getError()) && p('name:0') && e('『应用名称』已经有『应用2』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 修改重名提示
$default->name = '';
$system->update(1, $default);
r(dao::getError()) && p('name:0') && e('『应用名称』不能为空。'); // 修改空提示
$default->name    = '应用11';
$default->product = '字符串';
$system->update(1, $default);
r(dao::getError()) && p('product:0') && e('『product』应当是数字。'); // 修改非数字
$default->status  = 1;
$default->product = 1;
$system->update(1, $default, false);
r(dao::getError()) && p('status:0') && e('『状态』不符合格式，应当为:『/active|inactive/』。'); // 修改非法状态

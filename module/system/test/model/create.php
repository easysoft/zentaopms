#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zendata('system')->gen(0);

/**

title=测试 systemModel::create();
timeout=0
cid=18728

- 默认创建 @1
- 重复创建第name条的0属性 @『应用名称』已经有『应用10』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 创建空名称失败第name条的0属性 @『应用名称』不能为空。
- 创建非数字失败第product条的0属性 @『product』应当是数字。
- 创建非法状态失败第status条的0属性 @『状态』不符合格式，应当为:『/active|inactive/』。
*/
global $tester;
$system = $tester->loadModel('system');

$default = new stdclass();
$default->name        = '应用10';
$default->product     = 1;
$default->integrated  = 0;
$default->children    = '1';
$default->status      = 'active';
$default->desc        = '应用描述';
$default->createdBy   = 'admin';
$default->createdDate = '2024-01-01 00:00:00';

r($system->create($default)) && p() && e('1'); // 默认创建
$system->create($default);
r(dao::getError()) && p('systemName:0') && e('『应用名称』已经有『应用10』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 重复创建
$default->name = '';
$system->create($default);
r(dao::getError()) && p('systemName:0') && e('『应用名称』不能为空。'); // 创建空名称失败
$default->name    = '应用11';
$default->product = '字符串';
$system->create($default);
r(dao::getError()) && p('product:0') && e('『product』应当是数字。'); // 创建非数字失败
$default->product = 1;
$default->status  = 'test';
$system->create($default);
r(dao::getError()) && p('status:0') && e('『状态』不符合格式，应当为:『/active|inactive/』。'); // 创建非法状态失败

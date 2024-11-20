#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zendata('system')->gen(0);

/**

title=测试 systemModel::create();
timeout=0
cid=1


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

r($system->create($default)) && p() && e('1'); // 查询默认排序应用
$system->create($default);
r(dao::getError()) && p('name:0') && e('『应用名称』已经有『应用10』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 查询默认排序应用
$default->name = '';
$system->create($default);
r(dao::getError()) && p('name:0') && e('『应用名称』不能为空。');
$default->name    = '应用11';
$default->product = '字符串';
$system->create($default);
r(dao::getError()) && p('product:0') && e('『product』应当是数字。');
$default->product = 1;
$default->status  = 'test';
$system->create($default);
r(dao::getError()) && p('status:0') && e('『状态』不符合格式，应当为:『/active|inactive/』。');

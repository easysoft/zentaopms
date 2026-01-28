#!/usr/bin/env php
<?php

/**

title=测试 serverroomModel::create();
timeout=0
cid=18354

- 执行serverroomTest模块的createTest方法，参数是$validRoom  @1
- 执行serverroomTest模块的createTest方法，参数是$emptyName 第name条的0属性 @『名称』不能为空。
- 执行serverroomTest模块的createTest方法，参数是$emptyLine 第line条的0属性 @『线路类型』不能为空。
- 执行serverroomTest模块的createTest方法，参数是$longNameRoom 第name条的0属性 @『名称』长度应当不超过『128』，且大于『0』。
- 执行serverroomTest模块的createTest方法，参数是$allEmptyRoom 第name条的0属性 @『名称』不能为空。
- 执行serverroomTest模块的createTest方法，参数是$specialCharRoom  @2
- 执行serverroomTest模块的createTest方法，参数是$numericRoom  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('serverroom')->gen(0);
su('admin');

$serverroomTest = new serverroomModelTest();

// 准备测试数据
$validRoom = new stdclass();
$validRoom->name      = 'Test Room';
$validRoom->bandwidth = '100M';
$validRoom->city      = 'beijing';
$validRoom->line      = 'mobile';
$validRoom->provider  = 'aliyun';
$validRoom->owner     = 'admin';

$emptyName = clone $validRoom;
$emptyName->name = '';

$emptyLine = clone $validRoom;
$emptyLine->line = '';

$longNameRoom = clone $validRoom;
$longNameRoom->name = str_repeat('A', 150); // 超过数据库字段长度

$allEmptyRoom = new stdclass();
$allEmptyRoom->name      = '';
$allEmptyRoom->bandwidth = '';
$allEmptyRoom->city      = '';
$allEmptyRoom->line      = '';
$allEmptyRoom->provider  = '';
$allEmptyRoom->owner     = '';

$specialCharRoom = clone $validRoom;
$specialCharRoom->name = 'Test@#$%机房';
$specialCharRoom->city = '北京&上海';

$numericRoom = clone $validRoom;
$numericRoom->name = 'Room001';
$numericRoom->bandwidth = '1000';

r($serverroomTest->createTest($validRoom)) && p() && e('1');
r($serverroomTest->createTest($emptyName)) && p('name:0') && e('『名称』不能为空。');
r($serverroomTest->createTest($emptyLine)) && p('line:0') && e('『线路类型』不能为空。');
r($serverroomTest->createTest($longNameRoom)) && p('name:0') && e('『名称』长度应当不超过『128』，且大于『0』。');
r($serverroomTest->createTest($allEmptyRoom)) && p('name:0') && e('『名称』不能为空。');
r($serverroomTest->createTest($specialCharRoom)) && p() && e('2');
r($serverroomTest->createTest($numericRoom)) && p() && e('3');
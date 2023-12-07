#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/serverroom.class.php';

zdTable('serverroom')->gen(0);
su('admin');

/**

title=serverroomModel->getList();
timeout=0
cid=1

*/

$roomModel = new serverroomTest();

$roomData = new stdclass();
$roomData->name      = 'room1';
$roomData->bandwidth = '100M';
$roomData->city      = 'beijin';
$roomData->line      = 'mobile';
$roomData->provider  = 'aliyun';
$roomData->owner     = 'admin';

$emptName = clone $roomData;
$emptName->name = '';

$emptyLine = clone $roomData;
$emptyLine->line = '';

r($roomModel->createTest($roomData))  && p() && e('1');
r($roomModel->createTest($emptName))  && p('name:0') && e('『名称』不能为空。');
r($roomModel->createTest($emptyLine)) && p('line:0') && e('『线路类型』不能为空。');

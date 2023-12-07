#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/serverroom.class.php';

zdTable('serverroom')->config('serverroom')->gen(3);
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

$realID  = 1;
$emptyID = 0;
$failID  = 999;

r($roomModel->updateTest($failID, $roomData))  && p() && e('0');
r($roomModel->updateTest($emptyID, $roomData)) && p() && e('0');

r($roomModel->updateTest($realID, $roomData))  && p('0:field,old,new') && e('name,机房1,room1');
r($roomModel->updateTest($realID, $emptName))  && p('name:0') && e('『名称』不能为空。');
r($roomModel->updateTest($realID, $emptyLine)) && p('line:0') && e('『线路类型』不能为空。');

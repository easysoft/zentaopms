#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->updateLaneColumn();
cid=1
pid=1

测试修改列1信息 >> name,测试修改列名1,未开始;color,#2b519c,#333
测试修改列2信息 >> name,测试修改列名2,进行中;color,#e48610,#333
测试修改列3信息 >> name,测试修改列名3,已完成;color,#d2313d,#333
测试修改列4信息 >> 『看板列名称』不能为空。
测试修改列5信息 >> 『看板列名称』不能为空。

*/
$columnIDList = array('1', '2', '3', '4', '5');
$nameList     = array('测试修改列名1', '测试修改列名2', '测试修改列名3', '', '  ');
$colorList    = array('#2b519c', '#e48610', '#d2313d', '#2a9f23', '#777');

$kanban = new kanbanTest();

r($kanban->updateLaneColumnTest($columnIDList[0], $nameList[0], $colorList[0])) && p('0:field,new,old;1:field,new,old') && e('name,测试修改列名1,未开始;color,#2b519c,#333'); // 测试修改列1信息
r($kanban->updateLaneColumnTest($columnIDList[1], $nameList[1], $colorList[1])) && p('0:field,new,old;1:field,new,old') && e('name,测试修改列名2,进行中;color,#e48610,#333'); // 测试修改列2信息
r($kanban->updateLaneColumnTest($columnIDList[2], $nameList[2], $colorList[2])) && p('0:field,new,old;1:field,new,old') && e('name,测试修改列名3,已完成;color,#d2313d,#333'); // 测试修改列3信息
r($kanban->updateLaneColumnTest($columnIDList[3], $nameList[3], $colorList[3])) && p('name:0')                          && e('『看板列名称』不能为空。');                     // 测试修改列4信息
r($kanban->updateLaneColumnTest($columnIDList[4], $nameList[4], $colorList[4])) && p('name:0')                          && e('『看板列名称』不能为空。');                     // 测试修改列5信息
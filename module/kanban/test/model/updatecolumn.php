#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->gen(5);

/**

title=测试修改列1信息 >> name,测试修改列名1,未开始;color,
timeout=0
cid=2

- 测试修改列1信息
 - 第0条的field属性 @name
 - 第0条的new属性 @测试修改列名1
 - 第0条的old属性 @未开始
 - 第1条的field属性 @color
 - 第1条的new属性 @2b519c
- 测试修改列2信息
 - 第0条的field属性 @name
 - 第0条的new属性 @测试修改列名2
 - 第0条的old属性 @进行中
 - 第1条的field属性 @color
 - 第1条的new属性 @e48610
- 测试修改列3信息
 - 第0条的field属性 @name
 - 第0条的new属性 @测试修改列名3
 - 第0条的old属性 @已完成
 - 第1条的field属性 @color
 - 第1条的new属性 @d2313d
- 测试修改列4信息第name条的0属性 @『看板列名称』不能为空。

*/
$columnIDList = array('1', '2', '3', '4', '5');
$nameList     = array('测试修改列名1', '测试修改列名2', '测试修改列名3', '');
$colorList    = array('2b519c', 'e48610', 'd2313d', '2a9f23', '777');

$kanban = new kanbanTest();

r($kanban->updateColumnTest($columnIDList[0], $nameList[0], $colorList[0])) && p('0:field,new,old;1:field,new') && e('name,测试修改列名1,未开始;color,2b519c'); // 测试修改列1信息
r($kanban->updateColumnTest($columnIDList[1], $nameList[1], $colorList[1])) && p('0:field,new,old;1:field,new') && e('name,测试修改列名2,进行中;color,e48610'); // 测试修改列2信息
r($kanban->updateColumnTest($columnIDList[2], $nameList[2], $colorList[2])) && p('0:field,new,old;1:field,new') && e('name,测试修改列名3,已完成;color,d2313d'); // 测试修改列3信息
r($kanban->updateColumnTest($columnIDList[3], $nameList[3], $colorList[3])) && p('name:0')                      && e('『看板列名称』不能为空。');               // 测试修改列4信息
#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('product')->gen(50);
zdTable('productplan')->gen(50);
zdTable('branch')->gen(50);

/**

title=测试 kanbanModel->getPlanKanban();
timeout=0
cid=1

- 测试获取产品1的计划看板
 - 第0条的name属性 @all
 - 第0条的title属性 @所有
- 测试获取产品2的计划看板
 - 第1条的name属性 @5
 - 第1条的title属性 @1.1
- 测试获取产品3的计划看板
 - 第0条的name属性 @wait
 - 第0条的title属性 @未开始
- 测试获取产品1的计划看板,传入分支
 - 第0条的name属性 @wait
 - 第0条的title属性 @未开始
- 测试获取产品2的计划看板，传入分支
 - 第0条的name属性 @wait
 - 第0条的title属性 @未开始

*/

$productIDList = array('1', '2', '3');
$branchIDList  = array('0', '1', '3');

$kanban = new kanbanTest();

r($kanban->getPlanKanbanTest($productIDList[0])[0]['items'][0]['data']['lanes'])   && p('0:name,title') && e('all,所有');                   // 测试获取产品1的计划看板
r($kanban->getPlanKanbanTest($productIDList[1])[0]['items'][0]['data']['items']['all']['wait']) && p('1:name,title') && e('5,1.1');         // 测试获取产品2的计划看板
r($kanban->getPlanKanbanTest($productIDList[2])[0]['items'][0]['data']['cols']) && p('0:name,title') && e('wait,未开始');                   // 测试获取产品3的计划看板
r($kanban->getPlanKanbanTest($productIDList[0], $branchIDList[1])[0]['items'][0]['data']['cols']) && p('0:name,title') && e('wait,未开始'); // 测试获取产品1的计划看板,传入分支
r($kanban->getPlanKanbanTest($productIDList[1], $branchIDList[2])[0]['items'][0]['data']['cols']) && p('0:name,title') && e('wait,未开始'); // 测试获取产品2的计划看板，传入分支
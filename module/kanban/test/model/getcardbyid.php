#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancard')->gen(5);

/**

title=测试 kanbanModel->getCardByID();
timeout=0
cid=1

- 测试查询卡片1的信息
 - 属性name @卡片1
 - 属性status @doing
 - 属性pri @3
 - 属性progress @0
- 测试查询卡片2的信息
 - 属性name @卡片2
 - 属性status @doing
 - 属性pri @3
 - 属性progress @50
- 测试查询卡片3的信息
 - 属性name @卡片3
 - 属性status @done
 - 属性pri @3
 - 属性progress @100
- 测试查询卡片4的信息
 - 属性name @卡片4
 - 属性status @doing
 - 属性pri @3
 - 属性progress @0
- 测试查询卡片5的信息
 - 属性name @卡片5
 - 属性status @doing
 - 属性pri @3
 - 属性progress @50
- 测试查询不存在卡片的信息 @0

*/

$cardIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->getCardByIDTest($cardIDList[0])) && p('name,status,pri,progress') && e('卡片1,doing,3,0'); // 测试查询卡片1的信息
r($kanban->getCardByIDTest($cardIDList[1])) && p('name,status,pri,progress') && e('卡片2,doing,3,50');  // 测试查询卡片2的信息
r($kanban->getCardByIDTest($cardIDList[2])) && p('name,status,pri,progress') && e('卡片3,done,3,100');  // 测试查询卡片3的信息
r($kanban->getCardByIDTest($cardIDList[3])) && p('name,status,pri,progress') && e('卡片4,doing,3,0');  // 测试查询卡片4的信息
r($kanban->getCardByIDTest($cardIDList[4])) && p('name,status,pri,progress') && e('卡片5,doing,3,50'); // 测试查询卡片5的信息
r($kanban->getCardByIDTest($cardIDList[5])) && p()                           && e('0');                // 测试查询不存在卡片的信息
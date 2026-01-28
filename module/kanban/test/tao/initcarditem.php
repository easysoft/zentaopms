#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::initCardItem();
timeout=0
cid=16988

- 执行kanbanTest模块的initCardItemTest方法，参数是1, 1, 1, array 
 - 属性title @测试卡片1
 - 属性id @1
 - 属性assignedTo @admin
- 执行kanbanTest模块的initCardItemTest方法，参数是2, 2, 2, array 
 - 属性assignedTo @user1
 - 属性realnames @用户1 
- 执行kanbanTest模块的initCardItemTest方法，参数是3, 3, 3, array 
 - 属性cardType @common
 - 属性pri @3
 - 属性color @#FFC20E
- 执行kanbanTest模块的initCardItemTest方法，参数是4, 4, 4, array 
 - 属性parent @0
 - 属性progress @30.00
 - 属性status @wait
- 执行kanbanTest模块的initCardItemTest方法，参数是5, 5, 5, array 
 - 属性group @2
 - 属性region @1
 - 属性fromType @task

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$table = zenData('kanbancard');
$table->id->range('1-5');
$table->kanban->range('1{5}');
$table->region->range('1,2,1,2,1');
$table->group->range('1,2,3,1,2');
$table->name->range('测试卡片1,测试卡片2,测试卡片3,测试卡片4,测试卡片5');
$table->pri->range('1,2,3,4,1');
$table->color->range('#F51616,#FF8000,#FFC20E,#14C757,#006BFF');
$table->assignedTo->range('admin,user1,user2,admin,user1');
$table->progress->range('0,10,20,30,40');
$table->status->range('wait,doing,done,wait,doing');
$table->fromID->range('101,102,103,104,105');
$table->fromType->range('story,task,bug,story,task');
$table->desc->range('描述1,描述2,描述3,描述4,描述5');
$table->estimate->range('8,16,24,8,16');
$table->gen(5);

$cellTable = zenData('kanbancell');
$cellTable->id->range('1-5');
$cellTable->kanban->range('1{5}');
$cellTable->lane->range('1,2,1,2,1');
$cellTable->column->range('1,2,3,4,5');
$cellTable->type->range('common{5}');
$cellTable->cards->range('1,2,3,4,5');
$cellTable->gen(5);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,test1,test2');
$userTable->realname->range('管理员,用户1,用户2,测试1,测试2');
$userTable->avatar->range('avatar1.jpg,avatar2.jpg,avatar3.jpg,avatar4.jpg,avatar5.jpg');
$userTable->gen(5);

su('admin');

$kanbanTest = new kanbanTaoTest();

r($kanbanTest->initCardItemTest(1, 1, 1, array('admin' => 'avatar1.jpg', 'user1' => 'avatar2.jpg'), array('admin' => '管理员', 'user1' => '用户1'))) && p('title,id,assignedTo') && e('测试卡片1,1,admin');
r($kanbanTest->initCardItemTest(2, 2, 2, array('user1' => 'avatar2.jpg'), array('user1' => '用户1'))) && p('assignedTo,realnames') && e('user1,用户1 ');
r($kanbanTest->initCardItemTest(3, 3, 3, array('user2' => 'avatar3.jpg'), array('user2' => '用户2'))) && p('cardType,pri,color') && e('common,3,#FFC20E');
r($kanbanTest->initCardItemTest(4, 4, 4, array(), array())) && p('parent,progress,status') && e('0,30.00,wait');
r($kanbanTest->initCardItemTest(5, 5, 5, array('user1' => 'avatar2.jpg', 'user2' => 'avatar3.jpg'), array('user1' => '用户1', 'user2' => '用户2'))) && p('group,region,fromType') && e('2,1,task');
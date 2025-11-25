#!/usr/bin/env php
<?php

/**

title=测试 todoZen::beforeBatchEdit();
timeout=0
cid=0

- 步骤1：空数组 @0
- 步骤2：正常自定义类型第1条的name属性 @测试待办
- 步骤3：模块类型task
 - 第2条的objectID属性 @100
 - 第2条的name属性 @~~
- 步骤4：模块类型缺少objectID @0
- 步骤5：自定义类型缺少name @0
- 步骤6：正常时间范围(switchTime全局影响)
 - 第5条的begin属性 @2400
 - 第5条的end属性 @2400
- 步骤7：switchTime处理
 - 第6条的begin属性 @2400
 - 第6条的end属性 @2400

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1:空数组输入,期望返回空数组
r($todoTest->beforeBatchEditTest(array())) && p() && e('0'); // 步骤1：空数组

// 测试步骤2:正常自定义类型待办(包含name字段),期望成功返回
$todo2 = new stdClass();
$todo2->id = 1;
$todo2->type = 'custom';
$todo2->name = '测试待办';
$todo2->begin = '0900';
$todo2->end = '1800';
$todos2 = array(1 => $todo2);
r($todoTest->beforeBatchEditTest($todos2)) && p('1:name') && e('测试待办'); // 步骤2：正常自定义类型

// 测试步骤3:正常模块类型待办(task类型),期望成功设置objectID
$todo3 = new stdClass();
$todo3->id = 2;
$todo3->type = 'task';
$todo3->task = 100;
$todo3->begin = '0900';
$todo3->end = '1800';
$todos3 = array(2 => $todo3);
r($todoTest->beforeBatchEditTest($todos3)) && p('2:objectID,name') && e('100,~~'); // 步骤3：模块类型task

// 测试步骤4:模块类型待办缺少objectID,期望返回false
$todo4 = new stdClass();
$todo4->id = 3;
$todo4->type = 'story';
$todo4->begin = '0900';
$todo4->end = '1800';
$todos4 = array(3 => $todo4);
r($todoTest->beforeBatchEditTest($todos4)) && p() && e('0'); // 步骤4：模块类型缺少objectID

// 测试步骤5:自定义类型待办缺少name,期望返回false
$todo5 = new stdClass();
$todo5->id = 4;
$todo5->type = 'custom';
$todo5->name = '';
$todo5->begin = '0900';
$todo5->end = '1800';
$todos5 = array(4 => $todo5);
r($todoTest->beforeBatchEditTest($todos5)) && p() && e('0'); // 步骤5：自定义类型缺少name

// 测试步骤6:正常时间范围,期望成功处理
$todo6 = new stdClass();
$todo6->id = 5;
$todo6->type = 'custom';
$todo6->name = '测试正常时间';
$todo6->begin = '0900';
$todo6->end = '1800';
$todos6 = array(5 => $todo6);
r($todoTest->beforeBatchEditTest($todos6)) && p('5:begin,end') && e('2400,2400'); // 步骤6：正常时间范围(switchTime全局影响)

// 测试步骤7:switchTime处理,期望时间设置为2400
$todo7 = new stdClass();
$todo7->id = 6;
$todo7->type = 'custom';
$todo7->name = '测试时间切换';
$todo7->begin = '0900';
$todo7->end = '1800';
$todo7->switchTime = true;
$todos7 = array(6 => $todo7);
r($todoTest->beforeBatchEditTest($todos7)) && p('6:begin,end') && e('2400,2400'); // 步骤7：switchTime处理
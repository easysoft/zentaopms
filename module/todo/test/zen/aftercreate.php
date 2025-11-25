#!/usr/bin/env php
<?php

/**

title=测试 todoZen::afterCreate();
timeout=0
cid=19286

- 执行todoTest模块的afterCreateTest方法，参数是$todo1, $form1 属性fileUpdated @1
- 执行todoTest模块的afterCreateTest方法，参数是$todo2, $form2 属性fileUpdated @0
- 执行todoTest模块的afterCreateTest方法，参数是$todo3, $form3 属性cycleCreated @1
- 执行todoTest模块的afterCreateTest方法，参数是$todo4, $form4 属性cycleCreated @0
- 执行todoTest模块的afterCreateTest方法，参数是$todo5, $form5
 - 属性scoreCreated @1
 - 属性actionCreated @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('todo');
$table->id->range('1-10');
$table->account->range('admin,user1,user2');
$table->name->range('测试待办1,测试待办2,测试待办3');
$table->type->range('custom,task,bug');
$table->status->range('wait,doing,done');
$table->cycle->range('0{8},1{2}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 测试步骤1：有uid的表单数据
$todo1 = new stdClass();
$todo1->id = 1;
$todo1->name = '测试待办1';
$todo1->cycle = 0;
$form1 = new stdClass();
$form1->data = new stdClass();
$form1->data->uid = 'test-uid-123';
r($todoTest->afterCreateTest($todo1, $form1)) && p('fileUpdated') && e('1');

// 测试步骤2：无uid的表单数据
$todo2 = new stdClass();
$todo2->id = 2;
$todo2->name = '测试待办2';
$todo2->cycle = 0;
$form2 = new stdClass();
$form2->data = new stdClass();
r($todoTest->afterCreateTest($todo2, $form2)) && p('fileUpdated') && e('0');

// 测试步骤3：有cycle的待办对象
$todo3 = new stdClass();
$todo3->id = 3;
$todo3->name = '测试待办3';
$todo3->cycle = 1;
$form3 = new stdClass();
$form3->data = new stdClass();
r($todoTest->afterCreateTest($todo3, $form3)) && p('cycleCreated') && e('1');

// 测试步骤4：无cycle的待办对象
$todo4 = new stdClass();
$todo4->id = 4;
$todo4->name = '测试待办4';
$todo4->cycle = 0;
$form4 = new stdClass();
$form4->data = new stdClass();
r($todoTest->afterCreateTest($todo4, $form4)) && p('cycleCreated') && e('0');

// 测试步骤5：有效id的待办对象，验证积分和action创建
$todo5 = new stdClass();
$todo5->id = 5;
$todo5->name = '测试待办5';
$todo5->cycle = 0;
$form5 = new stdClass();
$form5->data = new stdClass();
r($todoTest->afterCreateTest($todo5, $form5)) && p('scoreCreated,actionCreated') && e('1,1');
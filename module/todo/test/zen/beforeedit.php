#!/usr/bin/env php
<?php

/**

title=测试 todoZen::beforeEdit();
timeout=0
cid=0

- 执行todoTest模块的beforeEditTest方法，参数是1, $normalForm
 - 属性name @更新后的待办名称
 - 属性pri @1
 - 属性type @custom
- 执行todoTest模块的beforeEditTest方法，参数是2, $taskForm
 - 属性objectID @100
 - 属性type @task
- 执行todoTest模块的beforeEditTest方法，参数是3, $emptyDateForm 属性date @2030-01-01
- 执行todoTest模块的beforeEditTest方法，参数是4, $privateForm
 - 属性private @1
 - 属性assignedTo @admin
 - 属性assignedBy @admin
- 执行todoTest模块的beforeEditTest方法，参数是5, $invalidTimeForm  @0
- 执行todoTest模块的beforeEditTest方法，参数是6, $noObjectIdForm  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

zendata('todo')->loadYaml('todo_beforeedit', false, 2)->gen(10);
zendata('user')->loadYaml('user', false, 2)->gen(5);

su('admin');

$todoTest = new todoTest();

// 测试1: 正常编辑自定义类型待办
$normalForm = new stdClass();
$normalForm->data = new stdClass();
$normalForm->data->type = 'custom';
$normalForm->data->name = '更新后的待办名称';
$normalForm->data->pri = 1;
$normalForm->data->date = '2023-12-10';
$normalForm->data->begin = '0900';
$normalForm->data->end = '1800';
$normalForm->data->assignedTo = 'admin';

r($todoTest->beforeEditTest(1, $normalForm)) && p('name,pri,type') && e('更新后的待办名称,1,custom');

// 测试2: 编辑任务类型待办并验证objectID处理
$taskForm = new stdClass();
$taskForm->data = new stdClass();
$taskForm->data->type = 'task';
$taskForm->data->objectID = 100;
$taskForm->data->pri = 2;
$taskForm->data->date = '2023-12-11';
$taskForm->data->begin = '1000';
$taskForm->data->end = '1900';
$taskForm->data->assignedTo = 'admin';

r($todoTest->beforeEditTest(2, $taskForm)) && p('objectID,type') && e('100,task');

// 测试3: 空日期处理 - 验证FUTURE_TIME设置
$emptyDateForm = new stdClass();
$emptyDateForm->data = new stdClass();
$emptyDateForm->data->type = 'custom';
$emptyDateForm->data->name = '空日期待办';
$emptyDateForm->data->pri = 2;
$emptyDateForm->data->date = '';
$emptyDateForm->data->begin = '1100';
$emptyDateForm->data->end = '1700';
$emptyDateForm->data->assignedTo = 'admin';

r($todoTest->beforeEditTest(3, $emptyDateForm)) && p('date') && e('2030-01-01');

// 测试4: 私有属性处理
$privateForm = new stdClass();
$privateForm->data = new stdClass();
$privateForm->data->type = 'custom';
$privateForm->data->name = '私有待办';
$privateForm->data->pri = 1;
$privateForm->data->date = '2023-12-13';
$privateForm->data->begin = '1400';
$privateForm->data->end = '1600';
$privateForm->data->private = 'on';
$privateForm->data->assignedTo = 'user1';

r($todoTest->beforeEditTest(4, $privateForm)) && p('private,assignedTo,assignedBy') && e('1,admin,admin');

// 测试5: 时间范围验证(结束时间小于开始时间)
$invalidTimeForm = new stdClass();
$invalidTimeForm->data = new stdClass();
$invalidTimeForm->data->type = 'custom';
$invalidTimeForm->data->name = '时间错误待办';
$invalidTimeForm->data->pri = 3;
$invalidTimeForm->data->date = '2023-12-12';
$invalidTimeForm->data->begin = '1800';
$invalidTimeForm->data->end = '0900';
$invalidTimeForm->data->assignedTo = 'admin';

r($todoTest->beforeEditTest(5, $invalidTimeForm)) && p() && e('0');

// 测试6: 测试模块类型缺少objectID的验证
$noObjectIdForm = new stdClass();
$noObjectIdForm->data = new stdClass();
$noObjectIdForm->data->type = 'bug';
$noObjectIdForm->data->objectID = 0;
$noObjectIdForm->data->pri = 1;
$noObjectIdForm->data->date = '2023-12-14';
$noObjectIdForm->data->begin = '1000';
$noObjectIdForm->data->end = '1800';
$noObjectIdForm->data->assignedTo = 'admin';

r($todoTest->beforeEditTest(6, $noObjectIdForm)) && p() && e('0');
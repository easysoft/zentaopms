#!/usr/bin/env php
<?php

/**

title=- 步骤4：任务类型待办，测试名称格式化第1条的name属性 @任务20(
timeout=0
cid=20

- 步骤1：正常用户待办，测试用户名格式化第0条的assignedTo属性 @用户1
- 步骤2：未来日期待办，测试日期转换第3条的date属性 @未来
- 步骤3：空时间待办，测试时间处理第4条的begin属性 @~~
- 步骤4：任务类型待办，测试名称格式化第1条的name属性 @任务20(#20)
- 步骤5：私有待办，测试描述隐私保护第4条的desc属性 @这是一个私人待办，暂时不能显示详细信息！

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('todo')->loadYaml('todo_assembleexportdata', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 设置全局语言变量
global $lang;
if(!isset($lang)) $lang = new stdClass();
if(!isset($lang->todo)) $lang->todo = new stdClass();
$lang->todo->future = '未来';
$lang->todo->thisIsPrivate = '这是一个私人待办，暂时不能显示详细信息！';

// 5. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 6. 🔴 强制要求：必须包含至少5个测试步骤

// 测试数据准备
$todos = array();
for($i = 0; $i < 5; $i++) {
    $todo = new stdClass();
    $todo->id = $i + 1;
    $todo->account = 'admin';
    $todo->assignedTo = 'user1';
    $todo->name = '测试待办' . ($i + 1);
    $todo->type = ($i == 0) ? 'custom' : (($i == 1) ? 'task' : (($i == 2) ? 'bug' : (($i == 3) ? 'story' : 'epic')));
    $todo->objectID = ($i == 0) ? 0 : (($i + 1) * 10);
    $todo->pri = ($i % 3) + 1;
    $todo->status = 'wait';
    $todo->begin = ($i == 4) ? '2400' : '0900';
    $todo->end = ($i == 4) ? '2400' : '1800';
    $todo->date = ($i == 3) ? '2030-01-01' : '2023-12-01';
    $todo->private = ($i == 4) ? 1 : 0;
    $todo->desc = '描述' . ($i + 1);
    $todos[$i] = $todo;
}

// 准备关联数据对象
$assemble = new stdClass();
$assemble->users = array('admin' => '管理员', 'user1' => '用户1', 'user2' => '用户2');
$assemble->bugs = array(30 => '缺陷30');
$assemble->tasks = array(20 => '任务20');
$assemble->stories = array(40 => '需求40');
$assemble->epics = array(50 => '史诗50');
$assemble->requirements = array();
$assemble->testTasks = array();
$assemble->issues = array();
$assemble->risks = array();
$assemble->opportunities = array();

// 准备语言对象
$todoLang = new stdClass();
$todoLang->typeList = array('custom' => '自定义', 'task' => '任务', 'bug' => '缺陷', 'story' => '需求', 'epic' => '史诗');
$todoLang->priList = array(1 => '高', 2 => '中', 3 => '低');
$todoLang->statusList = array('wait' => '未开始', 'doing' => '进行中', 'done' => '已完成');
$todoLang->future = '未来';
$todoLang->thisIsPrivate = '这是一个私人待办，暂时不能显示详细信息！';

// 准备时间数组
$times = array(
    '0900' => '09:00',
    '1800' => '18:00',
    '2400' => ''
);

r($todoTest->assembleExportDataTest($todos, $assemble, $todoLang, $times)) && p('0:assignedTo') && e('用户1'); // 步骤1：正常用户待办，测试用户名格式化
r($todoTest->assembleExportDataTest($todos, $assemble, $todoLang, $times)) && p('3:date') && e('未来'); // 步骤2：未来日期待办，测试日期转换
r($todoTest->assembleExportDataTest($todos, $assemble, $todoLang, $times)) && p('4:begin') && e('~~'); // 步骤3：空时间待办，测试时间处理
r($todoTest->assembleExportDataTest($todos, $assemble, $todoLang, $times)) && p('1:name') && e('任务20(#20)'); // 步骤4：任务类型待办，测试名称格式化
r($todoTest->assembleExportDataTest($todos, $assemble, $todoLang, $times)) && p('4:desc') && e('这是一个私人待办，暂时不能显示详细信息！'); // 步骤5：私有待办，测试描述隐私保护
#!/usr/bin/env php
<?php

/**

title=- 步骤5：Bug类型待办名称拼接验证 @缺陷1(
timeout=0
cid=1

- 步骤1：正常待办数据导出验证第0条的name属性 @测试待办1
- 步骤2：待办时间格式化验证第1条的begin属性 @~~
- 步骤3：指派用户名称转换验证第0条的assignedTo属性 @管理员
- 步骤4：未来日期待办处理验证第1条的date属性 @待办
- 步骤5：Bug类型待办名称拼接验证第1条的name属性 @缺陷1(#1)

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 准备测试数据
$todos = array(
    (object)array(
        'id' => 1,
        'name' => '测试待办1',
        'type' => 'custom',
        'account' => 'admin',
        'assignedTo' => 'admin',
        'begin' => '0900',
        'end' => '1800',
        'date' => '2023-12-01',
        'pri' => 1,
        'status' => 'wait',
        'objectID' => 0,
        'private' => 0
    ),
    (object)array(
        'id' => 2,
        'name' => '测试待办2',
        'type' => 'bug',
        'account' => 'admin',
        'assignedTo' => 'admin',
        'begin' => '2400',
        'end' => '2400',
        'date' => '2030-01-01',
        'pri' => 2,
        'status' => 'done',
        'objectID' => 1,
        'private' => 0
    ),
    (object)array(
        'id' => 3,
        'name' => '测试待办3',
        'type' => 'task',
        'account' => 'user1',
        'assignedTo' => 'user1',
        'begin' => '1000',
        'end' => '1700',
        'date' => '2023-12-15',
        'pri' => 3,
        'status' => 'doing',
        'objectID' => 10,
        'private' => 0
    )
);

// 准备关联数据对象
$assemble = new stdClass();
$assemble->users = array('admin' => '管理员', 'user1' => '用户1');
$assemble->bugs = array(1 => '缺陷1', 2 => '缺陷2');
$assemble->tasks = array(10 => '任务10', 20 => '任务20');
$assemble->stories = array(5 => '需求5', 15 => '需求15');
$assemble->epics = array(3 => '史诗3', 13 => '史诗13');
$assemble->requirements = array(7 => '用户需求7', 17 => '用户需求17');
$assemble->testTasks = array(1 => '测试任务1', 2 => '测试任务2');
$assemble->issues = array(1 => '问题1', 2 => '问题2');
$assemble->risks = array(1 => '风险1', 2 => '风险2');
$assemble->opportunities = array(1 => '机会1', 2 => '机会2');

// 准备语言对象
$todoLang = new stdClass();
$todoLang->typeList = array('custom' => '自定义', 'bug' => '缺陷', 'task' => '任务');
$todoLang->priList = array(1 => '高', 2 => '中', 3 => '低');
$todoLang->statusList = array('wait' => '未开始', 'doing' => '进行中', 'done' => '已完成');
$todoLang->future = '待办';

global $lang;
if(!isset($lang)) $lang = new stdClass();
if(!isset($lang->todo)) $lang->todo = new stdClass();
$lang->todo->thisIsPrivate = '这是私有待办';
$lang->todo->future = '待办';

// 准备时间数组
$times = array(
    '0900' => '09:00',
    '1000' => '10:00',
    '1700' => '17:00',
    '1800' => '18:00'
);

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($todoTest->assembleExportDataTest($todos, $assemble, $todoLang, $times)) && p('0:name') && e('测试待办1'); // 步骤1：正常待办数据导出验证
r($todoTest->assembleExportDataTest($todos, $assemble, $todoLang, $times)) && p('1:begin') && e('~~'); // 步骤2：待办时间格式化验证
r($todoTest->assembleExportDataTest($todos, $assemble, $todoLang, $times)) && p('0:assignedTo') && e('管理员'); // 步骤3：指派用户名称转换验证
r($todoTest->assembleExportDataTest($todos, $assemble, $todoLang, $times)) && p('1:date') && e('待办'); // 步骤4：未来日期待办处理验证
r($todoTest->assembleExportDataTest($todos, $assemble, $todoLang, $times)) && p('1:name') && e('缺陷1(#1)'); // 步骤5：Bug类型待办名称拼接验证
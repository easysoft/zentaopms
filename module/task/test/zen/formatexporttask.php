#!/usr/bin/env php
<?php

/**

title=- 步骤1：正常情况项目名称映射属性project @项目一(
timeout=0
cid=1

- 步骤1：正常情况项目名称映射属性project @项目一(#1)
- 步骤2：CSV格式特殊字符处理属性desc @包含\n换行和""引号""还有 空格
- 步骤3：日期格式处理属性openedDate @2023-01-01
- 步骤4：用户名称映射属性openedBy @管理员
- 步骤5：零值日期处理属性finishedDate @

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（不使用数据库数据生成）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 准备测试数据
$projects = array(
    1 => '项目一',
    2 => '项目二',
    3 => '项目三'
);

$executions = array(
    1 => '执行一',
    2 => '执行二',
    3 => '执行三'
);

$users = array(
    'admin' => '管理员',
    'user1' => '用户一',
    'user2' => '用户二',
    'user3' => '用户三'
);

// 创建任务对象进行测试
$normalTask = (object)array(
    'id' => 1,
    'name' => '测试任务',
    'project' => 1,
    'execution' => 1,
    'type' => 'devel',
    'pri' => 3,
    'status' => 'doing',
    'closedReason' => '',
    'mode' => 'linear',
    'openedBy' => 'admin',
    'assignedTo' => 'user1',
    'finishedBy' => '',
    'canceledBy' => '',
    'closedBy' => '',
    'lastEditedBy' => 'admin',
    'openedDate' => '2023-01-01 10:00:00',
    'assignedDate' => '2023-01-02 11:00:00',
    'finishedDate' => '0000-00-00 00:00:00',
    'canceledDate' => '0000-00-00 00:00:00',
    'closedDate' => '0000-00-00 00:00:00',
    'lastEditedDate' => '2023-01-15 10:00:00',
    'estimate' => 16.0,
    'consumed' => 8.0,
    'left' => 8.0,
    'desc' => '普通任务描述'
);

$csvTask = (object)array(
    'id' => 2,
    'name' => 'CSV测试任务',
    'project' => 2,
    'execution' => 2,
    'type' => 'test',
    'pri' => 2,
    'status' => 'done',
    'closedReason' => 'done',
    'mode' => 'multi',
    'openedBy' => 'user1',
    'assignedTo' => 'user2',
    'finishedBy' => 'user2',
    'canceledBy' => '',
    'closedBy' => 'user2',
    'lastEditedBy' => 'user2',
    'openedDate' => '2023-01-03 10:00:00',
    'assignedDate' => '2023-01-04 11:00:00',
    'finishedDate' => '2023-01-05 12:00:00',
    'canceledDate' => '0000-00-00 00:00:00',
    'closedDate' => '2023-01-06 13:00:00',
    'lastEditedDate' => '2023-01-16 10:00:00',
    'estimate' => 24.0,
    'consumed' => 20.0,
    'left' => 4.0,
    'desc' => '包含<br />换行和"引号"还有&nbsp;空格的描述'
);

// 模拟CSV导出的post数据
$_POST['fileType'] = 'excel';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$_POST['fileType'] = 'excel';
r($taskTest->formatExportTaskTest($normalTask, $projects, $executions, $users)) && p('project') && e('项目一(#1)'); // 步骤1：正常情况项目名称映射
$_POST['fileType'] = 'csv';
r($taskTest->formatExportTaskTest($csvTask, $projects, $executions, $users)) && p('desc') && e('包含\n换行和""引号""还有 空格'); // 步骤2：CSV格式特殊字符处理
$_POST['fileType'] = 'excel';
r($taskTest->formatExportTaskTest($normalTask, $projects, $executions, $users)) && p('openedDate') && e('2023-01-01'); // 步骤3：日期格式处理
r($taskTest->formatExportTaskTest($normalTask, $projects, $executions, $users)) && p('openedBy') && e('管理员'); // 步骤4：用户名称映射
$zeroDateTask = clone $normalTask;
$zeroDateTask->finishedDate = '0000-00-00 00:00:00';
r($taskTest->formatExportTaskTest($zeroDateTask, $projects, $executions, $users)) && p('finishedDate') && e(''); // 步骤5：零值日期处理
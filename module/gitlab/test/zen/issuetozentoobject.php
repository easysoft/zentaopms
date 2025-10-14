#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::issueToZentaoObject();
timeout=0
cid=0

- 测试正常task类型转换
 - 属性name @Test Task Title
 - 属性desc @Test task description<br><br><a href="https://gitlab.example.com/issues/1" target="_blank">https://gitlab.example.com/issues/1</a>
- 测试正常story类型转换
 - 属性title @Test Story Title
 - 属性spec @Test story description<br><br><a href="https://gitlab.example.com/issues/2" target="_blank">https://gitlab.example.com/issues/2</a>
- 测试正常bug类型转换
 - 属性title @Test Bug Title
 - 属性steps @Test bug description<br><br><a href="https://gitlab.example.com/issues/3" target="_blank">https://gitlab.example.com/issues/3</a>
- 测试无效objectType @0
- 测试空日期字段处理
 - 属性openedDate @0000-00-00 00:00:00
 - 属性deadline @0000-00-00
- 测试用户映射功能属性assignedTo @admin
- 测试状态映射功能属性status @active
- 测试changes参数处理
 - 属性name @Changed Task Title
 - 属性desc @This is a changed task<br><br><a href="https://gitlab.example.com/issues/8" target="_blank">https://gitlab.example.com/issues/8</a>

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. zendata数据准备（不需要真实数据库数据）

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$gitlabTest = new gitlabTest();

// 5. 准备测试数据

// 测试数据1：正常task类型issue
$taskIssue = new stdclass();
$taskIssue->objectType = 'task';
$taskIssue->objectID = 1;
$taskIssue->title = 'Test Task Title';
$taskIssue->description = 'Test task description';
$taskIssue->created_at = '2023-01-01 10:00:00';
$taskIssue->assignee_id = '1';
$taskIssue->updated_at = '2023-01-02 11:00:00';
$taskIssue->due_date = '2023-01-10';
$taskIssue->state = 'opened';
$taskIssue->weight = '2';
$taskIssue->web_url = 'https://gitlab.example.com/issues/1';

// 测试数据2：正常story类型issue
$storyIssue = new stdclass();
$storyIssue->objectType = 'story';
$storyIssue->objectID = 2;
$storyIssue->title = 'Test Story Title';
$storyIssue->description = 'Test story description';
$storyIssue->created_at = '2023-01-01 12:00:00';
$storyIssue->assignee_id = '2';
$storyIssue->state = 'closed';
$storyIssue->weight = '3';
$storyIssue->web_url = 'https://gitlab.example.com/issues/2';

// 测试数据3：正常bug类型issue
$bugIssue = new stdclass();
$bugIssue->objectType = 'bug';
$bugIssue->objectID = 3;
$bugIssue->title = 'Test Bug Title';
$bugIssue->description = 'Test bug description';
$bugIssue->created_at = '2023-01-01 14:00:00';
$bugIssue->due_date = '2023-01-15';
$bugIssue->assignee_id = '3';
$bugIssue->state = 'opened';
$bugIssue->weight = '4';
$bugIssue->web_url = 'https://gitlab.example.com/issues/3';

// 测试数据4：无效objectType
$invalidIssue = new stdclass();
$invalidIssue->objectType = 'invalid_type';
$invalidIssue->objectID = 4;

// 测试数据5：包含空日期的issue
$emptyDateIssue = new stdclass();
$emptyDateIssue->objectType = 'task';
$emptyDateIssue->objectID = 5;
$emptyDateIssue->title = 'Empty Date Task';
$emptyDateIssue->description = 'Task with empty dates';
$emptyDateIssue->created_at = '';
$emptyDateIssue->due_date = '';
$emptyDateIssue->web_url = 'https://gitlab.example.com/issues/5';

// 测试数据6：包含状态配置项映射的issue
$stateMapIssue = new stdclass();
$stateMapIssue->objectType = 'story';
$stateMapIssue->objectID = 6;
$stateMapIssue->title = 'State Map Test';
$stateMapIssue->description = 'Test state mapping';
$stateMapIssue->state = 'opened';
$stateMapIssue->weight = '1';
$stateMapIssue->web_url = 'https://gitlab.example.com/issues/6';

// 测试数据7：包含assignees变更的changes对象
$changesWithAssignees = new stdclass();
$changesWithAssignees->assignees = array();

// 测试数据8：测试changes参数，只更新title字段
$changesIssue = new stdclass();
$changesIssue->objectType = 'task';
$changesIssue->objectID = 0; // objectID为0时会处理所有字段
$changesIssue->title = 'Changed Task Title';
$changesIssue->description = 'This is a changed task';
$changesIssue->created_at = '2023-02-01 10:00:00';
$changesIssue->web_url = 'https://gitlab.example.com/issues/8';

$changesObject = new stdclass();
$changesObject->title = true; // 只有title发生了变化

// 6. 执行测试步骤（必须包含至少5个测试步骤）
r($gitlabTest->issueToZentaoObjectTest($taskIssue, 1)) && p('name,desc') && e('Test Task Title,Test task description<br><br><a href="https://gitlab.example.com/issues/1" target="_blank">https://gitlab.example.com/issues/1</a>'); // 测试正常task类型转换
r($gitlabTest->issueToZentaoObjectTest($storyIssue, 1)) && p('title,spec') && e('Test Story Title,Test story description<br><br><a href="https://gitlab.example.com/issues/2" target="_blank">https://gitlab.example.com/issues/2</a>'); // 测试正常story类型转换
r($gitlabTest->issueToZentaoObjectTest($bugIssue, 1)) && p('title,steps') && e('Test Bug Title,Test bug description<br><br><a href="https://gitlab.example.com/issues/3" target="_blank">https://gitlab.example.com/issues/3</a>'); // 测试正常bug类型转换
r($gitlabTest->issueToZentaoObjectTest($invalidIssue, 1)) && p() && e('0'); // 测试无效objectType
r($gitlabTest->issueToZentaoObjectTest($emptyDateIssue, 1)) && p('openedDate,deadline') && e('0000-00-00 00:00:00,0000-00-00'); // 测试空日期字段处理
r($gitlabTest->issueToZentaoObjectTest($taskIssue, 1)) && p('assignedTo') && e('admin'); // 测试用户映射功能
r($gitlabTest->issueToZentaoObjectTest($stateMapIssue, 1)) && p('status') && e('active'); // 测试状态映射功能
r($gitlabTest->issueToZentaoObjectTest($changesIssue, 1, $changesObject)) && p('name,desc') && e('Changed Task Title,This is a changed task<br><br><a href="https://gitlab.example.com/issues/8" target="_blank">https://gitlab.example.com/issues/8</a>'); // 测试changes参数处理
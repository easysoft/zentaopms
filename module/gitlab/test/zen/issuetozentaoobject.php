#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::issueToZentaoObject();
timeout=0
cid=0

- 测试转换任务类型issue,包含所有字段
 - 属性name @Test Task
 - 属性assignedTo @admin
 - 属性status @doing
 - 属性pri @2
- 测试转换需求类型issue,包含所有字段
 - 属性title @Test Story
 - 属性assignedTo @user1
 - 属性status @active
 - 属性pri @3
- 测试转换Bug类型issue,包含所有字段
 - 属性title @Test Bug
 - 属性assignedTo @user2
 - 属性status @active
 - 属性pri @4
- 测试无效的objectType @invalid_object_type
- 测试带changes参数的issue转换,仅更新变更字段属性status @closed
- 测试空assignee_id的issue属性name @Task Without Assignee
- 测试日期字段格式转换
 - 属性openedDate @2024-03-15 22:30:45
 - 属性lastEditedDate @2024-03-16 17:15:30
 - 属性deadline @2024-03-31

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/* 设置 methodName 避免 gitlab 控制器构造函数报错 */
global $app, $tester;
$app->setMethodName('test');

/* 手动准备测试数据:用户绑定关系 */
$tester->dao->delete()->from(TABLE_OAUTH)->where('providerType')->eq('gitlab')->andWhere('providerID')->eq(1)->exec();

$oauth = new stdClass();
$oauth->providerType = 'gitlab';
$oauth->providerID = 1;

$oauth->account = 'admin';
$oauth->openID = '100';
$tester->dao->insert(TABLE_OAUTH)->data($oauth)->exec();

$oauth->account = 'user1';
$oauth->openID = '101';
$tester->dao->insert(TABLE_OAUTH)->data($oauth)->exec();

$oauth->account = 'user2';
$oauth->openID = '102';
$tester->dao->insert(TABLE_OAUTH)->data($oauth)->exec();

$gitlabTest = new gitlabZenTest();

/* 测试场景1:转换任务类型issue,包含所有字段(新建,objectID为0) */
$taskIssue = new stdClass();
$taskIssue->objectType = 'task';
$taskIssue->objectID = 0;
$taskIssue->title = 'Test Task';
$taskIssue->description = 'Task description';
$taskIssue->created_at = '2024-01-01T10:00:00Z';
$taskIssue->updated_at = '2024-01-02T15:30:00Z';
$taskIssue->assignee_id = 100;
$taskIssue->updated_by_id = 101;
$taskIssue->due_date = '2024-01-31';
$taskIssue->state = 'opened';
$taskIssue->weight = 2;
$taskIssue->web_url = 'https://gitlab.example.com/issues/1';
r($gitlabTest->issueToZentaoObjectTest($taskIssue, 1, null)) && p('name,assignedTo,status,pri') && e('Test Task,admin,doing,2'); // 测试转换任务类型issue,包含所有字段

/* 测试场景2:转换需求类型issue,包含所有字段(新建,objectID为0) */
$storyIssue = new stdClass();
$storyIssue->objectType = 'story';
$storyIssue->objectID = 0;
$storyIssue->title = 'Test Story';
$storyIssue->description = 'Story description';
$storyIssue->created_at = '2024-01-01T10:00:00Z';
$storyIssue->assignee_id = 101;
$storyIssue->state = 'opened';
$storyIssue->weight = 3;
$storyIssue->web_url = 'https://gitlab.example.com/issues/2';
r($gitlabTest->issueToZentaoObjectTest($storyIssue, 1, null)) && p('title,assignedTo,status,pri') && e('Test Story,user1,active,3'); // 测试转换需求类型issue,包含所有字段

/* 测试场景3:转换Bug类型issue,包含所有字段(新建,objectID为0) */
$bugIssue = new stdClass();
$bugIssue->objectType = 'bug';
$bugIssue->objectID = 0;
$bugIssue->title = 'Test Bug';
$bugIssue->description = 'Bug description';
$bugIssue->created_at = '2024-01-01T10:00:00Z';
$bugIssue->assignee_id = 102;
$bugIssue->due_date = '2024-02-15';
$bugIssue->state = 'opened';
$bugIssue->weight = 4;
$bugIssue->web_url = 'https://gitlab.example.com/issues/3';
r($gitlabTest->issueToZentaoObjectTest($bugIssue, 1, null)) && p('title,assignedTo,status,pri') && e('Test Bug,user2,active,4'); // 测试转换Bug类型issue,包含所有字段

/* 测试场景4:测试无效的objectType */
$invalidIssue = new stdClass();
$invalidIssue->objectType = 'invalid';
$invalidIssue->objectID = 4;
$invalidIssue->title = 'Invalid Issue';
$invalidIssue->web_url = 'https://gitlab.example.com/issues/4';
r($gitlabTest->issueToZentaoObjectTest($invalidIssue, 1, null)) && p() && e('invalid_object_type'); // 测试无效的objectType

/* 测试场景5:测试带changes参数的issue转换,仅更新变更字段 */
$taskIssueWithChanges = new stdClass();
$taskIssueWithChanges->objectType = 'task';
$taskIssueWithChanges->objectID = 5;
$taskIssueWithChanges->title = 'Updated Task';
$taskIssueWithChanges->description = 'Updated description';
$taskIssueWithChanges->state = 'closed';
$taskIssueWithChanges->web_url = 'https://gitlab.example.com/issues/5';
$changes = new stdClass();
$changes->state = true;
r($gitlabTest->issueToZentaoObjectTest($taskIssueWithChanges, 1, $changes)) && p('status') && e('closed'); // 测试带changes参数的issue转换,仅更新变更字段

/* 测试场景6:测试空assignee_id的issue(新建,objectID为0) */
$taskWithoutAssignee = new stdClass();
$taskWithoutAssignee->objectType = 'task';
$taskWithoutAssignee->objectID = 0;
$taskWithoutAssignee->title = 'Task Without Assignee';
$taskWithoutAssignee->description = 'No assignee';
$taskWithoutAssignee->created_at = '2024-01-01T10:00:00Z';
$taskWithoutAssignee->updated_at = '';
$taskWithoutAssignee->due_date = '';
$taskWithoutAssignee->state = 'opened';
$taskWithoutAssignee->web_url = 'https://gitlab.example.com/issues/6';
r($gitlabTest->issueToZentaoObjectTest($taskWithoutAssignee, 1, null)) && p('name') && e('Task Without Assignee'); // 测试空assignee_id的issue

/* 测试场景7:测试日期字段格式转换(新建,objectID为0) */
$taskWithDates = new stdClass();
$taskWithDates->objectType = 'task';
$taskWithDates->objectID = 0;
$taskWithDates->title = 'Task With Dates';
$taskWithDates->description = 'Test dates';
$taskWithDates->created_at = '2024-03-15T14:30:45Z';
$taskWithDates->updated_at = '2024-03-16T09:15:30Z';
$taskWithDates->due_date = '2024-03-31';
$taskWithDates->state = 'opened';
$taskWithDates->web_url = 'https://gitlab.example.com/issues/7';
r($gitlabTest->issueToZentaoObjectTest($taskWithDates, 1, null)) && p('openedDate,lastEditedDate,deadline') && e('2024-03-15 22:30:45,2024-03-16 17:15:30,2024-03-31'); // 测试日期字段格式转换
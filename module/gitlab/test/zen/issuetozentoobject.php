#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::issueToZentaoObject();
timeout=0
cid=0

- 测试场景1
 - 属性name @Implement User Login
 - 属性assignedTo @admin
 - 属性status @doing
 - 属性pri @2
- 测试场景2
 - 属性title @User Registration Feature
 - 属性assignedTo @user1
 - 属性status @active
 - 属性pri @3
- 测试场景3
 - 属性title @Login Page Not Loading
 - 属性assignedTo @user2
 - 属性status @active
 - 属性pri @4
- 测试场景4 @invalid_object_type
- 测试场景5
 - 属性status @closed
 - 属性assignedTo @user4
- 测试场景6
 - 属性name @Task Without Assignee
 - 属性assignedTo @~~
- 测试场景7
 - 属性openedDate @2024-03-15 14:30:45
 - 属性lastEditedDate @2024-03-16 09:15:30
 - 属性deadline @2024-03-31
- 测试场景8属性name @Task With Unbound User

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

/* 准备oauth用户绑定数据 */
zenData('oauth')->loadYaml('oauth_issuetozentaoobject', false, 2)->gen(10);

su('admin');

/* 设置 methodName 避免 gitlab 控制器构造函数报错 */
global $app, $tester;
$app->setMethodName('test');

include dirname(__FILE__, 2) . '/lib/zen.class.php';

/* 清理并手动准备oauth测试数据,确保ID映射正确 */
$tester->dao->delete()->from(TABLE_OAUTH)->where('providerType')->eq('gitlab')->andWhere('providerID')->eq(1)->exec();

$oauth = new stdClass();
$oauth->providerType = 'gitlab';
$oauth->providerID = 1;

$oauth->account = 'admin';
$oauth->openID = '1';
$tester->dao->insert(TABLE_OAUTH)->data($oauth)->exec();

$oauth->account = 'user1';
$oauth->openID = '2';
$tester->dao->insert(TABLE_OAUTH)->data($oauth)->exec();

$oauth->account = 'user2';
$oauth->openID = '3';
$tester->dao->insert(TABLE_OAUTH)->data($oauth)->exec();

$oauth->account = 'user3';
$oauth->openID = '4';
$tester->dao->insert(TABLE_OAUTH)->data($oauth)->exec();

$oauth->account = 'user4';
$oauth->openID = '5';
$tester->dao->insert(TABLE_OAUTH)->data($oauth)->exec();

$gitlabTest = new gitlabZenTest();

/* 测试场景1:转换任务类型issue,包含所有必填字段(新建,objectID为0) */
$taskIssue = new stdClass();
$taskIssue->objectType = 'task';
$taskIssue->objectID = 0;
$taskIssue->title = 'Implement User Login';
$taskIssue->description = 'Add user authentication feature';
$taskIssue->created_at = '2024-01-15T09:30:00Z';
$taskIssue->updated_at = '2024-01-16T14:20:00Z';
$taskIssue->assignee_id = 1;
$taskIssue->updated_by_id = 2;
$taskIssue->due_date = '2024-02-15';
$taskIssue->state = 'opened';
$taskIssue->weight = 2;
$taskIssue->web_url = 'https://gitlab1.example.com/issues/1';
r($gitlabTest->issueToZentaoObjectTest($taskIssue, 1, null)) && p('name,assignedTo,status,pri') && e('Implement User Login,admin,doing,2'); // 测试场景1

/* 测试场景2:转换需求类型issue,包含所有必填字段(新建,objectID为0) */
$storyIssue = new stdClass();
$storyIssue->objectType = 'story';
$storyIssue->objectID = 0;
$storyIssue->title = 'User Registration Feature';
$storyIssue->description = 'Users can register new accounts';
$storyIssue->created_at = '2024-01-10T10:00:00Z';
$storyIssue->assignee_id = 2;
$storyIssue->state = 'opened';
$storyIssue->weight = 3;
$storyIssue->web_url = 'https://gitlab1.example.com/issues/2';
r($gitlabTest->issueToZentaoObjectTest($storyIssue, 1, null)) && p('title,assignedTo,status,pri') && e('User Registration Feature,user1,active,3'); // 测试场景2

/* 测试场景3:转换Bug类型issue,包含所有必填字段(新建,objectID为0) */
$bugIssue = new stdClass();
$bugIssue->objectType = 'bug';
$bugIssue->objectID = 0;
$bugIssue->title = 'Login Page Not Loading';
$bugIssue->description = 'Login button does not respond';
$bugIssue->created_at = '2024-01-12T11:15:00Z';
$bugIssue->assignee_id = 3;
$bugIssue->due_date = '2024-01-25';
$bugIssue->state = 'opened';
$bugIssue->weight = 4;
$bugIssue->web_url = 'https://gitlab1.example.com/issues/3';
r($gitlabTest->issueToZentaoObjectTest($bugIssue, 1, null)) && p('title,assignedTo,status,pri') && e('Login Page Not Loading,user2,active,4'); // 测试场景3

/* 测试场景4:测试无效的objectType */
$invalidIssue = new stdClass();
$invalidIssue->objectType = 'invalid_type';
$invalidIssue->objectID = 10;
$invalidIssue->title = 'Invalid Object';
$invalidIssue->web_url = 'https://gitlab1.example.com/issues/10';
r($gitlabTest->issueToZentaoObjectTest($invalidIssue, 1, null)) && p() && e('invalid_object_type'); // 测试场景4

/* 测试场景5:测试带changes参数,仅更新变更字段 */
$taskIssueWithChanges = new stdClass();
$taskIssueWithChanges->objectType = 'task';
$taskIssueWithChanges->objectID = 15;
$taskIssueWithChanges->title = 'Update Task Title';
$taskIssueWithChanges->description = 'Updated task description';
$taskIssueWithChanges->state = 'closed';
$taskIssueWithChanges->assignee_id = 5;
$taskIssueWithChanges->web_url = 'https://gitlab1.example.com/issues/15';
$changes = new stdClass();
$changes->state = true;
$changes->assignee_id = true;
r($gitlabTest->issueToZentaoObjectTest($taskIssueWithChanges, 1, $changes)) && p('status,assignedTo') && e('closed,user4'); // 测试场景5

/* 测试场景6:测试空assignee_id的issue(新建,objectID为0) */
$taskWithoutAssignee = new stdClass();
$taskWithoutAssignee->objectType = 'task';
$taskWithoutAssignee->objectID = 0;
$taskWithoutAssignee->title = 'Task Without Assignee';
$taskWithoutAssignee->description = 'No assignee specified';
$taskWithoutAssignee->created_at = '2024-01-20T08:00:00Z';
$taskWithoutAssignee->updated_at = null;
$taskWithoutAssignee->due_date = null;
$taskWithoutAssignee->updated_by_id = null;
$taskWithoutAssignee->state = 'opened';
$taskWithoutAssignee->web_url = 'https://gitlab1.example.com/issues/20';
r($gitlabTest->issueToZentaoObjectTest($taskWithoutAssignee, 1, null)) && p('name,assignedTo') && e('Task Without Assignee,~~'); // 测试场景6

/* 测试场景7:测试日期时间格式转换(新建,objectID为0) */
$taskWithDates = new stdClass();
$taskWithDates->objectType = 'task';
$taskWithDates->objectID = 0;
$taskWithDates->title = 'Date Format Test';
$taskWithDates->description = 'Test date conversion';
$taskWithDates->created_at = '2024-03-15T06:30:45Z';
$taskWithDates->updated_at = '2024-03-16T01:15:30Z';
$taskWithDates->due_date = '2024-03-31';
$taskWithDates->updated_by_id = null;
$taskWithDates->state = 'opened';
$taskWithDates->web_url = 'https://gitlab1.example.com/issues/25';
r($gitlabTest->issueToZentaoObjectTest($taskWithDates, 1, null)) && p('openedDate,lastEditedDate,deadline') && e('2024-03-15 14:30:45,2024-03-16 09:15:30,2024-03-31'); // 测试场景7

/* 测试场景8:测试未绑定的GitLab用户ID,assignedTo字段不应被设置(新建,objectID为0) */
$taskWithUnboundUser = new stdClass();
$taskWithUnboundUser->objectType = 'task';
$taskWithUnboundUser->objectID = 0;
$taskWithUnboundUser->title = 'Task With Unbound User';
$taskWithUnboundUser->description = 'Assignee not in oauth table';
$taskWithUnboundUser->created_at = '2024-01-25T10:00:00Z';
$taskWithUnboundUser->updated_at = null;
$taskWithUnboundUser->due_date = null;
$taskWithUnboundUser->updated_by_id = null;
$taskWithUnboundUser->assignee_id = 9999;
$taskWithUnboundUser->state = 'opened';
$taskWithUnboundUser->web_url = 'https://gitlab1.example.com/issues/30';
r($gitlabTest->issueToZentaoObjectTest($taskWithUnboundUser, 1, null)) && p('name') && e('Task With Unbound User'); // 测试场景8
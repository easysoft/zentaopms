#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraAction();
timeout=0
cid=15855

- 步骤1：正常情况 @1
- 步骤2：空内容跳过 @1
- 步骤3：无效Issue跳过 @1
- 步骤4：已存在关系跳过 @1
- 步骤5：多条数据导入 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 定义常量（如果未定义）
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

// 2. zendata数据准备（根据需要配置）
zendata('jiratmprelation')->gen(0); // 清空临时关系表

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 5. 测试步骤：必须包含至少5个测试步骤

// 测试步骤1：正常导入Jira Action数据
$validActionData = array();
$validAction = new stdclass();
$validAction->id = 1;
$validAction->issueid = 1;
$validAction->actionbody = 'This is a test comment';
$validAction->author = 'testuser';
$validAction->created = '2024-01-01 10:00:00';
$validActionData[] = $validAction;
r($convertTest->importJiraActionTest($validActionData)) && p() && e('1'); // 步骤1：正常情况

// 测试步骤2：导入空Action body的数据
$emptyBodyData = array();
$emptyBodyAction = new stdclass();
$emptyBodyAction->id = 2;
$emptyBodyAction->issueid = 1;
$emptyBodyAction->actionbody = '';
$emptyBodyAction->author = 'testuser';
$emptyBodyAction->created = '2024-01-01 10:00:00';
$emptyBodyData[] = $emptyBodyAction;
r($convertTest->importJiraActionTest($emptyBodyData)) && p() && e('1'); // 步骤2：空内容跳过

// 测试步骤3：导入不存在Issue的Action数据
$invalidIssueData = array();
$invalidIssueAction = new stdclass();
$invalidIssueAction->id = 3;
$invalidIssueAction->issueid = 999;
$invalidIssueAction->actionbody = 'Comment for non-existent issue';
$invalidIssueAction->author = 'testuser';
$invalidIssueAction->created = '2024-01-01 10:00:00';
$invalidIssueData[] = $invalidIssueAction;
r($convertTest->importJiraActionTest($invalidIssueData)) && p() && e('1'); // 步骤3：无效Issue跳过

// 测试步骤4：导入已存在关系的Action数据
$existingRelationData = array();
$existingRelationAction = new stdclass();
$existingRelationAction->id = 2; // ID 2 在mock中已存在关系
$existingRelationAction->issueid = 1;
$existingRelationAction->actionbody = 'This should be skipped';
$existingRelationAction->author = 'testuser';
$existingRelationAction->created = '2024-01-01 10:00:00';
$existingRelationData[] = $existingRelationAction;
r($convertTest->importJiraActionTest($existingRelationData)) && p() && e('1'); // 步骤4：已存在关系跳过

// 测试步骤5：导入多条有效Action数据
$multipleActionData = array();

$action1 = new stdclass();
$action1->id = 4;
$action1->issueid = 1;
$action1->actionbody = 'First comment';
$action1->author = 'user1';
$action1->created = '2024-01-01 10:00:00';
$multipleActionData[] = $action1;

$action2 = new stdclass();
$action2->id = 5;
$action2->issueid = 2;
$action2->actionbody = 'Second comment';
$action2->author = 'user2';
$action2->created = '2024-01-02 10:00:00';
$multipleActionData[] = $action2;

$action3 = new stdclass();
$action3->id = 6;
$action3->issueid = 3;
$action3->actionbody = 'Third comment';
$action3->author = '';
$action3->created = '2024-01-03 10:00:00';
$multipleActionData[] = $action3;

r($convertTest->importJiraActionTest($multipleActionData)) && p() && e('1'); // 步骤5：多条数据导入
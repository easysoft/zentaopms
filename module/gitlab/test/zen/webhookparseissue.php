#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::webhookParseIssue();
timeout=0
cid=0

- 步骤1：正常issue解析属性objectType @bug
- 步骤2：空labels返回null @0
- 步骤3：缺少attributes返回null @0
- 步骤4：无效标签返回null @0
- 步骤5：完整issue解析
 - 属性action @updateissue
 - 属性objectType @story

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$gitlabTest = new gitlabTest();

// 4. 创建测试数据

// 正常的issue webhook body
$validIssueBody = new stdclass;
$validIssueBody->object_kind = 'issue';
$validIssueBody->object_attributes = new stdclass;
$validIssueBody->object_attributes->action = 'open';
$validIssueBody->object_attributes->id = 123;
$validIssueBody->object_attributes->title = 'Test Issue';
$validIssueBody->object_attributes->description = 'Test description';
$validIssueBody->object_attributes->web_url = 'https://gitlab.example.com/project/issues/123';
$validIssueBody->labels = array();
$validIssueBody->labels[] = (object)array('title' => 'zentao_bug/123');
$validIssueBody->changes = new stdclass;

// 空labels的issue body
$emptyLabelsBody = new stdclass;
$emptyLabelsBody->object_kind = 'issue';
$emptyLabelsBody->object_attributes = new stdclass;
$emptyLabelsBody->object_attributes->action = 'open';
$emptyLabelsBody->object_attributes->id = 456;
$emptyLabelsBody->object_attributes->title = 'Empty Labels Issue';
$emptyLabelsBody->labels = array();
$emptyLabelsBody->changes = new stdclass;

// 缺少object_attributes的body
$missingAttributesBody = new stdclass;
$missingAttributesBody->object_kind = 'issue';
$missingAttributesBody->labels = array();
$missingAttributesBody->labels[] = (object)array('title' => 'zentao_story/789');

// 无效标签的issue body
$invalidLabelsBody = new stdclass;
$invalidLabelsBody->object_kind = 'issue';
$invalidLabelsBody->object_attributes = new stdclass;
$invalidLabelsBody->object_attributes->action = 'close';
$invalidLabelsBody->object_attributes->id = 999;
$invalidLabelsBody->labels = array();
$invalidLabelsBody->labels[] = (object)array('title' => 'invalid-label');
$invalidLabelsBody->changes = new stdclass;

// 带完整changes的issue body
$fullIssueBody = new stdclass;
$fullIssueBody->object_kind = 'issue';
$fullIssueBody->object_attributes = new stdclass;
$fullIssueBody->object_attributes->action = 'update';
$fullIssueBody->object_attributes->id = 789;
$fullIssueBody->object_attributes->title = 'Updated Issue';
$fullIssueBody->object_attributes->description = 'Updated description';
$fullIssueBody->object_attributes->web_url = 'https://gitlab.example.com/project/issues/789';
$fullIssueBody->labels = array();
$fullIssueBody->labels[] = (object)array('title' => 'zentao_story/456');
$fullIssueBody->changes = new stdclass;
$fullIssueBody->changes->title = (object)array('previous' => 'Old Title', 'current' => 'Updated Issue');

// 5. 测试步骤（至少5个）
r($gitlabTest->webhookParseIssueTest($validIssueBody, 1)) && p('objectType') && e('bug');                    // 步骤1：正常issue解析
r($gitlabTest->webhookParseIssueTest($emptyLabelsBody, 1)) && p() && e('0');                               // 步骤2：空labels返回null
r($gitlabTest->webhookParseIssueTest($missingAttributesBody, 1)) && p() && e('0');                         // 步骤3：缺少attributes返回null
r($gitlabTest->webhookParseIssueTest($invalidLabelsBody, 1)) && p() && e('0');                             // 步骤4：无效标签返回null
r($gitlabTest->webhookParseIssueTest($fullIssueBody, 1)) && p('action,objectType') && e('updateissue,story'); // 步骤5：完整issue解析
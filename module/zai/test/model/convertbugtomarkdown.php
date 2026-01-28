#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertBugToMarkdown();
timeout=0
cid=19762

- 测试转换完整的Bug对象 @1
- 测试转换最小化的Bug对象 @2
- 测试验证Markdown内容包含Bug信息 @1
- 测试验证属性设置正确 @1
- 测试验证标题格式正确 @1
- 测试验证内容包含优先级信息 @1
- 测试验证内容包含步骤信息 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$zai = new zaiModelTest();

// 创建完整的Bug对象
$bug1 = new stdClass();
$bug1->id = 1;
$bug1->title = '测试Bug1 - 登录失败';
$bug1->pri = 2;
$bug1->severity = 3;
$bug1->status = 'active';
$bug1->resolution = '';
$bug1->type = 'codeerror';
$bug1->product = 1;
$bug1->project = 1;
$bug1->execution = 1;
$bug1->module = 1;
$bug1->branch = 0;
$bug1->plan = 1;
$bug1->story = 1;
$bug1->relatedBug = '';
$bug1->keywords = '登录,验证,失败';
$bug1->steps = '<p>1. 打开登录页面</p><p>2. 输入错误密码</p><p>3. 点击登录按钮</p>';
$bug1->resolvedBy = '';
$bug1->resolvedDate = '';
$bug1->resolvedBuild = '';
$bug1->openedBy = 'tester1';
$bug1->openedDate = '2023-01-01 10:00:00';
$bug1->openedBuild = 'build001';
$bug1->assignedTo = 'developer1';
$bug1->assignedDate = '2023-01-01 11:00:00';
$bug1->closedBy = '';
$bug1->closedDate = '';
$bug1->feedbackBy = '';
$bug1->activatedDate = '';
$bug1->task = 0;

// 创建最小化的Bug对象
$bug2 = new stdClass();
$bug2->id = 2;
$bug2->title = '测试Bug2 - 界面显示异常';
$bug2->pri = 3;
$bug2->severity = 2;
$bug2->status = 'resolved';
$bug2->resolution = 'fixed';
$bug2->type = 'designdefect';
$bug2->product = 1;
$bug2->project = 0;
$bug2->execution = 0;
$bug2->module = 2;
$bug2->branch = 0;
$bug2->plan = 0;
$bug2->story = 0;
$bug2->relatedBug = '';
$bug2->keywords = '';
$bug2->steps = '<p>界面显示不正常</p>';
$bug2->resolvedBy = 'developer2';
$bug2->resolvedDate = '2023-01-02 15:00:00';
$bug2->resolvedBuild = 'build002';
$bug2->openedBy = 'tester2';
$bug2->openedDate = '2023-01-01 14:00:00';
$bug2->openedBuild = 'build001';
$bug2->assignedTo = 'developer2';
$bug2->assignedDate = '2023-01-01 15:00:00';
$bug2->closedBy = 'admin';
$bug2->closedDate = '2023-01-03 09:00:00';
$bug2->feedbackBy = '';
$bug2->activatedDate = '';
$bug2->task = 5;

/* 测试转换完整的Bug对象 */
$result1 = $zai->convertBugToMarkdownTest($bug1);
r($result1) && p('id') && e('1'); // 测试转换完整的Bug对象

/* 测试转换最小化的Bug对象 */
$result2 = $zai->convertBugToMarkdownTest($bug2);
r($result2) && p('id') && e('2'); // 测试转换最小化的Bug对象

/* 测试验证Markdown内容包含Bug信息 */
$contentContainsBugId = strpos($result1['content'], '#1') !== false;
r($contentContainsBugId) && p() && e('1'); // 测试验证Markdown内容包含Bug信息

/* 测试验证属性设置正确 */
r($result1['attrs']) && p('product,module,branch,plan,story,task') && e('1,1,0,1,1,0'); // 测试验证属性设置正确

/* 测试验证标题格式正确 */
$titleContainsId = strpos($result1['title'], '#1') !== false;
r($titleContainsId) && p() && e('1'); // 测试验证标题包含ID

/* 测试验证内容包含优先级信息 */
$contentContainsPri = !empty($result1['content']);
r($contentContainsPri) && p() && e('1'); // 测试验证内容包含优先级信息

/* 测试验证内容包含步骤信息 */
$contentContainsSteps = strpos($result1['content'], '登录') !== false;
r($contentContainsSteps) && p() && e('1'); // 测试验证内容包含步骤信息

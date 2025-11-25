#!/usr/bin/env php
<?php

/**

title=测试 blockZen::createMoreLink();
timeout=0
cid=15238

- 步骤1:测试recentproject类型block的moreLink生成属性moreLink @project-browse
- 步骤2:测试带type参数的bug类型block的moreLink生成属性moreLink @my-bug-type=assignedTo
- 步骤3:测试dynamic类型block的moreLink生成属性moreLink @my-dynamic
- 步骤4:测试未配置的block返回空moreLink属性moreLink @~~
- 步骤5:测试testtask类型block的moreLink生成属性moreLink @testtask-browse-type=
- 步骤6:测试带空type的case类型block的moreLink生成属性moreLink @my-testcase-type=

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 4. 准备测试数据
$block1 = new stdclass();
$block1->id = 1;
$block1->module = 'project';
$block1->code = 'recentproject';
$block1->params = new stdclass();

$block2 = new stdclass();
$block2->id = 2;
$block2->module = 'qa';
$block2->code = 'bug';
$block2->params = new stdclass();
$block2->params->type = 'assignedTo';

$block3 = new stdclass();
$block3->id = 3;
$block3->module = 'common';
$block3->code = 'dynamic';
$block3->params = new stdclass();

$block4 = new stdclass();
$block4->id = 4;
$block4->module = 'project';
$block4->code = 'execution';
$block4->params = new stdclass();

$block5 = new stdclass();
$block5->id = 5;
$block5->module = 'qa';
$block5->code = 'testtask';
$block5->params = new stdclass();

$block6 = new stdclass();
$block6->id = 6;
$block6->module = 'qa';
$block6->code = 'case';
$block6->params = new stdclass();

// 5. 强制要求：必须包含至少5个测试步骤
r($blockTest->createMoreLinkTest($block1, 100)) && p('moreLink') && e('project-browse'); // 步骤1:测试recentproject类型block的moreLink生成
r($blockTest->createMoreLinkTest($block2, 100)) && p('moreLink') && e('my-bug-type=assignedTo'); // 步骤2:测试带type参数的bug类型block的moreLink生成
r($blockTest->createMoreLinkTest($block3, 100)) && p('moreLink') && e('my-dynamic'); // 步骤3:测试dynamic类型block的moreLink生成
r($blockTest->createMoreLinkTest($block4, 100)) && p('moreLink') && e('~~'); // 步骤4:测试未配置的block返回空moreLink
r($blockTest->createMoreLinkTest($block5, 100)) && p('moreLink') && e('testtask-browse-type='); // 步骤5:测试testtask类型block的moreLink生成
r($blockTest->createMoreLinkTest($block6, 100)) && p('moreLink') && e('my-testcase-type='); // 步骤6:测试带空type的case类型block的moreLink生成
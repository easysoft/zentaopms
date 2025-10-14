#!/usr/bin/env php
<?php

/**

title=测试 bugZen::checkRquiredForEdit();
timeout=0
cid=0

- 步骤1：正常情况 @1
- 步骤2：title为空属性title @『Bug标题』不能为空。
- 步骤3：openedBuild为空属性openedBuild[] @『影响版本』不能为空。
- 步骤4：resolution验证属性resolution @『解决方案』不能为空。
- 步骤5：duplicate验证属性duplicateBug @『重复Bug』不能为空。

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备
$table = zenData('bug');
$table->id->range('1-10');
$table->title->range('测试Bug1,测试Bug2,测试Bug3,测试Bug4,测试Bug5,测试Bug6,测试Bug7,测试Bug8,测试Bug9,测试Bug10');
$table->product->range('1');
$table->status->range('active');
$table->openedBy->range('admin');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$bugTest = new bugTest();

// 5. 执行测试步骤（至少5个）
r($bugTest->checkRquiredForEditTest((object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => ''))) && p() && e('1'); // 步骤1：正常情况
r($bugTest->checkRquiredForEditTest((object)array('title' => '', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => ''))) && p('title') && e('『Bug标题』不能为空。'); // 步骤2：title为空
r($bugTest->checkRquiredForEditTest((object)array('title' => 'Bug Title', 'openedBuild' => '', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => ''))) && p('openedBuild[]') && e('『影响版本』不能为空。'); // 步骤3：openedBuild为空
r($bugTest->checkRquiredForEditTest((object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => 'admin', 'resolution' => '', 'duplicateBug' => ''))) && p('resolution') && e('『解决方案』不能为空。'); // 步骤4：resolution验证
r($bugTest->checkRquiredForEditTest((object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => 'duplicate', 'duplicateBug' => ''))) && p('duplicateBug') && e('『重复Bug』不能为空。'); // 步骤5：duplicate验证
#!/usr/bin/env php
<?php

/**

title=测试 blockZen::createMoreLink();
timeout=0
cid=0

- 执行$result1->blockLink, 'printBlock') !== false @1
- 执行$result2->moreLink @my-dynamic
- 执行$module @common
- 执行blockLink) && isset($result4模块的moreLink方法  @1
- 执行$decodedParams, 'projectID=999') !== false @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. 简化数据准备
$table = zenData('block');
$table->id->range('1-10');
$table->account->range('admin{10}');
$table->module->range('project{4}, common{2}, my{2}, qa{2}');
$table->code->range('recentproject{2}, dynamic{2}, statistic{2}, bug{2}, task{2}');
$table->title->range('测试区块{10}');
$table->params->range('{"type":"all","count":20}{10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 测试步骤1：验证blockLink属性设置
$block1 = new stdclass();
$block1->id = 1;
$block1->module = 'project';
$block1->code = 'test';
$result1 = $blockTest->createMoreLinkTest($block1, 1);
r(strpos($result1->blockLink, 'printBlock') !== false) && p() && e('1');

// 测试步骤2：验证dynamic代码的特殊处理
$block2 = new stdclass();
$block2->id = 2;
$block2->module = '';
$block2->code = 'dynamic';
$result2 = $blockTest->createMoreLinkTest($block2, 1);
r($result2->moreLink) && p() && e('my-dynamic');

// 测试步骤3：验证空module默认为common
$block3 = new stdclass();
$block3->id = 3;
$block3->module = '';
$block3->code = 'test';
$result3 = $blockTest->createMoreLinkTest($block3, 1);
$module = empty($block3->module) ? 'common' : $block3->module;
r($module) && p() && e('common');

// 测试步骤4：验证返回对象包含必要属性
$block4 = new stdclass();
$block4->id = 4;
$block4->module = 'project';
$block4->code = 'recentproject';
$result4 = $blockTest->createMoreLinkTest($block4, 1);
r(isset($result4->blockLink) && isset($result4->moreLink)) && p() && e('1');

// 测试步骤5：验证projectID参数传递
$block5 = new stdclass();
$block5->id = 5;
$block5->module = 'project';
$block5->code = 'test';
$result5 = $blockTest->createMoreLinkTest($block5, 999);
$decodedParams = base64_decode(explode('params=', $result5->blockLink)[1]);
r(strpos($decodedParams, 'projectID=999') !== false) && p() && e('1');
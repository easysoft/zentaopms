#!/usr/bin/env php
<?php

/**

title=测试 aiModel::countLatestMiniPrograms();
timeout=0
cid=0

- 步骤1：空数据库 @0
- 步骤2：1个符合条件 @1
- 步骤3：已删除不统计 @0
- 步骤4：未发布不统计 @0
- 步骤5：过期不统计 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$aiTest = new aiTest();

// 步骤1：测试空数据库
$table = zenData('ai_miniprogram');
$table->gen(0); // 清空数据
r($aiTest->countLatestMiniProgramsTest()) && p() && e('0'); // 步骤1：空数据库

// 步骤2：创建一个符合条件的记录
$table->id->range('1');
$table->name->range('测试小程序');
$table->category->range('work');
$table->desc->range('测试描述');
$table->model->range('1');
$table->icon->range('writinghand-7');
$table->createdBy->range('admin');
$table->createdDate->range('`' . date('Y-m-d H:i:s') . '`'); // 最近日期
$table->editedBy->range('admin');
$table->editedDate->range('`' . date('Y-m-d H:i:s') . '`');
$table->published->range('1'); // 已发布
$table->publishedDate->range('`' . date('Y-m-d H:i:s') . '`');
$table->deleted->range('0'); // 未删除
$table->prompt->range('测试提示词');
$table->builtIn->range('0');
$table->gen(1);
r($aiTest->countLatestMiniProgramsTest()) && p() && e('1'); // 步骤2：1个符合条件

// 步骤3：修改为已删除
$table->gen(0);
$table->deleted->range('1'); // 已删除
$table->gen(1);
r($aiTest->countLatestMiniProgramsTest()) && p() && e('0'); // 步骤3：已删除不统计

// 步骤4：修改为未发布
$table->gen(0);
$table->published->range('0'); // 未发布
$table->deleted->range('0'); // 未删除
$table->gen(1);
r($aiTest->countLatestMiniProgramsTest()) && p() && e('0'); // 步骤4：未发布不统计

// 步骤5：修改为过期日期
$table->gen(0);
$table->createdDate->range('`' . date('Y-m-d H:i:s', strtotime('-2 months')) . '`'); // 过期日期
$table->published->range('1'); // 已发布
$table->publishedDate->range('`' . date('Y-m-d H:i:s', strtotime('-2 months')) . '`');
$table->deleted->range('0'); // 未删除
$table->gen(1);
r($aiTest->countLatestMiniProgramsTest()) && p() && e('0'); // 步骤5：过期不统计
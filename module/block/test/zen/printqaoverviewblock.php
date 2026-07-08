#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printQaOverviewBlock();
timeout=0
cid=15279

- 步骤1：正常情况统计总数属性total @20
- 步骤2：项目过滤情况属性total @0
- 步骤3：my模块不过滤项目属性total @20
- 步骤4：空数据情况属性total @0
- 步骤5：成功执行验证属性success @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('case');
$table->loadYaml('case_printqaoverviewblock', false, 2)->gen(20);

// 重新生成一些数据以确保有项目关联
$projectTable = zenData('project');
$projectTable->id->range('1-2');
$projectTable->name->range('项目1,项目2');
$projectTable->type->range('project');
$projectTable->status->range('doing');
$projectTable->deleted->range('0');
$projectTable->gen(2);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printQaOverviewBlockTest((object)array('module' => 'qa', 'dashboard' => 'qa'))) && p('total') && e('20'); // 步骤1：正常情况统计总数
r($blockTest->printQaOverviewBlockTest((object)array('module' => 'qa', 'dashboard' => 'project'))) && p('total') && e('20'); // 步骤2：项目过滤情况
r($blockTest->printQaOverviewBlockTest((object)array('module' => 'my', 'dashboard' => 'my'))) && p('total') && e('20'); // 步骤3：my模块不过滤项目
r($blockTest->printQaOverviewBlockTest((object)array('module' => 'qa', 'dashboard' => 'qa'), true)) && p('total') && e('0'); // 步骤4：空数据情况
r($blockTest->printQaOverviewBlockTest((object)array('module' => 'qa', 'dashboard' => 'qa'))) && p('success') && e('1'); // 步骤5：成功执行验证

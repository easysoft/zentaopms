#!/usr/bin/env php
<?php

/**

title=测试 companyZen::loadProject();
timeout=0
cid=0

- 测试步骤1：正常情况加载项目数量验证 @11
- 测试步骤2：检查第一个项目名称属性1 @项目1
- 测试步骤3：检查默认项目标签 @项目
- 测试步骤4：检查第八个项目名称属性8 @项目8
- 测试步骤5：检查第七个项目名称属性7 @项目7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/company.unittest.class.php';

$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$table->code->range('PRJ001,PRJ002,PRJ003,PRJ004,PRJ005,PRJ006,PRJ007,PRJ008,PRJ009,PRJ010');
$table->type->range('project');
$table->status->range('wait{2},doing{5},suspended{1},closed{2}');
$table->deleted->range('0');
$table->project->range('0');
$table->vision->range('rnd');
$table->acl->range('open{6},private{4}');
$table->gen(10);

su('admin');

$companyTest = new companyTest();

r(count($companyTest->loadProjectTest())) && p() && e('11'); // 测试步骤1：正常情况加载项目数量验证
r($companyTest->loadProjectTest()) && p('1') && e('项目1'); // 测试步骤2：检查第一个项目名称
r($companyTest->loadProjectTest()) && p('0') && e('项目'); // 测试步骤3：检查默认项目标签
r($companyTest->loadProjectTest()) && p('8') && e('项目8'); // 测试步骤4：检查第八个项目名称
r($companyTest->loadProjectTest()) && p('7') && e('项目7'); // 测试步骤5：检查第七个项目名称
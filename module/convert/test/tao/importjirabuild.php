#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraBuild();
timeout=0
cid=15856

- 步骤1：空数据列表处理属性message @Empty data list handled correctly
- 步骤2：单个有效版本数据导入属性validCount @1
- 步骤3：多个版本数据批量导入属性validCount @3
- 步骤4：包含无效数据的混合数据导入属性validCount @3
- 步骤5：大量数据批量导入测试属性dataCount @15

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
$table = zenData('project');
$table->id->range('1001-1010');
$table->name->range('TestProject{10}');
$table->type->range('project');
$table->status->range('doing');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->importJiraBuildTest(array())) && p('message') && e('Empty data list handled correctly'); // 步骤1：空数据列表处理
r($convertTest->importJiraBuildTest(array((object)array('id' => 1, 'project' => 1001, 'name' => 'Version1.0')))) && p('validCount') && e('1'); // 步骤2：单个有效版本数据导入
r($convertTest->importJiraBuildTest(array((object)array('id' => 1, 'project' => 1001, 'name' => 'Version1.0'), (object)array('id' => 2, 'project' => 1002, 'name' => 'Version2.0'), (object)array('id' => 3, 'project' => 1003, 'name' => 'Version3.0')))) && p('validCount') && e('3'); // 步骤3：多个版本数据批量导入
r($convertTest->importJiraBuildTest(array((object)array('id' => 1, 'project' => 1001, 'name' => 'Version1.0'), (object)array('id' => 2, 'project' => 999, 'name' => 'InvalidProject'), (object)array('name' => 'NoId'), (object)array('id' => 3, 'project' => 1003, 'name' => 'Version3.0')))) && p('validCount') && e('3'); // 步骤4：包含无效数据的混合数据导入
r($convertTest->importJiraBuildTest(array_fill(0, 15, (object)array('id' => 1, 'project' => 1001, 'name' => 'BulkVersion')))) && p('dataCount') && e('15'); // 步骤5：大量数据批量导入测试
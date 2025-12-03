#!/usr/bin/env php
<?php

/**

title=测试 screenModel::genNotFoundOrDraftComponentOption();
timeout=0
cid=0

- 步骤1：空component参数，chart类型，有图表名称
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤2：空component参数，pivot类型，有透视表名称
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤3：空component对象，chart类型，无图表名称
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤4：有option但无title的component，chart类型
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤5：有option和title的component，pivot类型
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤6：完整component结构，chart类型，测试notFoundText内容属性notFoundText @图表 销售图表 未找到或处于草稿状态
- 步骤7：完整component结构，pivot类型，测试notFoundText内容属性notFoundText @透视表 数据透视 未找到或处于草稿状态

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($screenTest->genNotFoundOrDraftComponentOptionTest(null, (object)array('name' => '测试图表'), 'chart')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤1：空component参数，chart类型，有图表名称
r($screenTest->genNotFoundOrDraftComponentOptionTest(null, (object)array('name' => '测试透视表'), 'pivot')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤2：空component参数，pivot类型，有透视表名称
r($screenTest->genNotFoundOrDraftComponentOptionTest((object)array(), (object)array(), 'chart')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤3：空component对象，chart类型，无图表名称
r($screenTest->genNotFoundOrDraftComponentOptionTest((object)array('option' => new stdclass()), (object)array('name' => '图表A'), 'chart')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤4：有option但无title的component，chart类型
r($screenTest->genNotFoundOrDraftComponentOptionTest((object)array('option' => (object)array('title' => new stdclass())), (object)array('name' => '透视表B'), 'pivot')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤5：有option和title的component，pivot类型
r($screenTest->genNotFoundOrDraftComponentOptionTest((object)array('id' => 1, 'name' => 'test', 'option' => (object)array('title' => (object)array('text' => 'existing'))), (object)array('name' => '销售图表'), 'chart')) && p('notFoundText') && e('图表 销售图表 未找到或处于草稿状态'); // 步骤6：完整component结构，chart类型，测试notFoundText内容
r($screenTest->genNotFoundOrDraftComponentOptionTest((object)array('id' => 2, 'option' => (object)array('dataset' => array())), (object)array('name' => '数据透视'), 'pivot')) && p('notFoundText') && e('透视表 数据透视 未找到或处于草稿状态'); // 步骤7：完整component结构，pivot类型，测试notFoundText内容
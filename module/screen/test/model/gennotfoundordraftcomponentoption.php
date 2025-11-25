#!/usr/bin/env php
<?php

/**

title=测试 screenModel::genNotFoundOrDraftComponentOption();
timeout=0
cid=18231

- 步骤1：测试chart类型,空component和空chart对象
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤2：测试chart类型,空component和有name的chart对象
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
 - 属性notFoundText @图表 测试图表 未找到或处于草稿状态
- 步骤3：测试pivot类型,空component和有name的chart对象
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
 - 属性notFoundText @透视表 测试透视表 未找到或处于草稿状态
- 步骤4：测试chart类型,已有option的component对象
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤5：测试chart类型,已有option和title的component对象
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($screenTest->genNotFoundOrDraftComponentOptionTest(null, (object)array(), 'chart')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤1：测试chart类型,空component和空chart对象
r($screenTest->genNotFoundOrDraftComponentOptionTest(null, (object)array('name' => '测试图表'), 'chart')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted,notFoundText') && e('1,1,1,1,图表 测试图表 未找到或处于草稿状态'); // 步骤2：测试chart类型,空component和有name的chart对象
r($screenTest->genNotFoundOrDraftComponentOptionTest(null, (object)array('name' => '测试透视表'), 'pivot')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted,notFoundText') && e('1,1,1,1,透视表 测试透视表 未找到或处于草稿状态'); // 步骤3：测试pivot类型,空component和有name的chart对象
r($screenTest->genNotFoundOrDraftComponentOptionTest((object)array('option' => new stdclass()), (object)array('name' => '测试图表2'), 'chart')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤4：测试chart类型,已有option的component对象
r($screenTest->genNotFoundOrDraftComponentOptionTest((object)array('option' => (object)array('title' => new stdclass())), (object)array('name' => '测试图表3'), 'chart')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤5：测试chart类型,已有option和title的component对象
#!/usr/bin/env php
<?php

/**

title=测试 screenModel::genNotFoundOrDraftComponentOption();
timeout=0
cid=0

- 步骤1：测试component为null、chart有名称、type为chart的情况
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤2：测试component为null、chart有名称、type为pivot的情况
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤3：测试component为空对象、chart为null、type为chart的情况
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤4：测试component为空对象、chart无名称、type为pivot的情况
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤5：验证chart类型空名称的错误消息文本属性notFoundText @图表  未找到或处于草稿状态

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 此方法不依赖数据库数据，主要测试逻辑处理

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 准备测试数据
$chartWithName = new stdclass();
$chartWithName->name = 'Test Chart';

$chartEmpty = new stdclass();

$componentWithOption = new stdclass();
$componentWithOption->option = new stdclass();
$componentWithOption->option->title = new stdclass();
$componentWithOption->option->title->text = 'Existing Title';

// 5. 强制要求：必须包含至少5个测试步骤
r($screenTest->genNotFoundOrDraftComponentOptionTest(null, $chartWithName, 'chart')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤1：测试component为null、chart有名称、type为chart的情况
r($screenTest->genNotFoundOrDraftComponentOptionTest(null, $chartWithName, 'pivot')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤2：测试component为null、chart有名称、type为pivot的情况
r($screenTest->genNotFoundOrDraftComponentOptionTest(new stdclass(), null, 'chart')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤3：测试component为空对象、chart为null、type为chart的情况
r($screenTest->genNotFoundOrDraftComponentOptionTest(new stdclass(), $chartEmpty, 'pivot')) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤4：测试component为空对象、chart无名称、type为pivot的情况
r($screenTest->genNotFoundOrDraftComponentOptionTest(null, null, 'chart')) && p('notFoundText') && e('图表  未找到或处于草稿状态'); // 步骤5：验证chart类型空名称的错误消息文本
#!/usr/bin/env php
<?php

/**

title=测试 screenModel::unsetComponentDraftMarker();
timeout=0
cid=18284

- 步骤1：完整组件isDeleted属性被移除 @0
- 步骤2：完整组件notFoundText属性被移除 @0
- 步骤3：只有isDeleted的组件，isDeleted被移除 @0
- 步骤4：只有notFoundText的组件，notFoundText被移除 @0
- 步骤5：空组件option属性依然存在 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$screenTest = new screenTest();

// 4. 准备测试数据 - 创建不同类型的组件对象

// 完整组件，包含isDeleted和notFoundText
$fullComponent = new stdClass();
$fullComponent->option = new stdClass();
$fullComponent->option->isDeleted = true;
$fullComponent->option->title = new stdClass();
$fullComponent->option->title->notFoundText = '组件未找到或已删除';
$fullComponent->option->title->text = '正常标题';
$fullComponent->option->otherProp = 'keep this';

// 只有isDeleted的组件
$deletedComponent = new stdClass();
$deletedComponent->option = new stdClass();
$deletedComponent->option->isDeleted = true;
$deletedComponent->option->title = new stdClass();
$deletedComponent->option->title->text = '正常标题';
$deletedComponent->option->normalProp = 'normal';

// 只有notFoundText的组件
$notFoundComponent = new stdClass();
$notFoundComponent->option = new stdClass();
$notFoundComponent->option->title = new stdClass();
$notFoundComponent->option->title->notFoundText = '组件未找到';
$notFoundComponent->option->title->text = '正常标题';
$notFoundComponent->option->data = 'some data';

// 空组件，有option但没有相关属性
$emptyComponent = new stdClass();
$emptyComponent->option = new stdClass();
$emptyComponent->option->someProp = 'value';

// 无option属性的组件
$noOptionComponent = new stdClass();
$noOptionComponent->id = 1;
$noOptionComponent->name = 'test';

// 5. 执行测试步骤
$result1 = $screenTest->unsetComponentDraftMarkerTest($fullComponent);
$result2 = $screenTest->unsetComponentDraftMarkerTest($deletedComponent);
$result3 = $screenTest->unsetComponentDraftMarkerTest($notFoundComponent);
$result4 = $screenTest->unsetComponentDraftMarkerTest($emptyComponent);
$result5 = $screenTest->unsetComponentDraftMarkerTest($noOptionComponent);

// 检查isDeleted属性是否被移除
r(isset($result1->option->isDeleted)) && p() && e('0'); // 步骤1：完整组件isDeleted属性被移除
r(isset($result1->option->title->notFoundText)) && p() && e('0'); // 步骤2：完整组件notFoundText属性被移除
r(isset($result2->option->isDeleted)) && p() && e('0'); // 步骤3：只有isDeleted的组件，isDeleted被移除
r(isset($result3->option->title->notFoundText)) && p() && e('0'); // 步骤4：只有notFoundText的组件，notFoundText被移除
r(isset($result4->option)) && p() && e('1'); // 步骤5：空组件option属性依然存在
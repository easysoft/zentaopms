#!/usr/bin/env php
<?php

/**

title=测试 screenModel::initOptionTitle();
timeout=0
cid=18265

- 执行screenTest模块的initOptionTitleTest方法，参数是$component1, 'pivot', 'Test Pivot Chart' 第option条的caption属性 @Test Pivot Chart
- 执行screenTest模块的initOptionTitleTest方法，参数是$component2, 'pivot', 'New Chart Name' 第option条的caption属性 @Existing Caption
- 执行$result3->option->title->text) ? $result3->option->title->text :  @Test Line Chart
- 执行$result4->option->title->text) ? $result4->option->title->text :  @Existing Title
- 执行screenTest模块的initOptionTitleTest方法，参数是$component5, 'invalid_type', 'Test Chart' 属性testProperty @original

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$screenTest = new screenModelTest();

// 测试步骤1：pivot类型未设置caption时自动设置
$component1 = new stdclass();
$component1->option = new stdclass();
r($screenTest->initOptionTitleTest($component1, 'pivot', 'Test Pivot Chart')) && p('option:caption') && e('Test Pivot Chart');

// 测试步骤2：pivot类型已设置caption时不覆盖
$component2 = new stdclass();
$component2->option = new stdclass();
$component2->option->caption = 'Existing Caption';
r($screenTest->initOptionTitleTest($component2, 'pivot', 'New Chart Name')) && p('option:caption') && e('Existing Caption');

// 测试步骤3：chart类型未设置title时自动设置  
$component3 = new stdclass();
$component3->option = new stdclass();
$result3 = $screenTest->initOptionTitleTest($component3, 'chart', 'Test Line Chart');
r(isset($result3->option->title->text) ? $result3->option->title->text : '') && p() && e('Test Line Chart');

// 测试步骤4：chart类型已设置title时不覆盖
$component4 = new stdclass();
$component4->option = new stdclass();
$component4->option->title = new stdclass();
$component4->option->title->text = 'Existing Title';
$component4->option->title->show = true;
$result4 = $screenTest->initOptionTitleTest($component4, 'chart', 'New Chart Name');
r(isset($result4->option->title->text) ? $result4->option->title->text : '') && p() && e('Existing Title');

// 测试步骤5：无效类型参数处理
$component5 = new stdclass();
$component5->option = new stdclass();
$component5->testProperty = 'original';
r($screenTest->initOptionTitleTest($component5, 'invalid_type', 'Test Chart')) && p('testProperty') && e('original');
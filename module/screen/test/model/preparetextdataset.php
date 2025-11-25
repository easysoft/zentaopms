#!/usr/bin/env php
<?php

/**

title=测试 screenModel::prepareTextDataset();
timeout=0
cid=18276

- 执行option模块的dataset方法  @Hello World
- 执行option模块的dataset方法  @0
- 执行option模块的dataset方法  @Special & <> chars
- 执行option模块的dataset方法  @Long text content for testing purpose
- 执行styles模块的opacity方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

su('admin');
$screenTest = new screenTest();

// 准备测试组件
$component1 = new stdclass();
$component1->option = new stdclass();

$component2 = new stdclass();
$component2->option = new stdclass();

$component3 = new stdclass();
$component3->option = new stdclass();

$component4 = new stdclass();
$component4->option = new stdclass();

$component5 = new stdclass();
$component5->option = new stdclass();

$result1 = $screenTest->prepareTextDatasetTest($component1, 'Hello World');
r($result1->option->dataset) && p('') && e('Hello World');
$result2 = $screenTest->prepareTextDatasetTest($component2, '');
r($result2->option->dataset) && p('') && e('0');
$result3 = $screenTest->prepareTextDatasetTest($component3, 'Special & <> chars');
r($result3->option->dataset) && p('') && e('Special & <> chars');
$result4 = $screenTest->prepareTextDatasetTest($component4, 'Long text content for testing purpose');
r($result4->option->dataset) && p('') && e('Long text content for testing purpose');
$result5 = $screenTest->prepareTextDatasetTest($component5, 'Test text');
r($result5->styles->opacity) && p('') && e('1');
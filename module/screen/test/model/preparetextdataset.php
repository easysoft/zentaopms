#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

/**

title=测试 screenModel::prepareTextDataset();
timeout=0
cid=1

- 执行screen模块的prepareTextDataset方法，参数是$component1, 'Hello World' 属性option->dataset @Hello World
- 执行screen模块的prepareTextDataset方法，参数是$component2, '' 属性option->dataset @
- 执行screen模块的prepareTextDataset方法，参数是$component3, 'Special & <> chars' 属性option->dataset @Special & <> chars
- 执行screen模块的prepareTextDataset方法，参数是$component4, 'Long text content for testing purpose' 属性option->dataset @Long text content for testing purpose
- 执行screen模块的prepareTextDataset方法，参数是$component5, 'Test text' 属性styles->opacity @1

*/

$screen = new screenTest();

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

r($screen->prepareTextDataset($component1, 'Hello World')) && p('option->dataset') && e('Hello World');
r($screen->prepareTextDataset($component2, '')) && p('option->dataset') && e('');
r($screen->prepareTextDataset($component3, 'Special & <> chars')) && p('option->dataset') && e('Special & <> chars');
r($screen->prepareTextDataset($component4, 'Long text content for testing purpose')) && p('option->dataset') && e('Long text content for testing purpose');
r($screen->prepareTextDataset($component5, 'Test text')) && p('styles->opacity') && e('1');
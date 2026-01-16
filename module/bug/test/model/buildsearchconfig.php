#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

/**

title=bugModel->buildSearchConfig();
cid=15346
- 测试字段名称
 - 属性title @Bug标题
 - 属性module @所属模块
 - 属性steps @重现步骤
- 测试指派给字段
 - 属性operator @=
 - 属性control @select
- 测试严重程度字段 @6

*/

$bugTest = new bugModelTest();
$searchConfig = $bugTest->instance->buildSearchConfig(1, 'story');

r($searchConfig['fields']) && p('title,module,steps') && e('Bug标题,所属模块,重现步骤'); // 测试字段名称
r($searchConfig['params']['assignedTo']) && p('operator,control') && e('=,select');     // 测试指派给字段
r(count($searchConfig['params']['severity']['values'])) && p('') && e('6');             // 测试严重程度字段

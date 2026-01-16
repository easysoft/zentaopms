#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

/**

title=caselibModel->buildSearchConfig();
cid=15525
- 测试字段名称
 - 属性title @用例名称
 - 属性story @关联需求
 - 属性type @用例类型
- 测试模块字段
 - 属性operator @belong
 - 属性control @select
- 测试用例类型字段 @10

*/

$caselib = new caselibModelTest();
$searchConfig = $caselib->instance->buildSearchConfig(1);

r($searchConfig['fields']) && p('title,story,type') && e('用例名称,关联需求,用例类型'); // 测试字段名称
r($searchConfig['params']['module']) && p('operator,control') && e('belong,select');   // 测试模块字段
r(count($searchConfig['params']['type']['values'])) && p('') && e('10');               // 测试用例类型字段

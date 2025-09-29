#!/usr/bin/env php
<?php

/**

title=测试 docModel::getDocTemplateSpaces();
timeout=0
cid=0

- 步骤1：检查返回类型为数组 @array
- 步骤2：检查第一个模板空间名称属性1 @模板空间1
- 步骤3：检查第二个模板空间名称属性2 @模板空间2
- 步骤4：检查第三个模板空间名称属性3 @模板空间3
- 步骤5：检查返回数组的元素数量属性count() @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$table = zenData('doclib');
$table->id->range('1-10');
$table->type->range('doctemplate{5},product{2},custom{3}');
$table->vision->range('rnd{10}');
$table->name->range('模板空间1,模板空间2,模板空间3,模板空间4,模板空间5,产品空间1,产品空间2,自定义空间1,自定义空间2,自定义空间3');
$table->parent->range('0{10}');
$table->acl->range('open{7},private{3}');
$table->deleted->range('0{10}');
$table->gen(10);

su('admin');

$docTest = new docTest();

r($docTest->getDocTemplateSpacesTest()) && p() && e('array'); // 步骤1：检查返回类型为数组
r($docTest->getDocTemplateSpacesTest()) && p('1') && e('模板空间1'); // 步骤2：检查第一个模板空间名称
r($docTest->getDocTemplateSpacesTest()) && p('2') && e('模板空间2'); // 步骤3：检查第二个模板空间名称
r($docTest->getDocTemplateSpacesTest()) && p('3') && e('模板空间3'); // 步骤4：检查第三个模板空间名称
r($docTest->getDocTemplateSpacesTest()) && p('count()') && e('3'); // 步骤5：检查返回数组的元素数量
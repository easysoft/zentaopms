#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getExportFileName();
timeout=0
cid=0

- 步骤1:有执行ID时,使用执行名称+Bug @执行一-Bug
- 步骤2:无执行ID,有产品名称和browseType @产品一-全部Bug
- 步骤3:无执行ID,browseType是未关闭 @产品二-未关闭Bug
- 步骤4:无执行ID,browseType是assigntome @测试产品-指派给我Bug
- 步骤5:无执行ID,产品名称为空,browseType为all @-全部Bug
- 步骤6:有执行ID,执行名称包含特殊字符 @测试执行-Bug
- 步骤7:有执行ID,ID不存在时返回空名称 @-Bug

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('执行一,执行二,测试执行,开发执行,Sprint01,Stage01,项目A,项目B,项目C,项目D');
$project->type->range('sprint{4},stage,sprint,project{4}');
$project->status->range('doing');
$project->deleted->range('0');
$project->gen(10);

zenData('product')->gen(10);

su('admin');

$bugTest = new bugZenTest();

$product1 = (object)array('id' => 1, 'name' => '产品一', 'type' => 'normal', 'shadow' => 0);
$product2 = (object)array('id' => 2, 'name' => '产品二', 'type' => 'normal', 'shadow' => 0);
$product3 = (object)array('id' => 3, 'name' => '测试产品', 'type' => 'normal', 'shadow' => 0);
$emptyProduct = (object)array('id' => 0, 'name' => '', 'type' => 'normal', 'shadow' => 0);

r($bugTest->getExportFileNameTest(1, 'all', false)) && p() && e('执行一-Bug'); // 步骤1:有执行ID时,使用执行名称+Bug
r($bugTest->getExportFileNameTest(0, 'all', $product1)) && p() && e('产品一-全部Bug'); // 步骤2:无执行ID,有产品名称和browseType
r($bugTest->getExportFileNameTest(0, 'unclosed', $product2)) && p() && e('产品二-未关闭Bug'); // 步骤3:无执行ID,browseType是未关闭
r($bugTest->getExportFileNameTest(0, 'assigntome', $product3)) && p() && e('测试产品-指派给我Bug'); // 步骤4:无执行ID,browseType是assigntome
r($bugTest->getExportFileNameTest(0, 'all', $emptyProduct)) && p() && e('-全部Bug'); // 步骤5:无执行ID,产品名称为空,browseType为all
r($bugTest->getExportFileNameTest(3, 'all', false)) && p() && e('测试执行-Bug'); // 步骤6:有执行ID,执行名称包含特殊字符
r($bugTest->getExportFileNameTest(999, 'all', false)) && p() && e('-Bug'); // 步骤7:有执行ID,ID不存在时返回空名称
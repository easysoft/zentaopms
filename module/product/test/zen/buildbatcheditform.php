#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildBatchEditForm();
timeout=0
cid=17562

- 步骤1:空产品ID列表属性productsCount @0
- 步骤2:单个产品ID
 - 属性productsCount @1
 - 属性hasFields @1
- 步骤3:多个产品ID
 - 属性productsCount @3
 - 属性hasFields @1
- 步骤4:不存在的产品ID属性productsCount @0
- 步骤5:项目集ID和产品ID列表
 - 属性programID @1
 - 属性productsCount @2
 - 属性hasFields @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('buildbatcheditform/product', false, 2)->gen(10);
zenData('project')->loadYaml('buildbatcheditform/project', false, 2)->gen(3);
zenData('user')->gen(5);
zenData('module')->gen(0);
zenData('usergroup')->gen(0);

su('admin');

$productTest = new productZenTest();

r($productTest->buildBatchEditFormTest(0, array())) && p('productsCount') && e('0'); // 步骤1:空产品ID列表
r($productTest->buildBatchEditFormTest(0, array(1))) && p('productsCount,hasFields') && e('1,1'); // 步骤2:单个产品ID
r($productTest->buildBatchEditFormTest(0, array(1, 2, 3))) && p('productsCount,hasFields') && e('3,1'); // 步骤3:多个产品ID
r($productTest->buildBatchEditFormTest(0, array(999))) && p('productsCount') && e('0'); // 步骤4:不存在的产品ID
r($productTest->buildBatchEditFormTest(1, array(1, 2))) && p('programID,productsCount,hasFields') && e('1,2,1'); // 步骤5:项目集ID和产品ID列表
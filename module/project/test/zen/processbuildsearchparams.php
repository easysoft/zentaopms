#!/usr/bin/env php
<?php

/**

title=测试 projectZen::processBuildSearchParams();
timeout=0
cid=0

- 步骤1:无产品项目,product字段应该被移除第fields条的name属性 @Name
- 步骤2:有产品项目,product字段应该存在第fields条的product属性 @Product
- 步骤3:多迭代项目,execution字段应该被添加第fields条的name属性 @Name
- 步骤4:单迭代项目,execution字段不应该存在第fields条的name属性 @Name
- 步骤5:多分支产品,branch字段的params被正确设置第fields条的product属性 @Product
- 步骤6:普通产品,branch字段不应该存在第fields条的name属性 @Name
- 步骤7:测试返回数组包含name字段第fields条的product属性 @Product

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('project')->gen(10);
zenData('product')->gen(5);

su('admin');

$projectTest = new projectZenTest();

$normalProduct = new stdclass();
$normalProduct->id = 1;
$normalProduct->type = 'normal';

$branchProduct = new stdclass();
$branchProduct->id = 2;
$branchProduct->type = 'branch';

$products = array(1 => 'Product1', 2 => 'Product2');

r($projectTest->processBuildSearchParamsTest((object)array('id' => 1, 'multiple' => 0, 'hasProduct' => 0, 'model' => 'scrum'), $normalProduct, $products, 'all', 0)) && p('fields:name') && e('Name'); // 步骤1:无产品项目,product字段应该被移除
r($projectTest->processBuildSearchParamsTest((object)array('id' => 2, 'multiple' => 0, 'hasProduct' => 1, 'model' => 'scrum'), $normalProduct, $products, 'all', 0)) && p('fields:product') && e('Product'); // 步骤2:有产品项目,product字段应该存在
r($projectTest->processBuildSearchParamsTest((object)array('id' => 3, 'multiple' => 1, 'hasProduct' => 1, 'model' => 'scrum'), $normalProduct, $products, 'all', 0)) && p('fields:name') && e('Name'); // 步骤3:多迭代项目,execution字段应该被添加
r($projectTest->processBuildSearchParamsTest((object)array('id' => 4, 'multiple' => 0, 'hasProduct' => 1, 'model' => 'scrum'), $normalProduct, $products, 'all', 0)) && p('fields:name') && e('Name'); // 步骤4:单迭代项目,execution字段不应该存在
r($projectTest->processBuildSearchParamsTest((object)array('id' => 5, 'multiple' => 0, 'hasProduct' => 1, 'model' => 'scrum'), $branchProduct, $products, 'all', 0)) && p('fields:product') && e('Product'); // 步骤5:多分支产品,branch字段的params被正确设置
r($projectTest->processBuildSearchParamsTest((object)array('id' => 6, 'multiple' => 0, 'hasProduct' => 1, 'model' => 'scrum'), $normalProduct, $products, 'all', 0)) && p('fields:name') && e('Name'); // 步骤6:普通产品,branch字段不应该存在
r($projectTest->processBuildSearchParamsTest((object)array('id' => 7, 'multiple' => 0, 'hasProduct' => 1, 'model' => 'scrum'), $normalProduct, $products, 'bysearch', 10)) && p('fields:product') && e('Product'); // 步骤7:测试返回数组包含name字段
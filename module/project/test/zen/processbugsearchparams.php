#!/usr/bin/env php
<?php

/**

title=测试 projectZen::processBugSearchParams();
timeout=0
cid=17955

- 执行$searchConfig1['fields']['product'] @1
- 执行$searchConfig2['fields']['product'] @1
- 执行$searchConfig3['fields']['product']) && !isset($searchConfig3['fields']['plan'] @1
- 执行$searchConfig4['fields']['plan'] @1
- 执行$searchConfig5['fields']['project'] @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('project')->gen(10);
zenData('product')->gen(5);

su('admin');

$projectTest = new projectZenTest();

// 步骤1:有产品的项目,product字段应该存在
$project1 = new stdclass();
$project1->id = 1;
$project1->hasProduct = 1;
$project1->multiple = 1;
$project1->model = 'scrum';
$products1 = array(1 => (object)array('id' => 1, 'name' => 'Product 1'));
$searchConfig1 = $projectTest->processBugSearchParamsTest($project1, 'bysearch', 1, 1, 1, '0', 'id_desc', 0, $products1);
r(isset($searchConfig1['fields']['product'])) && p() && e('1');

// 步骤2:无产品的scrum项目,product字段不存在但plan字段存在
$project2 = new stdclass();
$project2->id = 2;
$project2->hasProduct = 0;
$project2->multiple = 1;
$project2->model = 'scrum';
$products2 = array();
$searchConfig2 = $projectTest->processBugSearchParamsTest($project2, 'bysearch', 2, 2, 0, '0', 'id_desc', 0, $products2);
r(!isset($searchConfig2['fields']['product'])) && p() && e('1');

// 步骤3:无产品的瀑布项目,product和plan字段都不存在
$project3 = new stdclass();
$project3->id = 3;
$project3->hasProduct = 0;
$project3->multiple = 1;
$project3->model = 'waterfall';
$products3 = array();
$searchConfig3 = $projectTest->processBugSearchParamsTest($project3, 'bysearch', 3, 3, 0, '0', 'id_desc', 0, $products3);
r(!isset($searchConfig3['fields']['product']) && !isset($searchConfig3['fields']['plan'])) && p() && e('1');

// 步骤4:无迭代无产品的项目,plan字段应该不存在
$project4 = new stdclass();
$project4->id = 4;
$project4->hasProduct = 0;
$project4->multiple = 0;
$project4->model = 'scrum';
$products4 = array();
$searchConfig4 = $projectTest->processBugSearchParamsTest($project4, 'all', 0, 4, 0, '0', 'id_desc', 0, $products4);
r(!isset($searchConfig4['fields']['plan'])) && p() && e('1');

// 步骤5:有产品的项目,project字段应该不存在
$project5 = new stdclass();
$project5->id = 5;
$project5->hasProduct = 1;
$project5->multiple = 1;
$project5->model = 'scrum';
$products5 = array(1 => (object)array('id' => 1, 'name' => 'Product 1'));
$searchConfig5 = $projectTest->processBugSearchParamsTest($project5, 'all', 0, 5, 1, '0', 'id_desc', 0, $products5);
r(!isset($searchConfig5['fields']['project'])) && p() && e('1');
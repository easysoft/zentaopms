#!/usr/bin/env php
<?php

/**

title=测试 projectZen::processGroupPrivs();
timeout=0
cid=0

- 步骤1:有产品的项目,productplan资源应被移除 @0
- 步骤2:有产品的项目,tree资源应被移除 @0
- 步骤3:无产品的项目,projectstory资源应存在 @1
- 步骤4:瀑布模型项目,productplan资源应被移除 @0
- 步骤5:瀑布模型项目,projectplan资源应被移除 @0
- 步骤6:Scrum模型项目,projectstory.track应被移除 @0
- 步骤7:无迭代且无产品的项目,story资源应存在 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$projectTest = new projectZenTest();

$project1 = new stdclass();
$project1->hasProduct = 1;
$project1->model = 'scrum';
$project1->multiple = 1;
$result1 = $projectTest->processGroupPrivsTest($project1);
r(property_exists($result1, 'productplan')) && p() && e('0'); // 步骤1:有产品的项目,productplan资源应被移除

$project2 = new stdclass();
$project2->hasProduct = 1;
$project2->model = 'scrum';
$project2->multiple = 1;
$result2 = $projectTest->processGroupPrivsTest($project2);
r(property_exists($result2, 'tree')) && p() && e('0'); // 步骤2:有产品的项目,tree资源应被移除

$project3 = new stdclass();
$project3->hasProduct = 0;
$project3->model = 'scrum';
$project3->multiple = 1;
$result3 = $projectTest->processGroupPrivsTest($project3);
r(property_exists($result3, 'projectstory')) && p() && e('1'); // 步骤3:无产品的项目,projectstory资源应存在

$project4 = new stdclass();
$project4->hasProduct = 1;
$project4->model = 'waterfall';
$project4->multiple = 1;
$result4 = $projectTest->processGroupPrivsTest($project4);
r(property_exists($result4, 'productplan')) && p() && e('0'); // 步骤4:瀑布模型项目,productplan资源应被移除

$project5 = new stdclass();
$project5->hasProduct = 1;
$project5->model = 'waterfall';
$project5->multiple = 1;
$result5 = $projectTest->processGroupPrivsTest($project5);
r(property_exists($result5, 'projectplan')) && p() && e('0'); // 步骤5:瀑布模型项目,projectplan资源应被移除

$project6 = new stdclass();
$project6->hasProduct = 1;
$project6->model = 'scrum';
$project6->multiple = 1;
$result6 = $projectTest->processGroupPrivsTest($project6);
r(isset($result6->projectstory->track)) && p() && e('0'); // 步骤6:Scrum模型项目,projectstory.track应被移除

$project7 = new stdclass();
$project7->hasProduct = 0;
$project7->model = 'scrum';
$project7->multiple = 0;
$result7 = $projectTest->processGroupPrivsTest($project7);
r(property_exists($result7, 'story')) && p() && e('1'); // 步骤7:无迭代且无产品的项目,story资源应存在
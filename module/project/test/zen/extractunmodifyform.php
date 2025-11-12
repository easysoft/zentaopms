#!/usr/bin/env php
<?php

/**

title=测试 projectZen::extractUnModifyForm();
timeout=0
cid=0

- 执行$result1, 'linkedProducts' @1
- 执行$result2, 'allProducts' @1
- 执行$result3, 'unmodifiableProducts' @1
- 执行$result4, 'unmodifiableProducts' @1
- 执行$result5, 'linkedBranches' @1
- 执行$result6, 'linkedProducts' @1
- 执行$result7, 'allProducts' @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

zendata('project')->loadYaml('extractunmodifyform', false, 2)->gen(10);
zendata('product')->loadYaml('extractunmodifyform', false, 2)->gen(10);
zendata('projectproduct')->loadYaml('extractunmodifyform', false, 2)->gen(16);
zendata('branch')->loadYaml('extractunmodifyform', false, 2)->gen(8);
zendata('projectstory')->loadYaml('extractunmodifyform', false, 2)->gen(10);

su('admin');

$project1 = new stdclass();
$project1->id = 1;
$project1->parent = 1;
$project1->model = 'scrum';
$project1->path = ',1,';

$project2 = new stdclass();
$project2->id = 2;
$project2->parent = 1;
$project2->model = 'waterfall';
$project2->path = ',1,';

$project3 = new stdclass();
$project3->id = 3;
$project3->parent = 2;
$project3->model = 'scrum';
$project3->path = ',2,';

$project5 = new stdclass();
$project5->id = 5;
$project5->parent = 2;
$project5->model = 'waterfall';
$project5->path = ',2,';

$project6 = new stdclass();
$project6->id = 6;
$project6->parent = 2;
$project6->model = 'scrum';
$project6->path = ',2,';

$projectNonExist = new stdclass();
$projectNonExist->id = 999;
$projectNonExist->parent = 1;
$projectNonExist->model = 'scrum';
$projectNonExist->path = ',1,';

$projectNoProduct = new stdclass();
$projectNoProduct->id = 5;
$projectNoProduct->parent = 2;
$projectNoProduct->model = 'scrum';
$projectNoProduct->path = ',2,';

$projectTest = new projectzenTest();

$result1 = $projectTest->extractUnModifyFormTest(1, $project1);
r(property_exists($result1, 'linkedProducts')) && p() && e('1');
$result2 = $projectTest->extractUnModifyFormTest(1, $project1);
r(property_exists($result2, 'allProducts')) && p() && e('1');
$result3 = $projectTest->extractUnModifyFormTest(1, $project1);
r(property_exists($result3, 'unmodifiableProducts')) && p() && e('1');
$result4 = $projectTest->extractUnModifyFormTest(2, $project2);
r(property_exists($result4, 'unmodifiableProducts')) && p() && e('1');
$result5 = $projectTest->extractUnModifyFormTest(1, $project1);
r(property_exists($result5, 'linkedBranches')) && p() && e('1');
$result6 = $projectTest->extractUnModifyFormTest(999, $projectNonExist);
r(property_exists($result6, 'linkedProducts')) && p() && e('1');
$result7 = $projectTest->extractUnModifyFormTest(5, $projectNoProduct);
r(property_exists($result7, 'allProducts')) && p() && e('1');
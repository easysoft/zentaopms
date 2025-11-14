#!/usr/bin/env php
<?php

/**

title=测试 productZen::getProductPlans();
timeout=0
cid=17592

- 步骤1:正常情况,isProjectStory为true且storyType为story
 - 第1条的3属性 @计划3 [2025-01-25 ~ 2025-12-31]
 - 第2条的5属性 @计划5 [2025-02-08 ~ 2025-12-31]
- 步骤2:isProjectStory为false @0
- 步骤3:storyType为requirement时 @0
- 步骤4:projectProducts为空数组 @0
- 步骤5:projectID为0时仍返回计划
 - 第1条的3属性 @计划3 [2025-01-25 ~ 2025-12-31]
 - 第2条的5属性 @计划5 [2025-02-08 ~ 2025-12-31]

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->type->range('normal{10}');
$product->status->range('normal{10}');
$product->gen(10);

$plan = zenData('productplan');
$plan->id->range('1-20');
$plan->product->range('1,1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10');
$plan->branch->range('0');
$plan->title->range('计划1,计划2,计划3,计划4,计划5,计划6,计划7,计划8,计划9,计划10,计划11,计划12,计划13,计划14,计划15,计划16,计划17,计划18,计划19,计划20');
$plan->status->range('wait{5},doing{13},closed{2}');
$plan->end->range('`2025-12-31`{18},`2023-12-31`{2}');
$plan->deleted->range('0');
$plan->gen(20);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project{10}');
$project->hasProduct->range('1{10}');
$project->status->range('doing{10}');
$project->deleted->range('0');
$project->gen(10);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1,1,1,2,2,3,3,4,5,6');
$projectproduct->product->range('1,2,3,4,5,6,7,8,9,10');
$projectproduct->gen(10);

su('admin');

$productTest = new productZenTest();

$product1 = new stdclass();
$product1->id = 1;
$product1->name = 'Product 1';
$product1->type = 'normal';
$product1->branches = array();

$product2 = new stdclass();
$product2->id = 2;
$product2->name = 'Product 2';
$product2->type = 'normal';
$product2->branches = array();

$projectProducts1 = array(1 => $product1, 2 => $product2);
$projectProducts2 = array();

r($productTest->getProductPlansTest($projectProducts1, 1, 'story', true)) && p('1:3;2:5') && e('计划3 [2025-01-25 ~ 2025-12-31];计划5 [2025-02-08 ~ 2025-12-31]'); // 步骤1:正常情况,isProjectStory为true且storyType为story
r($productTest->getProductPlansTest($projectProducts1, 1, 'story', false)) && p() && e('0'); // 步骤2:isProjectStory为false
r($productTest->getProductPlansTest($projectProducts1, 1, 'requirement', true)) && p() && e('0'); // 步骤3:storyType为requirement时
r($productTest->getProductPlansTest($projectProducts2, 1, 'story', true)) && p() && e('0'); // 步骤4:projectProducts为空数组
r($productTest->getProductPlansTest($projectProducts1, 0, 'story', true)) && p('1:3;2:5') && e('计划3 [2025-01-25 ~ 2025-12-31];计划5 [2025-02-08 ~ 2025-12-31]'); // 步骤5:projectID为0时仍返回计划
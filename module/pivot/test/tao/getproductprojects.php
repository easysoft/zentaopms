#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getProductProjects();
timeout=0
cid=17447

- 测试步骤1:验证产品101关联项目11(type=project,hasProduct=0)属性101 @11
- 测试步骤2:验证产品102关联项目12(type=project,hasProduct=0)属性102 @12
- 测试步骤3:验证产品103关联项目13(type=project,hasProduct=0)属性103 @13
- 测试步骤4:验证产品104关联项目14(type=project,hasProduct=0)属性104 @14
- 测试步骤5:验证返回5条关联关系(type=project且hasProduct=0的项目) @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$project = zenData('project');
$project->id->range('11-15');
$project->project->range('1');
$project->type->range('project{5}');
$project->hasProduct->range('0{5}');
$project->name->range('项目11,项目12,项目13,项目14,项目15');
$project->deleted->range('0{5}');
$project->gen(5);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('11-15');
$projectproduct->product->range('101-105');
$projectproduct->branch->range('0{5}');
$projectproduct->plan->range('``{5}');
$projectproduct->roadmap->range('``{5}');
$projectproduct->gen(5);

su('admin');

$pivotTest = new pivotTaoTest();

r($pivotTest->getProductProjectsTest()) && p('101') && e('11'); // 测试步骤1:验证产品101关联项目11(type=project,hasProduct=0)
r($pivotTest->getProductProjectsTest()) && p('102') && e('12'); // 测试步骤2:验证产品102关联项目12(type=project,hasProduct=0)
r($pivotTest->getProductProjectsTest()) && p('103') && e('13'); // 测试步骤3:验证产品103关联项目13(type=project,hasProduct=0)
r($pivotTest->getProductProjectsTest()) && p('104') && e('14'); // 测试步骤4:验证产品104关联项目14(type=project,hasProduct=0)
r(count($pivotTest->getProductProjectsTest())) && p() && e('5'); // 测试步骤5:验证返回5条关联关系(type=project且hasProduct=0的项目)
#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

/**

title=测试 projectModel->updateProductStage();
timeout=0
cid=17879

- 检查阶段一关联产品一第product条的1属性 @1
- 检查阶段一关联分支第branch条的0属性 @0
- 检查阶段一关联计划第plan条的1属性 @1
- 检查阶段二关联产品二第product条的2属性 @2
- 检查阶段二关联分支
 - 第branch条的0属性 @0
 - 第branch条的1属性 @1
 - 第branch条的2属性 @2
- 检查阶段二关联计划第plan条的2属性 @2
- 检查阶段三关联产品三第product条的3属性 @3
- 检查阶段三关联分支
 - 第branch条的0属性 @0
 - 第branch条的3属性 @3
 - 第branch条的4属性 @4
- 检查阶段三关联计划第plan条的3属性 @3
- 检查阶段四关联产品四第product条的4属性 @4
- 检查阶段四关联分支
 - 第branch条的0属性 @0
 - 第branch条的5属性 @5
- 检查阶段四关联计划第plan条的4属性 @4

*/

zenData('projectproduct')->gen(0);

$project = zenData('project');
$project->id->range('1-5');
$project->project->range('0,1{4}');
$project->model->range("waterfall,[]{4}");
$project->type->range('project,stage{4}');
$project->parent->range('0,1{4}');
$project->path->range('`,1,`,`,1,2,`,`,1,3,`,`,1,4,`,`,1,5,`');
$project->grade->range('1,2{4}');
$project->name->range('瀑布项目,阶段一,阶段二,阶段三,阶段四,阶段五');
$project->code->range('waterfall,stage1,stage2,stage3,stage4,stage5');
$project->hasProduct->range(1);
$project->status->range('wait');
$project->stageBy->range('product');
$project->linkType->range('plan');
$project->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('2,3,4,5');
$projectProduct->product->range('1,2,3,4');
$projectProduct->branch->range('0');
$projectProduct->plan->range('');
$projectProduct->gen(4);

$postData = new stdClass();
$postData->products = array(1, 2, 3, 4);
$postData->branch   = array(array(0), array(1, 2), array(3, 4), array(5));
$postData->plans    = array(1 => array(1), 2 => array(2), 3 => array(3), 4 => array(4));

$projectTester = new projectTest();
r($projectTester->updateProductStageTest(1, $postData)[2]) && p('product:1')    && e('1');     //检查阶段一关联产品一
r($projectTester->updateProductStageTest(1, $postData)[2]) && p('branch:0')     && e('0');     //检查阶段一关联分支
r($projectTester->updateProductStageTest(1, $postData)[2]) && p('plan:1')       && e('1');     //检查阶段一关联计划
r($projectTester->updateProductStageTest(1, $postData)[3]) && p('product:2')    && e('2');     //检查阶段二关联产品二
r($projectTester->updateProductStageTest(1, $postData)[3]) && p('branch:0,1,2') && e('0,1,2'); //检查阶段二关联分支
r($projectTester->updateProductStageTest(1, $postData)[3]) && p('plan:2')       && e('2');     //检查阶段二关联计划
r($projectTester->updateProductStageTest(1, $postData)[4]) && p('product:3')    && e('3');     //检查阶段三关联产品三
r($projectTester->updateProductStageTest(1, $postData)[4]) && p('branch:0,3,4') && e('0,3,4'); //检查阶段三关联分支
r($projectTester->updateProductStageTest(1, $postData)[4]) && p('plan:3')       && e('3');     //检查阶段三关联计划
r($projectTester->updateProductStageTest(1, $postData)[5]) && p('product:4')    && e('4');     //检查阶段四关联产品四
r($projectTester->updateProductStageTest(1, $postData)[5]) && p('branch:0,5')   && e('0,5');   //检查阶段四关联分支
r($projectTester->updateProductStageTest(1, $postData)[5]) && p('plan:4')       && e('4');     //检查阶段四关联计划

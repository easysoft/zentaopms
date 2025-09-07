#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $bug = zenData('bug');
    $bug->id->range('1-10');
    $bug->product->range('1-5');
    $bug->project->range('1-5');
    $bug->openedBuild->range('1-3,trunk');
    $bug->resolvedBuild->range('1-3,trunk');
    $bug->gen(10);

    $product = zenData('product');
    $product->id->range('1-5');
    $product->name->range('产品1,产品2,产品3,产品4,产品5');
    $product->gen(5);

    $project = zenData('project');
    $project->id->range('1-5');
    $project->name->range('项目1,项目2,项目3,项目4,项目5');
    $project->type->range('project');
    $project->gen(5);

    $build = zenData('build');
    $build->id->range('1-3');
    $build->name->range('版本1,版本2,版本3');
    $build->gen(3);
}

/**

title=测试 bugModel::getRelatedObjects();
timeout=0
cid=0

- 步骤1：product对象(2空+5产品) @7
- 步骤2：project对象(2空+5项目) @7
- 步骤3：build对象(2空+1trunk+3版本) @5
- 步骤4：openedBuild转build @5
- 步骤5：无效类型 @2
- 步骤6：不存在类型 @2

*/

global $tester;
$tester->loadModel('bug');

initData();

r(count($tester->bug->getRelatedObjects('product', 'id,name'))) && p() && e('7'); // 步骤1：product对象(2空+5产品)
r(count($tester->bug->getRelatedObjects('project', 'id,name'))) && p() && e('7'); // 步骤2：project对象(2空+5项目)
r(count($tester->bug->getRelatedObjects('build', 'id,name'))) && p() && e('5'); // 步骤3：build对象(2空+1trunk+3版本)
r(count($tester->bug->getRelatedObjects('openedBuild', 'id,name'))) && p() && e('5'); // 步骤4：openedBuild转build
r(count($tester->bug->getRelatedObjects('invalidtype', 'id,name'))) && p() && e('2'); // 步骤5：无效类型
r(count($tester->bug->getRelatedObjects('nonexistent', 'id,name'))) && p() && e('2'); // 步骤6：不存在类型
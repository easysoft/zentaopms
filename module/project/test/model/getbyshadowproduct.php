#!/usr/bin/env php
<?php
/**

title=测试 projectModel::getByShadowProduct();
timeout=0
cid=17821

- 获取ID为0的产品关联的项目 @0
- 获取ID为2的产品关联的项目
 - 属性id @3
 - 属性name @项目3
 - 属性code @project3
 - 属性type @project
- 获取ID为5的产品关联的项目 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$project = zenData('project');
$project->id->range('2-5');
$project->project->range('2-5');
$project->name->prefix("项目")->range('2-5');
$project->code->prefix("project")->range('2-5');
$project->type->range("project");
$project->gen(4);

$projectproduct = zenData('projectproduct');
$projectproduct->product->range('1-3');
$projectproduct->project->range('2-4');
$projectproduct->branch->range("0{5}");
$projectproduct->gen(3);

global $tester;
$projectModel = $tester->loadModel('project');
r($projectModel->getByShadowProduct(0))     && p()                    && e('0');                        // 获取ID为0的产品关联的项目
r($projectModel->getByShadowProduct(2))     && p('id,name,code,type') && e('3,项目3,project3,project'); // 获取ID为2的产品关联的项目
r($projectModel->getByShadowProduct(5))     && p()                    && e('0');                        // 获取ID为5的产品关联的项目

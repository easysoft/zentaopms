#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

function initData()
{
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
}

/**

title=测试 projectModel::getByShadowProduct;
timeout=0
cid=1

- 执行projectTester模块的testGetByShadowPro方法，参数是2属性code @project3

- 执行projectTester模块的testGetByShadowPro方法，参数是5属性code @0

- 执行projectTester模块的testGetByShadowPro方法，参数是aaa属性code @($productID) must be of type int



*/

initData();

$projectTester = new Project();
r($projectTester->testGetByShadowProduct(2))      && p('code') && e('project3');                         //获取ID为1的产品关联的项目
r($projectTester->testGetByShadowProduct(5))      && p('code') && e('0');                                //获取ID为4的产品关联的项目
r($projectTester->testGetByShadowProduct('aaa'))  && p('code') && e('($productID) must be of type int'); //获取ID为字符串的产品的项目

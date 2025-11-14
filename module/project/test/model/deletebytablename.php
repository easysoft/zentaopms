#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

function initData()
{
    $project = zenData('project')->loadYaml('project')->gen(6);

    $product = zenData('product');
    $product->id->range('1-3');
    $product->gen(3);

    $projectproduct = zenData('projectproduct')->loadYaml('projectproduct')->gen(5);

    $doclib = zenData('doclib');
    $doclib->id->range('1');
    $doclib->execution->range('2');
    $doclib->project->range('1');
    $doclib->gen(1);
}

/**

title=测试 projectModel->deleteByTableName();
timeout=0
cid=17811

*/

global $tester;
$tester->loadModel('project');

initData();

$execution  = $tester->loadModel('execution')->getPairs(1);
$executions = $tester->loadModel('execution')->getPairs(3);
$product    = $tester->loadModel('product')->getProductIDByProject(1);
$products   = $tester->loadModel('product')->getProductIDByProject(1, false);
$project1   = $tester->project->deleteByTableName('zt_doclib', 1);
$project2   = $tester->project->deleteByTableName('zt_project', array_keys($execution));
$project3   = $tester->project->deleteByTableName('zt_project', array_keys($executions));
$project4   = $tester->project->deleteByTableName('zt_product', $product);
$project5   = $tester->project->deleteByTableName('zt_product', $products);

r($project1) && p() && e('1'); #删除项目编号为1，同时删除项目下文档库
r($project2) && p() && e('1'); #删除项目编号为1，同时删除项目下的一个执行1-2
r($project3) && p() && e('1'); #删除项目编号为3，同时删除项目下的全部执行3-4 3-5
r($project4) && p() && e('1'); #删除项目编号为1，同时删除项目下关联的第一个产品
r($project5) && p() && e('1'); #删除项目编号为1，同时删除项目下关联的全部产品

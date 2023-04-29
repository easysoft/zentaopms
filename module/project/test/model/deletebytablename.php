#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

function initData()
{
    $project = zdTable('project');
    $project->id->range('1,3');
    $project->type->range("project");
    $project->status->range("doing,closed");
    $project->project->range('1,3');
    $project->name->prefix('项目')->range('1,3');
    $project->gen(2);

    $product = zdTable('product');
    $product->id->range('7-9');
    $product->gen(3);

    $product = zdTable('projectproduct')->config('1')->gen(3);

    $product = zdTable('doclib');
    $product->id->range('1');
    $product->execution->range('3');
    $product->gen(3);
}

/**

title=测试 projectModel->deleteByTableName();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('project');

initData();

die;
$project = new stdclass();
$project->parent     = 0;
$project->name       = '测试新增项目一';
$project->budget     = '';
$project->budgetUnit = 'CNY';
$project->begin      = '2022-02-07';
$project->end        = '2023-01-01';
$project->desc       = '测试项目描述';
$project->acl        = 'private';
$project->whitelist  = 'user1,user2,user3';
$project->PM         = 'admin';
$project->type       = 'project';
$project->model      = 'scrum';
$project->multiple   = 1;
$project->hasProduct = 1;

$postData = new stdclass();
$postData->rawdata = clone $project;
$postData->rawdata->uid      = '64dda2xc';
$postData->rawdata->delta    = 0;
$postData->rawdata->products = array(1);

$normalProject = clone $project;

$emptyNameProject = clone $project;
$emptyNameProject->name = '';

$emptyBeginProject = clone $project;
$emptyBeginProject->name  = '测试新增项目二';
$emptyBeginProject->begin = '';

$emptyEndProject = clone $project;
$emptyEndProject->end  = '';
$emptyEndProject->name = '测试新增项目三';

$beginGtEndProject = clone $project;
$beginGtEndProject->end  = '2021-01-10';
$beginGtEndProject->name = '测试新增项目四';

$productIdList = array(1, 2, 3, 4);
$tester->project->updateProductProgram(7, 1, $productIdList);

$products = $tester->loadModel('product')->getByIdList($productIdList);

r(count($products)) && p('')               && e('4');           // 查看被更新了项目集的产品数量
r($products)        && p('1:program,name') && e('1,正常产品1'); // 查看被更新了项目集的产品详情
r($products)        && p('2:program,name') && e('1,正常产品2'); // 查看被更新了项目集的产品详情
r($products)        && p('3:program,name') && e('1,正常产品3'); // 查看被更新了项目集的产品详情
r($products)        && p('4:program,name') && e('1,正常产品4'); // 查看被更新了项目集的产品详情

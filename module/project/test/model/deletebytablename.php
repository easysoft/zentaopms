#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

function initData()
{
    #scrum
    #project1   对应product1       对应execution1,3     对应文档库2
    #project3   对应product2,3     对应execution2,4     对应文档库3

    #waterfall
    #kanban/stage
    #无迭代项目

    $project = zdTable('project')->config('project')->gen(5);

    $product = zdTable('product');
    $product->id->range('1-3');
    $product->gen(3);

    $projectproduct = zdTable('projectproduct')->config('projectproduct')->gen(3);

    $doclib = zdTable('doclib');
    $doclib->id->range('1');
    $doclib->execution->range('2');
    $doclib->gen(1);
}

/**
title=测试 projectModel->deleteByTableName();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('project');

initData();

$executions = $tester->loadModel('execution')->getPairs(1);
$project1    = $tester->project->deleteByTableName('zt_doclib', 2);
$project2    = $tester->project->deleteByTableName('zt_product', 2);
$project3    = $tester->project->deleteByTableName('zt_project', 2);
$project4    = $tester->project->deleteByTableName('zt_project', $executions);

a($project1);die;
r($project1) && p('1:program,name') && e('1,正常产品1'); #删除项目编号为2，同时删除项目下文档库
r($project2) && p('2:program,name') && e('1,正常产品2'); #删除项目编号为2，同时删除项目下的一个产品
r($project3) && p('3:program,name') && e('1,正常产品3'); #删除项目编号为2，同时删除项目下的一个执行
r($project4) && p('4:program,name') && e('1,正常产品4'); #删除项目编号为1，同时删除项目下关联的全部产品

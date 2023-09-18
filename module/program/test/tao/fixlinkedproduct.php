#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';
su('admin');

zdTable('project')->config('program')->gen(30)->fixPath();
$product = zdTable('product');
$product->program->range('11-30');
$product->shadow->range('0,1,0{10}');
$product->gen(10);
$projectproduct = zdTable('projectproduct');
$projectproduct->product->range('2');
$projectproduct->project->range('11');
$projectproduct->gen(1);

/**

title=测试 programModel::fixLinkedProduct();
timeout=0
cid=1

*/

$programIdList = array(0, 1, 3);
$parentIdList  = array(0, 0, 4);
$oldParent     = array(0, 11, 0);
$oldPathList   = array(',0,', ',11,1', ',14,2');

$programTester = new programTest();

r($programTester->fixLinkedProductTest($programIdList[0], $parentIdList[0], $oldParent[0], $oldPathList[0])) && p() && e('0'); // 测试空数据
r($programTester->fixLinkedProductTest($programIdList[2], $parentIdList[2], $oldParent[2], $oldPathList[2])) && p() && e('4'); // 测试含有影子产品的项目修改。
r($programTester->fixLinkedProductTest($programIdList[1], $parentIdList[1], $oldParent[1], $oldPathList[1])) && p() && e('2'); // 测试OldParent参数等于0的项目修改。

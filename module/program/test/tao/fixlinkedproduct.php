#!/usr/bin/env php
<?php

/**

title=测试 programModel::fixLinkedProduct();
timeout=0
cid=17717

- 测试空数据 @0
- 测试含有影子产品的项目修改。 @4
- 测试含有影子产品的项目修改。 @4
- 测试OldParent参数等于0的项目修改。 @2
- 测试OldParent参数等于0的项目修改。 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

zenData('project')->loadYaml('program')->gen(30)->fixPath();
$product = zenData('product');
$product->program->range('11-30');
$product->shadow->range('0,1,0{10}');
$product->gen(10);
$projectproduct = zenData('projectproduct');
$projectproduct->product->range('2');
$projectproduct->project->range('11');
$projectproduct->gen(1);

$programTester = new programTest();
r($programTester->fixLinkedProductTest(0, 0, 0, ',0,'))     && p() && e('0'); // 测试空数据
r($programTester->fixLinkedProductTest(3, 1, 0, ',14,2'))   && p() && e('4'); // 测试含有影子产品的项目修改。
r($programTester->fixLinkedProductTest(3, 4, 0, ',14,2'))   && p() && e('4'); // 测试含有影子产品的项目修改。
r($programTester->fixLinkedProductTest(1, 0, 11, ',11,1'))  && p() && e('2'); // 测试OldParent参数等于0的项目修改。
r($programTester->fixLinkedProductTest(1, 0, 12, ',12,1'))  && p() && e('2'); // 测试OldParent参数等于0的项目修改。

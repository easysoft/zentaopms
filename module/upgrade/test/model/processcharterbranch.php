#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->processCharterBranch().
timeout=0
cid=19540

- 获取立项1的产品和分支
 - 属性product @1
 - 属性branch @1
- 获取立项2的产品和分支
 - 属性product @2
 - 属性branch @2
- 获取立项3的产品和分支
 - 属性product @3
 - 属性branch @3
- 获取立项4的产品和分支
 - 属性product @4
 - 属性branch @4
- 获取立项5的产品和分支
 - 属性product @5
 - 属性branch @5

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$charter = zenData('charter');
$charter->id->range('1-5');
$charter->name->range('立项{1-5}');
$charter->product->range('1-5');
$charter->roadmap->range('1-5');
$charter->plan->range('1-5');
$charter->gen(5);

$roadmap = zenData('roadmap');
$roadmap->id->range('1-5');
$roadmap->product->range('1-5');
$roadmap->branch->range('1-5');
$roadmap->name->range('路标{1-5}');
$roadmap->gen(5);

$plan = zenData('productplan');
$plan->id->range('1-5');
$plan->product->range('1-5');
$plan->branch->range('1-5');
$plan->title->range('计划{1-5}');
$plan->gen(5);

zenData('charterproduct')->gen(0);

$upgrade = new upgradeModelTest();
r($upgrade->processCharterBranchTest(1)) && p('product,branch') && e('1,1'); //获取立项1的产品和分支
r($upgrade->processCharterBranchTest(2)) && p('product,branch') && e('2,2'); //获取立项2的产品和分支
r($upgrade->processCharterBranchTest(3)) && p('product,branch') && e('3,3'); //获取立项3的产品和分支
r($upgrade->processCharterBranchTest(4)) && p('product,branch') && e('4,4'); //获取立项4的产品和分支
r($upgrade->processCharterBranchTest(5)) && p('product,branch') && e('5,5'); //获取立项5的产品和分支

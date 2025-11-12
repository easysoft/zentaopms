#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignBranchForEdit();
timeout=0
cid=0

- 执行testcaseTest模块的assignBranchForEditTest方法，参数是$case1, 1, 'execution' 属性1 @分支1
- 执行testcaseTest模块的assignBranchForEditTest方法，参数是$case2, 0, 'project' 属性2 @分支2 (已关闭)
- 执行testcaseTest模块的assignBranchForEditTest方法，参数是$case3, 1, 'execution' 属性10 @分支10 (已关闭)
- 执行testcaseTest模块的assignBranchForEditTest方法，参数是$case4, 1, 'execution' 属性999 @0
- 执行testcaseTest模块的assignBranchForEditTest方法，参数是$case5, 3, 'execution'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

zendata('case')->loadYaml('case_assignbranchforedit', false, 2)->gen(10);
zendata('branch')->loadYaml('branch_assignbranchforedit', false, 2)->gen(10);
zendata('product')->loadYaml('product_assignbranchforedit', false, 2)->gen(5);

su('admin');

$testcaseTest = new testcaseZenTest();

$case1 = new stdClass();
$case1->id = 1;
$case1->product = 1;
$case1->project = 1;
$case1->branch = 1;

$case2 = new stdClass();
$case2->id = 2;
$case2->product = 1;
$case2->project = 2;
$case2->branch = 2;

$case3 = new stdClass();
$case3->id = 3;
$case3->product = 1;
$case3->project = 1;
$case3->branch = 10;

$case4 = new stdClass();
$case4->id = 4;
$case4->product = 1;
$case4->project = 1;
$case4->branch = 999;

$case5 = new stdClass();
$case5->id = 5;
$case5->product = 2;
$case5->project = 3;
$case5->branch = 3;

r($testcaseTest->assignBranchForEditTest($case1, 1, 'execution')) && p('1') && e('分支1');
r($testcaseTest->assignBranchForEditTest($case2, 0, 'project')) && p('2') && e('分支2 (已关闭)');
r($testcaseTest->assignBranchForEditTest($case3, 1, 'execution')) && p('10') && e('分支10 (已关闭)');
r($testcaseTest->assignBranchForEditTest($case4, 1, 'execution')) && p('999') && e('0');
r(count($testcaseTest->assignBranchForEditTest($case5, 3, 'execution'))) && p() && e('1');
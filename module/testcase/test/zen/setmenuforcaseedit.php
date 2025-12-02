#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::setMenuForCaseEdit();
timeout=0
cid=0

- 执行testcaseTest模块的setMenuForCaseEditTest方法，参数是$case1, 0, 'project' 属性projectID @1
- 执行testcaseTest模块的setMenuForCaseEditTest方法，参数是$case2, 10, 'execution' 属性executionID @10
- 执行testcaseTest模块的setMenuForCaseEditTest方法，参数是$case3, 0, 'execution' 属性executionID @5
- 执行testcaseTest模块的setMenuForCaseEditTest方法，参数是$case4, 0, 'qa' 属性tab @qa
- 执行testcaseTest模块的setMenuForCaseEditTest方法，参数是$case5, 20, 'execution' 属性executionID @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

zendata('case')->gen(10);
zendata('project')->gen(10);
zendata('product')->gen(10);

su('admin');

$testcaseTest = new testcaseZenTest();

$case1 = new stdClass();
$case1->id = 1;
$case1->product = 1;
$case1->project = 1;
$case1->execution = 1;
$case1->branch = 0;

$case2 = new stdClass();
$case2->id = 2;
$case2->product = 2;
$case2->project = 2;
$case2->execution = 5;
$case2->branch = 0;

$case3 = new stdClass();
$case3->id = 3;
$case3->product = 3;
$case3->project = 3;
$case3->execution = 5;
$case3->branch = 0;

$case4 = new stdClass();
$case4->id = 4;
$case4->product = 4;
$case4->project = 4;
$case4->execution = 4;
$case4->branch = 1;

$case5 = new stdClass();
$case5->id = 5;
$case5->product = 5;
$case5->project = 5;
$case5->execution = 15;
$case5->branch = 0;

r($testcaseTest->setMenuForCaseEditTest($case1, 0, 'project')) && p('projectID') && e('1');
r($testcaseTest->setMenuForCaseEditTest($case2, 10, 'execution')) && p('executionID') && e('10');
r($testcaseTest->setMenuForCaseEditTest($case3, 0, 'execution')) && p('executionID') && e('5');
r($testcaseTest->setMenuForCaseEditTest($case4, 0, 'qa')) && p('tab') && e('qa');
r($testcaseTest->setMenuForCaseEditTest($case5, 20, 'execution')) && p('executionID') && e('20');
#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::importCases();
timeout=0
cid=0

- 步骤1:导入空数组 @0
- 步骤2:导入单个用例对象 @1
- 步骤3:导入多个用例对象 @2
- 步骤4:导入已有ID的用例第0条的id属性 @1
- 步骤5:导入混合用例(有ID和无ID) @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

zenData('case')->gen(10);
zenData('product')->gen(5);
zenData('module')->gen(10);
zenData('action')->gen(0);
zenData('casestep')->gen(0);
zenData('casespec')->gen(0);
zenData('file')->gen(0);

su('admin');

$testcaseTest = new testcaseZenTest();

$case1 = (object)array(
    'product' => 1,
    'branch' => 0,
    'module' => 0,
    'title' => 'Test Case 1',
    'type' => 'feature',
    'pri' => 3,
    'status' => 'normal',
    'stage' => 'unittest',
    'story' => 0,
    'frequency' => 1,
    'order' => 0,
    'openedBy' => 'admin',
    'openedDate' => '2024-01-01 00:00:00',
    'version' => 1,
    'steps' => array('step1'),
    'expects' => array('expect1'),
    'stepType' => array('step'),
    'precondition' => '',
    'keywords' => ''
);

$case2 = (object)array(
    'id' => 1,
    'product' => 1,
    'branch' => 0,
    'module' => 0,
    'title' => 'Test Case 2',
    'type' => 'feature',
    'pri' => 2,
    'status' => 'normal',
    'stage' => 'unittest',
    'story' => 0,
    'frequency' => 1,
    'order' => 0,
    'version' => 1,
    'steps' => array('step2'),
    'expects' => array('expect2'),
    'stepType' => array('step'),
    'precondition' => '',
    'keywords' => ''
);

$case3 = (object)array(
    'product' => 1,
    'branch' => 0,
    'module' => 0,
    'title' => 'Test Case 3',
    'type' => 'feature',
    'pri' => 1,
    'status' => 'normal',
    'stage' => 'unittest',
    'story' => 0,
    'frequency' => 1,
    'order' => 0,
    'openedBy' => 'admin',
    'openedDate' => '2024-01-01 00:00:00',
    'version' => 1,
    'steps' => array('step3'),
    'expects' => array('expect3'),
    'stepType' => array('step'),
    'precondition' => '',
    'keywords' => ''
);

r(count($testcaseTest->importCasesTest(array()))) && p() && e('0'); // 步骤1:导入空数组
r(count($testcaseTest->importCasesTest(array($case1)))) && p() && e('1'); // 步骤2:导入单个用例对象
r(count($testcaseTest->importCasesTest(array($case1, $case3)))) && p() && e('2'); // 步骤3:导入多个用例对象
r($testcaseTest->importCasesTest(array($case2))) && p('0:id') && e('1'); // 步骤4:导入已有ID的用例
r(count($testcaseTest->importCasesTest(array($case1, $case2, $case3)))) && p() && e('3'); // 步骤5:导入混合用例(有ID和无ID)
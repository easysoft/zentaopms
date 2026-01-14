#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('case')->gen(10);
zenData('story')->gen(10);

/**

title=测试 testcaseModel->review();
timeout=0
cid=19019

- 测试评审用例 1
 - 属性status @normal
 - 属性reviewedBy @test1
 - 属性reviewedDate @2023-08-29
- 测试评审用例 1
 - 属性status @wait
 - 属性reviewedBy @test2
 - 属性reviewedDate @2023-08-29

*/

$caseIDList = array(1, 2);

$case1 = new stdclass();
$case1->result       = 'pass';
$case1->status       = 'normal';
$case1->reviewedBy   = 'test1';
$case1->reviewedDate = '2023-08-29';
$case1->comment      = '';

$case2 = new stdclass();
$case2->result       = 'clarify';
$case2->status       = 'wait';
$case2->reviewedBy   = 'test2';
$case2->reviewedDate = '2023-08-29';
$case2->comment      = '';

$testcase = new testcaseModelTest();

r($testcase->reviewTest($caseIDList[0], $case1)) && p('status,reviewedBy,reviewedDate') && e('normal,test1,2023-08-29'); // 测试评审用例 1
r($testcase->reviewTest($caseIDList[1], $case2)) && p('status,reviewedBy,reviewedDate') && e('wait,test2,2023-08-29');   // 测试评审用例 1
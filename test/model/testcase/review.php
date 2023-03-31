#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->review();
cid=1
pid=1

测试评审用例 1 >> status,wait,normal
测试评审用例 2 >> reviewedBy,admin,test2
测试评审用例 3 >> status,wait,normal
测试评审用例 4 >> reviewedBy,admin,test4
测试评审用例 5 >> status,wait,normal

*/

$caseIDList = array(1, 5, 9, 13, 17);

$case1 = new stdclass();
$case1->result = 'pass';
$case1->status = 'normal';
$case1->reviewedBy = array('test1');

$case2 = new stdclass();
$case2->result = 'clarify';
$case2->status = 'wait';
$case2->reviewedBy = array('test2');

$case3 = new stdclass();
$case3->result = 'pass';
$case3->status = 'normal';
$case3->reviewedBy = array('test3');

$case4 = new stdclass();
$case4->result = 'clarify';
$case4->status = 'wait';
$case4->reviewedBy = array('test4');

$case5 = new stdclass();
$case5->result = 'pass';
$case5->status = 'normal';
$case5->reviewedBy = array('test5');

$testcase = new testcaseTest();

r($testcase->reviewTest($caseIDList[0], $case1)) && p('0:field,old,new') && e('status,wait,normal');     // 测试评审用例 1
r($testcase->reviewTest($caseIDList[1], $case2)) && p('0:field,old,new') && e('reviewedBy,admin,test2'); // 测试评审用例 2
r($testcase->reviewTest($caseIDList[2], $case3)) && p('0:field,old,new') && e('status,wait,normal');     // 测试评审用例 3
r($testcase->reviewTest($caseIDList[3], $case4)) && p('0:field,old,new') && e('reviewedBy,admin,test4'); // 测试评审用例 4
r($testcase->reviewTest($caseIDList[4], $case5)) && p('0:field,old,new') && e('status,wait,normal');     // 测试评审用例 5

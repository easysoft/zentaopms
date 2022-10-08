#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->appendData();
cid=1
pid=1

获取case 1 2 可以加入的数据 >> 0,1,0,1;2,1,1,1
获取case 1 2 可以加入的数据 >> 0,1,0,1;0,1,1,1
获取case 1 2 可以加入的数据 >> 0,1,0,1;2,1,1,1
获取case 1 2 可以加入的数据 >> 0,1,0,1;0,1,1,1
获取case 1 2 可以加入的数据 >> 0,1,0,1;2,1,1,1

*/
$case1 = new stdclass();
$case1->id   = 1;
$case1->case = 1;

$case2 = new stdclass();
$case2->id   = 2;
$case2->case = 2;

$case3 = new stdclass();
$case3->id   = 3;
$case3->case = 3;

$case4 = new stdclass();
$case4->id   = 4;
$case4->case = 4;

$case5 = new stdclass();
$case5->id   = 5;
$case5->case = 5;

$case6 = new stdclass();
$case6->id   = 6;
$case6->case = 6;

$case7 = new stdclass();
$case7->id   = 7;
$case7->case = 7;

$case8 = new stdclass();
$case8->id   = 8;
$case8->case = 8;

$case9 = new stdclass();
$case9->id   = 9;
$case9->case = 9;

$case10 = new stdclass();
$case10->id   = 10;
$case10->case = 10;

$cases = array(array(1 => $case1, 2 => $case2), array(3 => $case3, 4 => $case4), array(5 => $case5, 6 => $case6), array(7 => $case7, 8 => $case8), array(9 => $case9, 10 => $case10));

$type = 'run';

$testcase = new testcaseTest();

r($testcase->appendDataTest($cases[0])) && p('1:bugs,results,caseFails,stepNumber;2:bugs,results,caseFails,stepNumber')  && e('0,1,0,1;2,1,1,1'); // 获取case 1 2 可以加入的数据
r($testcase->appendDataTest($cases[1])) && p('3:bugs,results,caseFails,stepNumber;4:bugs,results,caseFails,stepNumber')  && e('0,1,0,1;0,1,1,1'); // 获取case 1 2 可以加入的数据
r($testcase->appendDataTest($cases[2])) && p('5:bugs,results,caseFails,stepNumber;6:bugs,results,caseFails,stepNumber')  && e('0,1,0,1;2,1,1,1'); // 获取case 1 2 可以加入的数据
r($testcase->appendDataTest($cases[3])) && p('7:bugs,results,caseFails,stepNumber;8:bugs,results,caseFails,stepNumber')  && e('0,1,0,1;0,1,1,1'); // 获取case 1 2 可以加入的数据
r($testcase->appendDataTest($cases[4])) && p('9:bugs,results,caseFails,stepNumber;10:bugs,results,caseFails,stepNumber') && e('0,1,0,1;2,1,1,1'); // 获取case 1 2 可以加入的数据
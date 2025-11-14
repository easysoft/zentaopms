#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->gen('100');
zenData('casestep')->gen('100');
zenData('bug')->loadYaml('casebug')->gen('50');
zenData('testresult')->gen('50');
zenData('user')->gen('1');
zenData('testrun')->gen('10');

su('admin');

/**

title=测试 testcaseModel->appendData();
timeout=0
cid=18952

- 获取case 1 2 可以加入的数据
 - 第1条的bugs属性 @6
 - 第1条的results属性 @1
 - 第1条的caseFails属性 @0
 - 第1条的stepNumber属性 @1
 - 第2条的bugs属性 @6
 - 第2条的results属性 @1
 - 第2条的caseFails属性 @1
 - 第2条的stepNumber属性 @1
- 获取case 3 4 可以加入的数据
 - 第3条的bugs属性 @6
 - 第3条的results属性 @1
 - 第3条的caseFails属性 @0
 - 第3条的stepNumber属性 @1
 - 第4条的bugs属性 @0
 - 第4条的results属性 @1
 - 第4条的caseFails属性 @1
 - 第4条的stepNumber属性 @1
- 获取case 5 6 可以加入的数据
 - 第5条的bugs属性 @0
 - 第5条的results属性 @1
 - 第5条的caseFails属性 @0
 - 第5条的stepNumber属性 @1
 - 第6条的bugs属性 @6
 - 第6条的results属性 @1
 - 第6条的caseFails属性 @1
 - 第6条的stepNumber属性 @1
- 获取case 7 8 可以加入的数据
 - 第7条的bugs属性 @6
 - 第7条的results属性 @1
 - 第7条的caseFails属性 @0
 - 第7条的stepNumber属性 @1
 - 第8条的bugs属性 @5
 - 第8条的results属性 @1
 - 第8条的caseFails属性 @1
 - 第8条的stepNumber属性 @1
- 获取case 9 10 可以加入的数据
 - 第9条的bugs属性 @5
 - 第9条的results属性 @1
 - 第9条的caseFails属性 @0
 - 第9条的stepNumber属性 @1
 - 第10条的bugs属性 @0
 - 第10条的results属性 @1
 - 第10条的caseFails属性 @1
 - 第10条的stepNumber属性 @1
- 获取run 1 2 可以加入的数据
 - 第1条的bugs属性 @0
 - 第1条的results属性 @1
 - 第1条的caseFails属性 @0
 - 第1条的stepNumber属性 @1
 - 第2条的bugs属性 @0
 - 第2条的results属性 @1
 - 第2条的caseFails属性 @1
 - 第2条的stepNumber属性 @1
- 获取run 3 4 可以加入的数据
 - 第3条的bugs属性 @2
 - 第3条的results属性 @1
 - 第3条的caseFails属性 @0
 - 第3条的stepNumber属性 @1
 - 第4条的bugs属性 @0
 - 第4条的results属性 @1
 - 第4条的caseFails属性 @1
 - 第4条的stepNumber属性 @1
- 获取run 5 6 可以加入的数据
 - 第5条的bugs属性 @0
 - 第5条的results属性 @1
 - 第5条的caseFails属性 @0
 - 第5条的stepNumber属性 @1
 - 第6条的bugs属性 @1
 - 第6条的results属性 @1
 - 第6条的caseFails属性 @1
 - 第6条的stepNumber属性 @1
- 获取run 7 8 可以加入的数据
 - 第7条的bugs属性 @2
 - 第7条的results属性 @1
 - 第7条的caseFails属性 @0
 - 第7条的stepNumber属性 @1
 - 第8条的bugs属性 @1
 - 第8条的results属性 @1
 - 第8条的caseFails属性 @1
 - 第8条的stepNumber属性 @1
- 获取run 9 10 可以加入的数据
 - 第9条的bugs属性 @0
 - 第9条的results属性 @1
 - 第9条的caseFails属性 @0
 - 第9条的stepNumber属性 @1
 - 第10条的bugs属性 @0
 - 第10条的results属性 @1
 - 第10条的caseFails属性 @1
 - 第10条的stepNumber属性 @1
- 获取case 1 2 case id 1 3 5 7 可以加入的数据
 - 第1条的bugs属性 @6
 - 第1条的results属性 @1
 - 第1条的caseFails属性 @0
 - 第1条的stepNumber属性 @1
 - 第2条的bugs属性 @0
 - 第2条的results属性 @0
 - 第2条的caseFails属性 @0
 - 第2条的stepNumber属性 @0
- 获取case 3 4 case id 1 3 5 7 可以加入的数据
 - 第3条的bugs属性 @6
 - 第3条的results属性 @1
 - 第3条的caseFails属性 @0
 - 第3条的stepNumber属性 @1
 - 第4条的bugs属性 @0
 - 第4条的results属性 @0
 - 第4条的caseFails属性 @0
 - 第4条的stepNumber属性 @0
- 获取case 5 6 case id 1 3 5 7 可以加入的数据
 - 第5条的bugs属性 @0
 - 第5条的results属性 @1
 - 第5条的caseFails属性 @0
 - 第5条的stepNumber属性 @1
 - 第6条的bugs属性 @0
 - 第6条的results属性 @0
 - 第6条的caseFails属性 @0
 - 第6条的stepNumber属性 @0
- 获取case 7 8 case id 1 3 5 7 可以加入的数据
 - 第7条的bugs属性 @6
 - 第7条的results属性 @1
 - 第7条的caseFails属性 @0
 - 第7条的stepNumber属性 @1
 - 第8条的bugs属性 @0
 - 第8条的results属性 @0
 - 第8条的caseFails属性 @0
 - 第8条的stepNumber属性 @0
- 获取case 9 10 case id 1 3 5 7 可以加入的数据
 - 第9条的bugs属性 @0
 - 第9条的results属性 @0
 - 第9条的caseFails属性 @0
 - 第9条的stepNumber属性 @0
 - 第10条的bugs属性 @0
 - 第10条的results属性 @0
 - 第10条的caseFails属性 @0
 - 第10条的stepNumber属性 @0
- 获取run 1 2 run id 1 3 5 7 可以加入的数据
 - 第1条的bugs属性 @3
 - 第1条的results属性 @1
 - 第1条的caseFails属性 @0
 - 第1条的stepNumber属性 @1
 - 第2条的bugs属性 @3
 - 第2条的results属性 @0
 - 第2条的caseFails属性 @0
 - 第2条的stepNumber属性 @0
- 获取run 3 4 run id 1 3 5 7 可以加入的数据
 - 第3条的bugs属性 @3
 - 第3条的results属性 @1
 - 第3条的caseFails属性 @0
 - 第3条的stepNumber属性 @1
 - 第4条的bugs属性 @0
 - 第4条的results属性 @0
 - 第4条的caseFails属性 @0
 - 第4条的stepNumber属性 @0
- 获取run 5 6 run id 1 3 5 7 可以加入的数据
 - 第5条的bugs属性 @0
 - 第5条的results属性 @1
 - 第5条的caseFails属性 @0
 - 第5条的stepNumber属性 @1
 - 第6条的bugs属性 @3
 - 第6条的results属性 @0
 - 第6条的caseFails属性 @0
 - 第6条的stepNumber属性 @0
- 获取run 7 8 run id 1 3 5 7 可以加入的数据
 - 第7条的bugs属性 @3
 - 第7条的results属性 @1
 - 第7条的caseFails属性 @0
 - 第7条的stepNumber属性 @1
 - 第8条的bugs属性 @3
 - 第8条的results属性 @0
 - 第8条的caseFails属性 @0
 - 第8条的stepNumber属性 @0
- 获取run 9 10 run id 1 3 5 7 可以加入的数据
 - 第9条的bugs属性 @3
 - 第9条的results属性 @0
 - 第9条的caseFails属性 @0
 - 第9条的stepNumber属性 @0
 - 第10条的bugs属性 @0
 - 第10条的results属性 @0
 - 第10条的caseFails属性 @0
 - 第10条的stepNumber属性 @0

*/
$case1 = new stdclass();
$case1->id    = 1;
$case1->case  = 1;
$case1->story = 0;

$case2 = new stdclass();
$case2->id    = 2;
$case2->case  = 2;
$case2->story = 0;

$case3 = new stdclass();
$case3->id    = 3;
$case3->case  = 3;
$case3->story = 0;

$case4 = new stdclass();
$case4->id    = 4;
$case4->case  = 4;
$case4->story = 0;

$case5 = new stdclass();
$case5->id    = 5;
$case5->case  = 5;
$case5->story = 0;

$case6 = new stdclass();
$case6->id    = 6;
$case6->case  = 6;
$case6->story = 0;

$case7 = new stdclass();
$case7->id    = 7;
$case7->case  = 7;
$case7->story = 0;

$case8 = new stdclass();
$case8->id    = 8;
$case8->case  = 8;
$case8->story = 0;

$case9 = new stdclass();
$case9->id    = 9;
$case9->case  = 9;
$case9->story = 0;

$case10 = new stdclass();
$case10->id    = 10;
$case10->case  = 10;
$case10->story = 0;

$cases = array(array(1 => $case1, 2 => $case2), array(3 => $case3, 4 => $case4), array(5 => $case5, 6 => $case6), array(7 => $case7, 8 => $case8), array(9 => $case9, 10 => $case10));
$type  = array('case', 'run');
$caseIdList = array(1, 3, 5, 7);

$testcase = new testcaseTest();

r($testcase->appendDataTest($cases[0], $type[0])) && p('1:bugs,results,caseFails,stepNumber;2:bugs,results,caseFails,stepNumber')  && e('6,1,0,1;6,1,1,1'); // 获取case 1 2 可以加入的数据
r($testcase->appendDataTest($cases[1], $type[0])) && p('3:bugs,results,caseFails,stepNumber;4:bugs,results,caseFails,stepNumber')  && e('6,1,0,1;0,1,1,1'); // 获取case 3 4 可以加入的数据
r($testcase->appendDataTest($cases[2], $type[0])) && p('5:bugs,results,caseFails,stepNumber;6:bugs,results,caseFails,stepNumber')  && e('0,1,0,1;6,1,1,1'); // 获取case 5 6 可以加入的数据
r($testcase->appendDataTest($cases[3], $type[0])) && p('7:bugs,results,caseFails,stepNumber;8:bugs,results,caseFails,stepNumber')  && e('6,1,0,1;5,1,1,1'); // 获取case 7 8 可以加入的数据
r($testcase->appendDataTest($cases[4], $type[0])) && p('9:bugs,results,caseFails,stepNumber;10:bugs,results,caseFails,stepNumber') && e('5,1,0,1;0,1,1,1'); // 获取case 9 10 可以加入的数据

r($testcase->appendDataTest($cases[0], $type[1])) && p('1:bugs,results,caseFails,stepNumber;2:bugs,results,caseFails,stepNumber')  && e('0,1,0,1;0,1,1,1'); // 获取run 1 2 可以加入的数据
r($testcase->appendDataTest($cases[1], $type[1])) && p('3:bugs,results,caseFails,stepNumber;4:bugs,results,caseFails,stepNumber')  && e('2,1,0,1;0,1,1,1'); // 获取run 3 4 可以加入的数据
r($testcase->appendDataTest($cases[2], $type[1])) && p('5:bugs,results,caseFails,stepNumber;6:bugs,results,caseFails,stepNumber')  && e('0,1,0,1;1,1,1,1'); // 获取run 5 6 可以加入的数据
r($testcase->appendDataTest($cases[3], $type[1])) && p('7:bugs,results,caseFails,stepNumber;8:bugs,results,caseFails,stepNumber')  && e('2,1,0,1;1,1,1,1'); // 获取run 7 8 可以加入的数据
r($testcase->appendDataTest($cases[4], $type[1])) && p('9:bugs,results,caseFails,stepNumber;10:bugs,results,caseFails,stepNumber') && e('0,1,0,1;0,1,1,1'); // 获取run 9 10 可以加入的数据

r($testcase->appendDataTest($cases[0], $type[0], $caseIdList)) && p('1:bugs,results,caseFails,stepNumber;2:bugs,results,caseFails,stepNumber')  && e('6,1,0,1;0,0,0,0'); // 获取case 1 2 case id 1 3 5 7 可以加入的数据
r($testcase->appendDataTest($cases[1], $type[0], $caseIdList)) && p('3:bugs,results,caseFails,stepNumber;4:bugs,results,caseFails,stepNumber')  && e('6,1,0,1;0,0,0,0'); // 获取case 3 4 case id 1 3 5 7 可以加入的数据
r($testcase->appendDataTest($cases[2], $type[0], $caseIdList)) && p('5:bugs,results,caseFails,stepNumber;6:bugs,results,caseFails,stepNumber')  && e('0,1,0,1;0,0,0,0'); // 获取case 5 6 case id 1 3 5 7 可以加入的数据
r($testcase->appendDataTest($cases[3], $type[0], $caseIdList)) && p('7:bugs,results,caseFails,stepNumber;8:bugs,results,caseFails,stepNumber')  && e('6,1,0,1;0,0,0,0'); // 获取case 7 8 case id 1 3 5 7 可以加入的数据
r($testcase->appendDataTest($cases[4], $type[0], $caseIdList)) && p('9:bugs,results,caseFails,stepNumber;10:bugs,results,caseFails,stepNumber') && e('0,0,0,0;0,0,0,0'); // 获取case 9 10 case id 1 3 5 7 可以加入的数据

r($testcase->appendDataTest($cases[0], $type[1], $caseIdList)) && p('1:bugs,results,caseFails,stepNumber;2:bugs,results,caseFails,stepNumber')  && e('3,1,0,1;3,0,0,0'); // 获取run 1 2 run id 1 3 5 7 可以加入的数据
r($testcase->appendDataTest($cases[1], $type[1], $caseIdList)) && p('3:bugs,results,caseFails,stepNumber;4:bugs,results,caseFails,stepNumber')  && e('3,1,0,1;0,0,0,0'); // 获取run 3 4 run id 1 3 5 7 可以加入的数据
r($testcase->appendDataTest($cases[2], $type[1], $caseIdList)) && p('5:bugs,results,caseFails,stepNumber;6:bugs,results,caseFails,stepNumber')  && e('0,1,0,1;3,0,0,0'); // 获取run 5 6 run id 1 3 5 7 可以加入的数据
r($testcase->appendDataTest($cases[3], $type[1], $caseIdList)) && p('7:bugs,results,caseFails,stepNumber;8:bugs,results,caseFails,stepNumber')  && e('3,1,0,1;3,0,0,0'); // 获取run 7 8 run id 1 3 5 7 可以加入的数据
r($testcase->appendDataTest($cases[4], $type[1], $caseIdList)) && p('9:bugs,results,caseFails,stepNumber;10:bugs,results,caseFails,stepNumber') && e('3,0,0,0;0,0,0,0'); // 获取run 9 10 run id 1 3 5 7 可以加入的数据

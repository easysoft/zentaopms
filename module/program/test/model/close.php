#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=测试 programModel::close();
timeout=0
cid=17677

- 测试实际完成时间大于今日的情况第realEnd条的0属性 @~f:『实际完成日期』应当不大于~
- 测试实际完成时间小于实际开始时间的情况第realEnd条的0属性 @『实际完成日期』应当不小于『2023-09-11』。
- 测试关闭一个项目集
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @close
- 测试关闭一个项目集
 - 第1条的field属性 @closedDate
 - 第1条的old属性 @~~
- 测试关闭一个项目集
 - 第2条的field属性 @closedBy
 - 第2条的old属性 @~~
 - 第2条的new属性 @admin

*/

zenData('user')->gen(5);
su('admin');

$program = zenData('project')->loadYaml('program');
$program->realBegan->range('`2023-09-11`');
$program->gen(10);

$programID = 1;
$maxDate    = array('realEnd' => date('Y-m-d', strtotime('+1 day')));
$minDate    = array('realEnd' => '2023-09-10');
$normalDate = array('realEnd' => '2023-09-12');

$programTester = new programModelTest();

$maxDateResult = $programTester->closeTest($programID, $maxDate);
$minDateResult = $programTester->closeTest($programID, $minDate);
$normalResult = $programTester->closeTest($programID, $normalDate);

r($maxDateResult) && p('realEnd:0')       && e('~f:『实际完成日期』应当不大于~');             // 测试实际完成时间大于今日的情况
r($minDateResult) && p('realEnd:0')       && e('『实际完成日期』应当不小于『2023-09-11』。'); // 测试实际完成时间小于实际开始时间的情况
r($normalResult)  && p('0:field,old,new') && e('status,doing,close');                         // 测试关闭一个项目集
r($normalResult)  && p('1:field,old')     && e('closedDate,~~');                              // 测试关闭一个项目集
r($normalResult)  && p('2:field,old,new') && e('closedBy,~~,admin');                          // 测试关闭一个项目集

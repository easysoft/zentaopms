#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';
zdTable('user')->gen(5);
su('admin');

$program = zdTable('project')->config('program');
$program->realBegan->range('`2023-09-11`');
$program->gen(10);

/**

title=测试 programModel::close();
timeout=0
cid=1

*/

$programID = 1;
$maxDate    = array('realEnd' => date('Y-m-d', strtotime('+1 day')));
$minDate    = array('realEnd' => '2023-09-10');
$normalDate = array('realEnd' => '2023-09-12');

$programTester = new programTest();
r($programTester->closeTest($programID, $maxDate))    && p('realEnd:0')       && e('~f:『实际完成日期』应当不大于~');             // 测试实际完成时间大于今日的情况
r($programTester->closeTest($programID, $minDate))    && p('realEnd:0')       && e('『实际完成日期』应当不小于『2023-09-11』。'); // 测试实际完成时间小于实际开始时间的情况
r($programTester->closeTest($programID, $normalDate)) && p('0:field,old,new') && e('status,doing,close');                         // 测试关闭一个项目集

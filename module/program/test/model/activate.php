#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=测试 programModel::activate();
timeout=0
cid=17670

- 测试实际完成时间小于实际开始时间的情况属性end @『计划完成』应当不小于『计划开始』。
- 测试激活一个项目集
 - 属性field @status
 - 属性old @closed
 - 属性new @doing
- 测试激活一个项目集
 - 属性field @realEnd
 - 属性old @2023-09-11
 - 属性new @~~
- 测试激活一个项目集
 - 属性field @begin
 - 属性new @2023-09-12
- 测试激活一个项目集
 - 属性field @end
 - 属性new @2024-09-12
- 测试重复激活一个项目集 @0

*/

zenData('user')->gen(5);
su('admin');

$program = zenData('project')->loadYaml('program');
$program->realEnd->range('`2023-09-11`');
$program->status->range('closed');
$program->gen(10);

$programID  = 1;
$errorDate  = array('begin' => '2023-09-15', 'end' => '2023-09-10');
$normalDate = array('begin' => '2023-09-12', 'end' => '2024-09-12');

$programTester = new programModelTest();

$result = $programTester->activateTest($programID, $normalDate);
r($programTester->activateTest($programID, $errorDate))  && p('end')           && e('『计划完成』应当不小于『计划开始』。'); // 测试实际完成时间小于实际开始时间的情况
r($result[0])                                            && p('field,old,new') && e('status,closed,doing');                  // 测试激活一个项目集
r($result[1])                                            && p('field,old,new') && e('realEnd,2023-09-11,~~');                // 测试激活一个项目集
r($result[2])                                            && p('field,new')     && e('begin,2023-09-12');                     // 测试激活一个项目集
r($result[3])                                            && p('field,new')     && e('end,2024-09-12');                       // 测试激活一个项目集
r($programTester->activateTest($programID, $normalDate)) && p()                && e('0');                                    // 测试重复激活一个项目集

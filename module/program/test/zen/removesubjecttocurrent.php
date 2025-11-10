#!/usr/bin/env php
<?php

/**

title=测试 programZen::removeSubjectToCurrent();
timeout=0
cid=0

- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array  @4
- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array  @6
- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array  @0
- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array  @4
- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array  @3
- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array  @9
- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

zenData('project')->loadYaml('removesubjecttocurrent/project', false, 2)->gen(20);

su('admin');

$programTest = new programTest();

r(count($programTest->removeSubjectToCurrentTest(array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5), 1))) && p() && e('4');
r(count($programTest->removeSubjectToCurrentTest(array(1 => 1, 2 => 2, 3 => 3, 7 => 7, 8 => 8, 9 => 9, 10 => 10), 2))) && p() && e('6');
r(count($programTest->removeSubjectToCurrentTest(array(), 1))) && p() && e('0');
r(count($programTest->removeSubjectToCurrentTest(array(1 => 1, 2 => 2, 3 => 3, 11 => 11, 12 => 12), 11))) && p() && e('4');
r(count($programTest->removeSubjectToCurrentTest(array(1 => 1, 2 => 2, 3 => 3), 999))) && p() && e('3');
r(count($programTest->removeSubjectToCurrentTest(array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10), 1))) && p() && e('9');
r(count($programTest->removeSubjectToCurrentTest(array(3 => 3, 9 => 9, 10 => 10), 3))) && p() && e('2');
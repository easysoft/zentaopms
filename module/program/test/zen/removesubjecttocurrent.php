#!/usr/bin/env php
<?php

/**

title=测试 programZen::removeSubjectToCurrent();
timeout=0
cid=0

- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array
 - 属性2 @子项目集A
 - 属性7 @独立项目集
- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array
 - 属性8 @独立项目集2
 - 属性9 @独立项目集3
- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array  @0
- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array
 - 属性1 @顶级项目集
 - 属性7 @独立项目集1
 - 属性8 @独立项目集2
- 执行programTest模块的removeSubjectToCurrentTest方法，参数是array
 - 属性1 @顶级项目集
 - 属性10 @其他项目集

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

zendata('project')->loadYaml('project_removesubjecttocurrent', false, 2)->gen(15);

su('admin');

$programTest = new programTest();

r($programTest->removeSubjectToCurrentTest(array(1 => '顶级项目集', 2 => '子项目集A', 11 => '子项目集11', 7 => '独立项目集'), 1)) && p('2,7') && e('子项目集A,独立项目集');
r($programTest->removeSubjectToCurrentTest(array(7 => '独立项目集1', 8 => '独立项目集2', 9 => '独立项目集3'), 7)) && p('8,9') && e('独立项目集2,独立项目集3');
r($programTest->removeSubjectToCurrentTest(array(), 1)) && p() && e('0');
r($programTest->removeSubjectToCurrentTest(array(1 => '顶级项目集', 7 => '独立项目集1', 8 => '独立项目集2'), 999)) && p('1,7,8') && e('顶级项目集,独立项目集1,独立项目集2');
r($programTest->removeSubjectToCurrentTest(array(1 => '顶级项目集', 2 => '子项目集A', 12 => '项目集12', 10 => '其他项目集'), 2)) && p('1,10') && e('顶级项目集,其他项目集');
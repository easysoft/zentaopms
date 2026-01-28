#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getPrograms();
timeout=0
cid=19454

- 执行tutorialTest模块的getProgramsTest方法 第1条的name属性 @Test program
- 执行tutorialTest模块的getProgramsTest方法 第1条的id属性 @1
- 执行tutorialTest模块的getProgramsTest方法 第1条的type属性 @program
- 执行tutorialTest模块的getProgramsTest方法 第1条的parent属性 @0
- 执行tutorialTest模块的getProgramsTest方法 第1条的grade属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tutorialTest = new tutorialModelTest();

r($tutorialTest->getProgramsTest()) && p('1:name') && e('Test program');
r($tutorialTest->getProgramsTest()) && p('1:id') && e('1');
r($tutorialTest->getProgramsTest()) && p('1:type') && e('program');
r($tutorialTest->getProgramsTest()) && p('1:parent') && e('0');
r($tutorialTest->getProgramsTest()) && p('1:grade') && e('1');
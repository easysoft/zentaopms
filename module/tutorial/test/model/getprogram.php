#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getProgram();
timeout=0
cid=19452

- 执行tutorialTest模块的getProgramTest方法 属性id @1
- 执行tutorialTest模块的getProgramTest方法 属性name @Test program
- 执行tutorialTest模块的getProgramTest方法 属性type @program
- 执行tutorialTest模块的getProgramTest方法 属性parent @0
- 执行tutorialTest模块的getProgramTest方法 属性grade @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tutorialTest = new tutorialModelTest();

r($tutorialTest->getProgramTest()) && p('id') && e('1');
r($tutorialTest->getProgramTest()) && p('name') && e('Test program');
r($tutorialTest->getProgramTest()) && p('type') && e('program');
r($tutorialTest->getProgramTest()) && p('parent') && e('0');
r($tutorialTest->getProgramTest()) && p('grade') && e('1');
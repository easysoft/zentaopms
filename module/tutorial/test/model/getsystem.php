#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getSystem();
timeout=0
cid=19481

- 执行tutorialTest模块的getSystemTest方法 属性id @1
- 执行tutorialTest模块的getSystemTest方法 属性name @Test App
- 执行tutorialTest模块的getSystemTest方法 属性status @active
- 执行tutorialTest模块的getSystemTest方法 属性product @1
- 执行tutorialTest模块的getSystemTest方法 属性integrated @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tutorialTest = new tutorialModelTest();

r($tutorialTest->getSystemTest()) && p('id') && e('1');
r($tutorialTest->getSystemTest()) && p('name') && e('Test App');
r($tutorialTest->getSystemTest()) && p('status') && e('active');
r($tutorialTest->getSystemTest()) && p('product') && e('1');
r($tutorialTest->getSystemTest()) && p('integrated') && e('0');
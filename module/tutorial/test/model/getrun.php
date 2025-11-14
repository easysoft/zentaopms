#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getRun();
timeout=0
cid=19471

- 执行tutorialTest模块的getRunTest方法 属性id @1
- 执行tutorialTest模块的getRunTest方法 属性status @normal
- 执行tutorialTest模块的getRunTest方法 属性task @1
- 执行tutorialTest模块的getRunTest方法 属性case @1
- 执行tutorialTest模块的getRunTest方法 属性version @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

su('admin');

$tutorialTest = new tutorialTest();

r($tutorialTest->getRunTest()) && p('id') && e('1');
r($tutorialTest->getRunTest()) && p('status') && e('normal');
r($tutorialTest->getRunTest()) && p('task') && e('1');
r($tutorialTest->getRunTest()) && p('case') && e('1');
r($tutorialTest->getRunTest()) && p('version') && e('1');
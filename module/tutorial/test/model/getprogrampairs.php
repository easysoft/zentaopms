#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getProgramPairs();
timeout=0
cid=19453

- 执行tutorialTest模块的getProgramPairsTest方法 属性1 @Test program
- 执行tutorialTest模块的getProgramPairsTest方法  @1
- 执行tutorialTest模块的getProgramPairsTest方法  @1
- 执行tutorialTest模块的getProgramPairsTest方法  @Test program
- 执行tutorialTest模块的getProgramPairsTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tutorialTest = new tutorialModelTest();

r($tutorialTest->getProgramPairsTest()) && p('1') && e('Test program');
r(count($tutorialTest->getProgramPairsTest())) && p() && e('1');
r(array_keys($tutorialTest->getProgramPairsTest())) && p('0') && e('1');
r(array_values($tutorialTest->getProgramPairsTest())) && p('0') && e('Test program');
r(is_array($tutorialTest->getProgramPairsTest())) && p() && e('1');
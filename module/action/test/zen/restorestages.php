#!/usr/bin/env php
<?php

/**

title=测试 actionZen::restoreStages();
timeout=0
cid=14974

- 执行actionTest模块的restoreStagesTest方法，参数是16, 'no'  @1
- 执行actionTest模块的restoreStagesTest方法，参数是17, 'no'  @1
- 执行actionTest模块的restoreStagesTest方法，参数是18, 'no'  @1
- 执行actionTest模块的restoreStagesTest方法，参数是19, 'no'  @1

- 执行actionTest模块的restoreStagesTest方法，参数是20, 'yes'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('project')->gen(20);

su('admin');

$actionTest = new actionZenTest();

r($actionTest->restoreStagesTest(16, 'no'))  && p() && e('1');
r($actionTest->restoreStagesTest(17, 'no'))  && p() && e('1');
r($actionTest->restoreStagesTest(18, 'no'))  && p() && e('1');
r($actionTest->restoreStagesTest(19, 'no'))  && p() && e('1');
r($actionTest->restoreStagesTest(20, 'yes')) && p() && e('1');

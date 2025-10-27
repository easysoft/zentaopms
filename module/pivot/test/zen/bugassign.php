#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::bugAssign();
timeout=0
cid=0

- 执行pivotTest模块的bugAssignTest方法 属性title @Bug指派表
- 执行pivotTest模块的bugAssignTest方法 属性pivotName @Bug指派表
- 执行pivotTest模块的bugAssignTest方法 属性currentMenu @bugassign
- 执行pivotTest模块的bugAssignTest方法 
 - 属性hasUsers @1
 - 属性hasBugs @1
- 执行pivotTest模块的bugAssignTest方法 属性sessionSet @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->bugAssignTest()) && p('title') && e('Bug指派表');
r($pivotTest->bugAssignTest()) && p('pivotName') && e('Bug指派表');
r($pivotTest->bugAssignTest()) && p('currentMenu') && e('bugassign');
r($pivotTest->bugAssignTest()) && p('hasUsers,hasBugs') && e('1,1');
r($pivotTest->bugAssignTest()) && p('sessionSet') && e('1');
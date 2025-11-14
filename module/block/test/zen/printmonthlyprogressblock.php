#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printMonthlyProgressBlock();
timeout=0
cid=15267

- 执行blockTest模块的printMonthlyProgressBlockTest方法 属性doneStoryEstimateCount @6
- 执行blockTest模块的printMonthlyProgressBlockTest方法 属性doneStoryCountCount @6
- 执行blockTest模块的printMonthlyProgressBlockTest方法 属性createStoryCountCount @6
- 执行blockTest模块的printMonthlyProgressBlockTest方法 属性fixedBugCountCount @6
- 执行blockTest模块的printMonthlyProgressBlockTest方法 属性createBugCountCount @6
- 执行blockTest模块的printMonthlyProgressBlockTest方法 属性totalDataArrays @30

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$blockTest = new blockZenTest();

r($blockTest->printMonthlyProgressBlockTest()) && p('doneStoryEstimateCount') && e('6');
r($blockTest->printMonthlyProgressBlockTest()) && p('doneStoryCountCount') && e('6');
r($blockTest->printMonthlyProgressBlockTest()) && p('createStoryCountCount') && e('6');
r($blockTest->printMonthlyProgressBlockTest()) && p('fixedBugCountCount') && e('6');
r($blockTest->printMonthlyProgressBlockTest()) && p('createBugCountCount') && e('6');
r($blockTest->printMonthlyProgressBlockTest()) && p('totalDataArrays') && e('30');
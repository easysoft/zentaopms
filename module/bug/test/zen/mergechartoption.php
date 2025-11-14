#!/usr/bin/env php
<?php

/**

title=测试 bugZen::mergeChartOption();
timeout=0
cid=15463

- 执行bugTest模块的mergeChartOptionTest方法，参数是'bugsPerModule', 'default' 第graph条的caption属性 @模块Bug数量
- 执行bugTest模块的mergeChartOptionTest方法，参数是'bugsPerModule', 'default' 属性type @pie
- 执行bugTest模块的mergeChartOptionTest方法，参数是'bugsPerStatus', 'default' 第graph条的caption属性 @按Bug状态统计
- 执行bugTest模块的mergeChartOptionTest方法，参数是'bugsPerSeverity', 'bar' 属性type @bar
- 执行bugTest模块的mergeChartOptionTest方法，参数是'openedBugsPerDay', 'pie' 属性type @pie
- 执行bugTest模块的mergeChartOptionTest方法，参数是'resolvedBugsPerUser', 'line' 属性type @line
- 执行bugTest模块的mergeChartOptionTest方法，参数是'bugsPerExecution', 'default' 第graph条的caption属性 @迭代Bug数量

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$bugTest = new bugZenTest();

r($bugTest->mergeChartOptionTest('bugsPerModule', 'default')) && p('graph:caption') && e('模块Bug数量');
r($bugTest->mergeChartOptionTest('bugsPerModule', 'default')) && p('type') && e('pie');
r($bugTest->mergeChartOptionTest('bugsPerStatus', 'default')) && p('graph:caption') && e('按Bug状态统计');
r($bugTest->mergeChartOptionTest('bugsPerSeverity', 'bar')) && p('type') && e('bar');
r($bugTest->mergeChartOptionTest('openedBugsPerDay', 'pie')) && p('type') && e('pie');
r($bugTest->mergeChartOptionTest('resolvedBugsPerUser', 'line')) && p('type') && e('line');
r($bugTest->mergeChartOptionTest('bugsPerExecution', 'default')) && p('graph:caption') && e('迭代Bug数量');
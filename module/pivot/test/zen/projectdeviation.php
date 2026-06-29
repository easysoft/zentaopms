#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::projectDeviation();
timeout=0
cid=0

- 执行pivotTest模块的projectDeviationTest方法，参数是'', '' 
 - 属性begin @1
 - 属性end @1
- 执行pivotTest模块的projectDeviationTest方法，参数是'2025-10-01', '' 
 - 属性begin @1
 - 属性end @1
- 执行pivotTest模块的projectDeviationTest方法，参数是'', '2025-10-31' 
 - 属性begin @1
 - 属性end @1
- 执行pivotTest模块的projectDeviationTest方法，参数是'2025-09-01', '2025-09-30' 
 - 属性begin @1
 - 属性end @1
- 执行pivotTest模块的projectDeviationTest方法，参数是'2026-01-01', '2026-01-31' 
 - 属性begin @1
 - 属性end @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

zenData('project')->gen(10);
zenData('task')->gen(20);

su('admin');

$pivotTest = new pivotZenTest();
$currentMonthBegin = date('Y-m-01');
$currentMonthEnd   = date('Y-m-d', strtotime(date('Y-m-01', strtotime('next month')) . ' -1 day'));
$defaultDeviation  = $pivotTest->projectDeviationTest('', '');
$beginDeviation    = $pivotTest->projectDeviationTest('2025-10-01', '');
$endDeviation      = $pivotTest->projectDeviationTest('', '2025-10-31');
$customDeviation   = $pivotTest->projectDeviationTest('2025-09-01', '2025-09-30');
$futureDeviation   = $pivotTest->projectDeviationTest('2026-01-01', '2026-01-31');

r($defaultDeviation['begin'] == $currentMonthBegin ? 1 : 0) && p() && e('1');
r($defaultDeviation['end'] == $currentMonthEnd ? 1 : 0) && p() && e('1');
r($beginDeviation['begin'] == '2025-10-01' ? 1 : 0) && p() && e('1');
r($beginDeviation['end'] == $currentMonthEnd ? 1 : 0) && p() && e('1');
r($endDeviation['begin'] == $currentMonthBegin ? 1 : 0) && p() && e('1');
r($endDeviation['end'] == '2025-10-31' ? 1 : 0) && p() && e('1');
r($customDeviation['begin'] == '2025-09-01' ? 1 : 0) && p() && e('1');
r($customDeviation['end'] == '2025-09-30' ? 1 : 0) && p() && e('1');
r($futureDeviation['begin'] == '2026-01-01' ? 1 : 0) && p() && e('1');
r($futureDeviation['end'] == '2026-01-31' ? 1 : 0) && p() && e('1');

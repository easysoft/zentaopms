#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::projectDeviation();
timeout=0
cid=0

- 执行pivotTest模块的projectDeviationTest方法，参数是'', '' 
 - 属性begin @2025-12-01
 - 属性end @2025-12-31
- 执行pivotTest模块的projectDeviationTest方法，参数是'2025-10-01', '' 
 - 属性begin @2025-10-01
 - 属性end @2025-12-31
- 执行pivotTest模块的projectDeviationTest方法，参数是'', '2025-10-31' 
 - 属性begin @2025-12-01
 - 属性end @2025-10-31
- 执行pivotTest模块的projectDeviationTest方法，参数是'2025-09-01', '2025-09-30' 
 - 属性begin @2025-09-01
 - 属性end @2025-09-30
- 执行pivotTest模块的projectDeviationTest方法，参数是'2026-01-01', '2026-01-31' 
 - 属性begin @2026-01-01
 - 属性end @2026-01-31

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

zenData('project')->gen(10);
zenData('task')->gen(20);

su('admin');

$pivotTest = new pivotZenTest();

r($pivotTest->projectDeviationTest('', '')) && p('begin,end') && e('2025-12-01,2025-12-31');
r($pivotTest->projectDeviationTest('2025-10-01', '')) && p('begin,end') && e('2025-10-01,2025-12-31');
r($pivotTest->projectDeviationTest('', '2025-10-31')) && p('begin,end') && e('2025-12-01,2025-10-31');
r($pivotTest->projectDeviationTest('2025-09-01', '2025-09-30')) && p('begin,end') && e('2025-09-01,2025-09-30');
r($pivotTest->projectDeviationTest('2026-01-01', '2026-01-31')) && p('begin,end') && e('2026-01-01,2026-01-31');
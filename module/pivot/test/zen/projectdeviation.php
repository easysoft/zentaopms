#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::projectDeviation();
timeout=0
cid=0

- 执行pivotTest模块的projectDeviationTest方法，参数是'', ''
 - 属性begin @2025-11-01
 - 属性end @2025-11-30
- 执行pivotTest模块的projectDeviationTest方法，参数是'2025-10-01', '2025-11-10'
 - 属性begin @2025-10-01
 - 属性end @2025-11-10
- 执行pivotTest模块的projectDeviationTest方法，参数是'', ''
 - 属性begin @2025-11-01
 - 属性end @2025-11-30
- 执行pivotTest模块的projectDeviationTest方法，参数是'2025-10-15', ''
 - 属性begin @2025-10-15
 - 属性end @2025-11-30
- 执行pivotTest模块的projectDeviationTest方法，参数是'', '2025-11-15'
 - 属性begin @2025-11-01
 - 属性end @2025-11-15

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

zenData('task')->loadYaml('projectdeviation', false, 2)->gen(30);
zenData('project')->loadYaml('projectdeviation', false, 2)->gen(6);

su('admin');

$pivotTest = new pivotZenTest();

r($pivotTest->projectDeviationTest('', '')) && p('begin,end') && e('2025-11-01,2025-11-30');
r($pivotTest->projectDeviationTest('2025-10-01', '2025-11-10')) && p('begin,end') && e('2025-10-01,2025-11-10');
r($pivotTest->projectDeviationTest('', '')) && p('begin,end') && e('2025-11-01,2025-11-30');
r($pivotTest->projectDeviationTest('2025-10-15', '')) && p('begin,end') && e('2025-10-15,2025-11-30');
r($pivotTest->projectDeviationTest('', '2025-11-15')) && p('begin,end') && e('2025-11-01,2025-11-15');
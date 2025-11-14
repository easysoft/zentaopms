#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getBugGroup();
timeout=0
cid=17440

- 执行pivotTest模块的getBugGroupTest方法，参数是'2025-01-01', '2025-12-31', 0, 0  @1
- 执行pivotTest模块的getBugGroupTest方法，参数是'2025-01-01', '2025-12-31', 0, 0)['admin']  @90
- 执行pivotTest模块的getBugGroupTest方法，参数是'2025-01-01', '2025-12-31', 1, 0)['admin']  @3
- 执行pivotTest模块的getBugGroupTest方法，参数是'2025-01-01', '2025-12-31', 0, 101)['admin']  @3
- 执行getBugGroupTest('2025-01-01', '2025-12-31', 0, 0)['admin'][0]模块的openedBy方法  @admin
- 执行getBugGroupTest('2025-01-01', '2025-12-31', 0, 0)['admin'][0]模块的status方法  @active
- 执行getBugGroupTest('2025-01-01', '2025-12-31', 0, 0)['admin'][39]模块的resolution方法  @unResolved

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('bug')->loadYaml('getbuggroup/bug', false, 2)->gen(100);

su('admin');

$pivotTest = new pivotTaoTest();

r(count($pivotTest->getBugGroupTest('2025-01-01', '2025-12-31', 0, 0))) && p() && e('1');
r(count($pivotTest->getBugGroupTest('2025-01-01', '2025-12-31', 0, 0)['admin'])) && p() && e('90');
r(count($pivotTest->getBugGroupTest('2025-01-01', '2025-12-31', 1, 0)['admin'])) && p() && e('3');
r(count($pivotTest->getBugGroupTest('2025-01-01', '2025-12-31', 0, 101)['admin'])) && p() && e('3');
r($pivotTest->getBugGroupTest('2025-01-01', '2025-12-31', 0, 0)['admin'][0]->openedBy) && p() && e('admin');
r($pivotTest->getBugGroupTest('2025-01-01', '2025-12-31', 0, 0)['admin'][0]->status) && p() && e('active');
r($pivotTest->getBugGroupTest('2025-01-01', '2025-12-31', 0, 0)['admin'][39]->resolution) && p() && e('unResolved');
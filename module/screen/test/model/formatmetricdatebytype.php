#!/usr/bin/env php
<?php

/**

title=测试 screenModel::formatMetricDateByType();
timeout=0
cid=18224

- 步骤1：year类型属性year @2021
- 步骤2：month类型
 - 属性year @2021
 - 属性month @2021-01
- 步骤3：week类型
 - 属性year @2021
 - 属性week @2021-53
- 步骤4：day类型
 - 属性year @2021
 - 属性day @2021-01-01
- 步骤5：边界值属性year @1970

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$screenTest = new screenModelTest();

r($screenTest->formatMetricDateByTypeTest(1609459200000, 'year')) && p('year') && e('2021'); // 步骤1：year类型
r($screenTest->formatMetricDateByTypeTest(1609459200000, 'month')) && p('year,month') && e('2021,2021-01'); // 步骤2：month类型
r($screenTest->formatMetricDateByTypeTest(1609459200000, 'week')) && p('year,week') && e('2021,2021-53'); // 步骤3：week类型
r($screenTest->formatMetricDateByTypeTest(1609459200000, 'day')) && p('year,day') && e('2021,2021-01-01'); // 步骤4：day类型
r($screenTest->formatMetricDateByTypeTest(1000, 'year')) && p('year') && e('1970'); // 步骤5：边界值
#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::checkIFChartInUse();
timeout=0
cid=17359

- 执行pivotTest模块的checkIFChartInUseTest方法，参数是1002, 'pivot', $screenList  @1
- 执行pivotTest模块的checkIFChartInUseTest方法，参数是2001, 'chart', $screenList  @1
- 执行pivotTest模块的checkIFChartInUseTest方法，参数是9999, 'pivot', $screenList  @0
- 执行pivotTest模块的checkIFChartInUseTest方法，参数是1002, 'pivot', $screenList  @1
- 执行pivotTest模块的checkIFChartInUseTest方法，参数是0, 'chart', $screenList  @0
- 执行pivotTest模块的checkIFChartInUseTest方法，参数是5000, 'pivot', $screenList  @0
- 执行pivotTest模块的checkIFChartInUseTest方法，参数是1002, 'pivot', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest = new pivotModelTest();

$screenList = array();

$screen1 = new stdClass();
$screen1->id = 1;
$screen1->scheme = '';
$screen1->status = 'published';
$screen1->deleted = 0;
$screenList[] = $screen1;

$screen2 = new stdClass();
$screen2->id = 2;
$screen2->scheme = '{"componentList":[]}';
$screen2->status = 'published';
$screen2->deleted = 0;
$screenList[] = $screen2;

$screen3 = new stdClass();
$screen3->id = 3;
$screen3->scheme = '{"componentList":[{"chartConfig":{"sourceID":1002,"package":"Tables"}}]}';
$screen3->status = 'published';
$screen3->deleted = 0;
$screenList[] = $screen3;

$screen4 = new stdClass();
$screen4->id = 4;
$screen4->scheme = '{"componentList":[{"chartConfig":{"sourceID":2001,"package":"Charts"}}]}';
$screen4->status = 'published';
$screen4->deleted = 0;
$screenList[] = $screen4;

$screen5 = new stdClass();
$screen5->id = 5;
$screen5->scheme = '{"componentList":[{"isGroup":true,"groupList":[{"chartConfig":{"sourceID":1002,"package":"Tables"}},{"chartConfig":{"sourceID":2002,"package":"Charts"}}]}]}';
$screen5->status = 'published';
$screen5->deleted = 0;
$screenList[] = $screen5;

r($pivotTest->checkIFChartInUseTest(1002, 'pivot', $screenList)) && p('') && e('1');
r($pivotTest->checkIFChartInUseTest(2001, 'chart', $screenList)) && p('') && e('1');
r($pivotTest->checkIFChartInUseTest(9999, 'pivot', $screenList)) && p('') && e('0');
r($pivotTest->checkIFChartInUseTest(1002, 'pivot', $screenList)) && p('') && e('1');
r($pivotTest->checkIFChartInUseTest(0, 'chart', $screenList)) && p('') && e('0');
r($pivotTest->checkIFChartInUseTest(5000, 'pivot', $screenList)) && p('') && e('0');
r($pivotTest->checkIFChartInUseTest(1002, 'pivot', array())) && p('') && e('1');
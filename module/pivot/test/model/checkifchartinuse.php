#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->checkIfChartInUse().
timeout=0
cid=1

- id为1000的透视表被大屏使用了 @1
- id为1003的透视表未被大屏使用 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivotTest = new pivotTest();

$pivotIDList = array(1002, 1003);

$screenList = $tester->dao->select('*')->from(TABLE_SCREEN)->where('deleted')->eq(0)->andWhere('status')->eq('published')->fetchAll('', false);

r($pivotTest->checkIfChartInUse($pivotIDList[0],  'pivot', $screenList)) && p('') && e(1);  //id为1000的透视表被大屏使用了
r(!$pivotTest->checkIfChartInUse($pivotIDList[1], 'pivot', $screenList)) && p('') && e(1);  //id为1003的透视表未被大屏使用

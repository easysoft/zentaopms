#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getChartOption();
timeout=0
cid=18239

- 执行$result1) || is_object($result1) || $result1 ===  @1
- 执行$result2) || is_object($result2) || $result2 ===  @1
- 执行$result3) || is_object($result3) || $result3 ===  @1
- 执行$result4) || is_object($result4) || $result4 ===  @1
- 执行$result5 ===  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$screenTest = new screenModelTest();
r($screenTest->getChartOptionTest('line'))    && p() && e('success');
r($screenTest->getChartOptionTest('bar'))     && p() && e('success');
r($screenTest->getChartOptionTest('pie'))     && p() && e('success');
r($screenTest->getChartOptionTest('table'))   && p() && e('success');
r($screenTest->getChartOptionTest('unknown') === '') && p() && e('1');

#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getColumnSummary();
cid=0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivotTest = new pivotTest();

r($pivotTest->getColumnSummaryTest(array(
    array('col1' => array('value' => 10, 'isGroup' => 0)),
    array('col1' => array('value' => 15, 'isGroup' => 0))
), 'total')) && p('col1:value') && e('25');

r($pivotTest->getColumnSummaryTest(array(
    array('col1' => array('value' => 'text', 'isGroup' => 0))
), 'summary')) && p('col1:value') && e('text');

r($pivotTest->getColumnSummaryTest(array(), 'empty')) && p('empty:value') && e('$total$');

r($pivotTest->getColumnSummaryTest(array(
    array('col1' => array('value' => 30, 'isGroup' => 1))
), 'grouped')) && p('col1:value') && e('30');

r($pivotTest->getColumnSummaryTest(array(
    array('col1' => array('value' => 10.555, 'isGroup' => 0)),
    array('col1' => array('value' => 15.333, 'isGroup' => 0))
), 'decimal')) && p('col1:value') && e('25.89');
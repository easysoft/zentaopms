#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::isFiltersAllEmpty();
timeout=0
cid=0

- 执行pivotTest模块的isFiltersAllEmptyTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->isFiltersAllEmptyTest(array())) && p() && e('0');

r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => null),
    array('name' => 'filter3', 'default' => false),
    array('name' => 'filter4', 'default' => 0),
    array('name' => 'filter5', 'default' => array())
))) && p() && e('1');

r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => 'non-empty-value'),
    array('name' => 'filter3', 'default' => null)
))) && p() && e('0');

r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => '')
))) && p() && e('1');

r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1'),
    array('name' => 'filter2'),
    array('name' => 'filter3')
))) && p() && e('1');
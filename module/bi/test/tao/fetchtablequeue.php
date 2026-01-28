#!/usr/bin/env php
<?php

/**

title=测试 biTao::fetchTableQueue();
timeout=0
cid=15222

- 执行biTest模块的fetchTableQueueTest方法  @1
- 执行biTest模块的fetchTableQueueTest方法  @4
- 执行biTest模块的fetchTableQueueTest方法，参数是['zt_user']  @1
- 执行biTest模块的fetchTableQueueTest方法  @0
- 执行biTest模块的fetchTableQueueTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

global $tester;
$dao = $tester->dao;

$dao->delete()->from(TABLE_DUCKDBQUEUE)->exec();

$dao->insert(TABLE_DUCKDBQUEUE)->data(array(
    'object' => 'zt_user',
    'updatedTime' => '2024-01-01 10:00:00',
    'syncTime' => '2024-01-01 09:00:00'
))->exec();

$dao->insert(TABLE_DUCKDBQUEUE)->data(array(
    'object' => 'zt_product',
    'updatedTime' => '2024-01-02 10:00:00',
    'syncTime' => null
))->exec();

$dao->insert(TABLE_DUCKDBQUEUE)->data(array(
    'object' => 'zt_project',
    'updatedTime' => '2024-01-03 10:00:00',
    'syncTime' => '2024-01-03 11:00:00'
))->exec();

$dao->insert(TABLE_DUCKDBQUEUE)->data(array(
    'object' => 'zt_task',
    'updatedTime' => null,
    'syncTime' => null
))->exec();

$dao->insert(TABLE_DUCKDBQUEUE)->data(array(
    'object' => 'zt_bug',
    'updatedTime' => '2024-01-05 10:00:00',
    'syncTime' => '2024-01-05 09:00:00'
))->exec();

$dao->insert(TABLE_DUCKDBQUEUE)->data(array(
    'object' => 'zt_metriclib',
    'updatedTime' => '2024-01-06 10:00:00',
    'syncTime' => null
))->exec();

$dao->insert(TABLE_DUCKDBQUEUE)->data(array(
    'object' => 'zt_action',
    'updatedTime' => '2024-01-07 10:00:00',
    'syncTime' => '2024-01-07 11:00:00'
))->exec();

su('admin');

$biTest = new biTaoTest();

r(is_array($biTest->fetchTableQueueTest())) && p() && e('1');
r(count($biTest->fetchTableQueueTest())) && p() && e('4');
r(isset($biTest->fetchTableQueueTest()['zt_user'])) && p() && e('1');
r(in_array('zt_metriclib', $biTest->fetchTableQueueTest())) && p() && e('0');
r(in_array('zt_action', $biTest->fetchTableQueueTest())) && p() && e('0');
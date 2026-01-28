#!/usr/bin/env php
<?php

/**

title=测试 biTao::updateSyncTime();
timeout=0
cid=15223

- 执行biTest模块的updateSyncTimeTest方法，参数是array  @0
- 执行biTest模块的updateSyncTimeTest方法，参数是array  @1
- 执行biTest模块的updateSyncTimeTest方法，参数是array  @2
- 执行biTest模块的updateSyncTimeTest方法，参数是array  @0
- 执行biTest模块的updateSyncTimeTest方法，参数是array  @1

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
    'syncTime' => '2024-01-02 09:00:00'
))->exec();

$dao->insert(TABLE_DUCKDBQUEUE)->data(array(
    'object' => 'zt_project',
    'updatedTime' => '2024-01-03 10:00:00',
    'syncTime' => '2024-01-03 09:00:00'
))->exec();

$dao->insert(TABLE_DUCKDBQUEUE)->data(array(
    'object' => 'zt_task',
    'updatedTime' => '2024-01-04 10:00:00',
    'syncTime' => null
))->exec();

su('admin');

$biTest = new biTaoTest();

r($biTest->updateSyncTimeTest(array())) && p() && e('0');
r($biTest->updateSyncTimeTest(array('zt_user'))) && p() && e('1');
r($biTest->updateSyncTimeTest(array('zt_product', 'zt_project'))) && p() && e('2');
r($biTest->updateSyncTimeTest(array('zt_nonexistent'))) && p() && e('0');
r($biTest->updateSyncTimeTest(array('zt_task', 'zt_nonexistent'))) && p() && e('1');
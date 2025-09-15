#!/usr/bin/env php
<?php

/**

title=测试 biTao::fetchAllTables();
timeout=0
cid=0

- 步骤1：正常情况返回239个表 @239
- 步骤2：包含用户表属性zt_user @zt_user
- 步骤3：不包含action表 @0
- 步骤4：不包含duckdbqueue表 @0
- 步骤5：不包含metriclib表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

r(count($biTest->fetchAllTablesTest())) && p() && e('239');                // 步骤1：正常情况返回239个表
r($biTest->fetchAllTablesTest()) && p('zt_user') && e('zt_user');          // 步骤2：包含用户表
r(isset($biTest->fetchAllTablesTest()['zt_action'])) && p() && e('0');     // 步骤3：不包含action表
r(isset($biTest->fetchAllTablesTest()['zt_duckdbqueue'])) && p() && e('0'); // 步骤4：不包含duckdbqueue表
r(isset($biTest->fetchAllTablesTest()['zt_metriclib'])) && p() && e('0');   // 步骤5：不包含metriclib表
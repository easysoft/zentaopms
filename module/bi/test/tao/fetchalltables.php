#!/usr/bin/env php
<?php

/**

title=测试 biTao::fetchAllTables();
timeout=0
cid=15221

- 执行biTest模块的fetchAllTablesTest方法  @1
- 执行biTest模块的fetchAllTablesTest方法  @1
- 执行biTest模块的fetchAllTablesTest方法  @0
- 执行biTest模块的fetchAllTablesTest方法  @0
- 执行biTest模块的fetchAllTablesTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

r(is_array($biTest->fetchAllTablesTest())) && p() && e('1');
r(in_array('zt_user', $biTest->fetchAllTablesTest())) && p() && e('1');
r(in_array('zt_metriclib', $biTest->fetchAllTablesTest())) && p() && e('0');
r(in_array('zt_action', $biTest->fetchAllTablesTest())) && p() && e('0');
r(in_array('zt_duckdbqueue', $biTest->fetchAllTablesTest())) && p() && e('0');
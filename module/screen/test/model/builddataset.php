#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildDataset();
timeout=0
cid=18209

- 执行screenTest模块的buildDatasetTest方法，参数是20002, 'mysql', '' 第0条的count属性 @0
- 执行screenTest模块的buildDatasetTest方法，参数是20004, 'mysql', '' 第0条的count属性 @0
- 执行screenTest模块的buildDatasetTest方法，参数是20007, 'mysql', '' 第0条的count属性 @0
- 执行screenTest模块的buildDatasetTest方法，参数是10018, 'mysql', 'SELECT COUNT 第0条的count属性 @5
- 执行screenTest模块的buildDatasetTest方法，参数是999, 'mysql', 'SELECT 1 as test' 第0条的test属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

su('admin');

$screenTest = new screenTest();

r($screenTest->buildDatasetTest(20002, 'mysql', '')) && p('0:count') && e('0');
r($screenTest->buildDatasetTest(20004, 'mysql', '')) && p('0:count') && e('0');
r($screenTest->buildDatasetTest(20007, 'mysql', '')) && p('0:count') && e('0');
r($screenTest->buildDatasetTest(10018, 'mysql', 'SELECT COUNT(*) as count FROM zt_user')) && p('0:count') && e('5');
r($screenTest->buildDatasetTest(999, 'mysql', 'SELECT 1 as test')) && p('0:test') && e('1');
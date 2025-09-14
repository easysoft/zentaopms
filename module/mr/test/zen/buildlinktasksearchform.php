#!/usr/bin/env php
<?php

/**

title=测试 mrZen::buildLinkTaskSearchForm();
timeout=0
cid=0

- 执行mrTest模块的buildLinkTaskSearchFormTest方法，参数是1, 1, 'id', 10, array 属性queryID @10
- 执行mrTest模块的buildLinkTaskSearchFormTest方法，参数是2, 2, 'name', 0, array 属性queryID @0
- 执行mrTest模块的buildLinkTaskSearchFormTest方法，参数是-1, 1, 'id', 5, array  @invalid_mrid
- 执行mrTest模块的buildLinkTaskSearchFormTest方法，参数是1, -1, 'id', 5, array  @invalid_repoid
- 执行mrTest模块的buildLinkTaskSearchFormTest方法，参数是3, 3, 'status', 15, array 属性execution_values @0
- 执行mrTest模块的buildLinkTaskSearchFormTest方法，参数是4, 4, 'priority', 20, array 属性execution_values @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

su('admin');

$mrTest = new mrTest();

r($mrTest->buildLinkTaskSearchFormTest(1, 1, 'id', 10, array('1' => 'Project 1', '2' => 'Project 2'))) && p('queryID') && e(10);
r($mrTest->buildLinkTaskSearchFormTest(2, 2, 'name', 0, array())) && p('queryID') && e(0);
r($mrTest->buildLinkTaskSearchFormTest(-1, 1, 'id', 5, array())) && p() && e('invalid_mrid');
r($mrTest->buildLinkTaskSearchFormTest(1, -1, 'id', 5, array())) && p() && e('invalid_repoid');
r($mrTest->buildLinkTaskSearchFormTest(3, 3, 'status', 15, array())) && p('execution_values') && e(0);
r($mrTest->buildLinkTaskSearchFormTest(4, 4, 'priority', 20, array('10' => 'Execution 1', '20' => 'Execution 2', '30' => 'Execution 3'))) && p('execution_values') && e(3);
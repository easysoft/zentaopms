#!/usr/bin/env php
<?php

/**

title=测试 programZen::getProgramsByType();
timeout=0
cid=0

- 执行programTest模块的getProgramsByTypeTest方法，参数是'doing', 'id_asc'  @10
- 执行programTest模块的getProgramsByTypeTest方法，参数是'wait', 'id_desc'  @6
- 执行programTest模块的getProgramsByTypeTest方法，参数是'closed', 'order_asc'  @2
- 执行programTest模块的getProgramsByTypeTest方法，参数是'unclosed', 'id_asc'  @18
- 执行programTest模块的getProgramsByTypeTest方法，参数是'bysearch', 'id_asc', 0  @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

$table = zenData('project');
$table->loadYaml('project_getprogramsbytype', false, 2);
$table->gen(20);

su('admin');

$programTest = new programTest();

r($programTest->getProgramsByTypeTest('doing', 'id_asc')) && p() && e('10');
r($programTest->getProgramsByTypeTest('wait', 'id_desc')) && p() && e('6');
r($programTest->getProgramsByTypeTest('closed', 'order_asc')) && p() && e('2');
r($programTest->getProgramsByTypeTest('unclosed', 'id_asc')) && p() && e('18');
r($programTest->getProgramsByTypeTest('bysearch', 'id_asc', 0)) && p() && e('10');
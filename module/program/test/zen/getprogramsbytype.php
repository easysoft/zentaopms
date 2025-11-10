#!/usr/bin/env php
<?php

/**

title=测试 programZen::getProgramsByType();
timeout=0
cid=0

- 执行programTest模块的getProgramsByTypeTest方法，参数是'doing', 'id_desc', 0  @9
- 执行programTest模块的getProgramsByTypeTest方法，参数是'unclosed', 'id_desc', 0  @17
- 执行programTest模块的getProgramsByTypeTest方法，参数是'wait', 'id_desc', 0  @5
- 执行programTest模块的getProgramsByTypeTest方法，参数是'closed', 'id_desc', 0  @2
- 执行programTest模块的getProgramsByTypeTest方法，参数是'suspended', 'id_desc', 0  @3
- 执行programTest模块的getProgramsByTypeTest方法，参数是'doing', 'order_asc', 0  @9
- 执行programTest模块的getProgramsByTypeTest方法，参数是'all', 'id_desc', 0  @19

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

zenData('project')->loadYaml('getprogramsbytype/project', false, 2)->gen(25);
zenData('user')->gen(5);

su('admin');

$programTest = new programTest();

r(count($programTest->getProgramsByTypeTest('doing', 'id_desc', 0))) && p() && e('9');
r(count($programTest->getProgramsByTypeTest('unclosed', 'id_desc', 0))) && p() && e('17');
r(count($programTest->getProgramsByTypeTest('wait', 'id_desc', 0))) && p() && e('5');
r(count($programTest->getProgramsByTypeTest('closed', 'id_desc', 0))) && p() && e('2');
r(count($programTest->getProgramsByTypeTest('suspended', 'id_desc', 0))) && p() && e('3');
r(count($programTest->getProgramsByTypeTest('doing', 'order_asc', 0))) && p() && e('9');
r(count($programTest->getProgramsByTypeTest('all', 'id_desc', 0))) && p() && e('19');
#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::setTreePath();
timeout=0
cid=17757

- 执行programplanTest模块的setTreePathTest方法，参数是2
 - 属性path @,1,2,
 - 属性grade @1
- 执行programplanTest模块的setTreePathTest方法，参数是3
 - 属性path @,1,2,3,
 - 属性grade @2
- 执行programplanTest模块的setTreePathTest方法，参数是7
 - 属性path @,7,
 - 属性grade @1
- 执行programplanTest模块的setTreePathTest方法，参数是8
 - 属性path @,7,8,
 - 属性grade @1
- 执行programplanTest模块的setTreePathTest方法，参数是1
 - 属性path @,1,
 - 属性grade @1
- 执行programplanTest模块的setTreePathTest方法，参数是6
 - 属性path @,4,5,6,
 - 属性grade @3
- 执行$result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

zenData('project')->loadYaml('project')->gen(10);

su('admin');

$programplanTest = new programplanTest();

r($programplanTest->setTreePathTest(2)) && p('path|grade', '|') && e(',1,2,|1');
r($programplanTest->setTreePathTest(3)) && p('path|grade', '|') && e(',1,2,3,|2');
r($programplanTest->setTreePathTest(7)) && p('path|grade', '|') && e(',7,|1');
r($programplanTest->setTreePathTest(8)) && p('path|grade', '|') && e(',7,8,|1');
r($programplanTest->setTreePathTest(1)) && p('path|grade', '|') && e(',1,|1');
r($programplanTest->setTreePathTest(6)) && p('path|grade', '|') && e(',4,5,6,|3');

global $tester;
$result = $tester->programplan->setTreePath(9);
r($result) && p() && e(1);
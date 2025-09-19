#!/usr/bin/env php
<?php

/**

title=测试 chartModel::getFirstGroup();
timeout=0
cid=0

- 执行chartTest模块的getFirstGroupTest方法，参数是1  @32
- 执行chartTest模块的getFirstGroupTest方法，参数是999  @0
- 执行chartTest模块的getFirstGroupTest方法  @0
- 执行chartTest模块的getFirstGroupTest方法，参数是-1  @0
- 执行chartTest模块的getFirstGroupTest方法，参数是9999  @0
- 执行chartTest模块的getFirstGroupTest方法，参数是1  @32
- 执行chartTest模块的getFirstGroupTest方法，参数是2  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

zenData('module')->loadYaml('module')->gen(27);
zenData('user')->gen(5);

su('admin');

$chartTest = new chartTest();

r($chartTest->getFirstGroupTest(1)) && p() && e('32');
r($chartTest->getFirstGroupTest(999)) && p() && e(0);
r($chartTest->getFirstGroupTest(0)) && p() && e(0);
r($chartTest->getFirstGroupTest(-1)) && p() && e(0);
r($chartTest->getFirstGroupTest(9999)) && p() && e(0);
r($chartTest->getFirstGroupTest(1)) && p() && e('32');
r($chartTest->getFirstGroupTest(2)) && p() && e(0);
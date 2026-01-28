#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::initResultForAutomatedTest();
timeout=0
cid=19205

- 执行testtaskTest模块的initResultForAutomatedTestTest方法，参数是1, 2, 1, 3  @1
- 执行testtaskTest模块的initResultForAutomatedTestTest方法，参数是0, 0, 0, 0  @2
- 执行testtaskTest模块的initResultForAutomatedTestTest方法，参数是10, 20, 2, 5  @3
- 执行testtaskTest模块的initResultForAutomatedTestTest方法，参数是100, 200, 3, 10  @4
- 执行testtaskTest模块的initResultForAutomatedTestTest方法  @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zendata('testresult')->loadYaml('testresult_initresultforautomatedtest', false, 2)->gen(0);

su('admin');

$testtaskTest = new testtaskModelTest();

r($testtaskTest->initResultForAutomatedTestTest(1, 2, 1, 3)) && p() && e('1');
r($testtaskTest->initResultForAutomatedTestTest(0, 0, 0, 0)) && p() && e('2');
r($testtaskTest->initResultForAutomatedTestTest(10, 20, 2, 5)) && p() && e('3');
r($testtaskTest->initResultForAutomatedTestTest(100, 200, 3, 10)) && p() && e('4');
r($testtaskTest->initResultForAutomatedTestTest()) && p() && e('5');
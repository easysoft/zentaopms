#!/usr/bin/env php
<?php

/**

title=测试 projectZen::displayAfterCreated();
timeout=0
cid=17938

- 执行projectTest模块的displayAfterCreatedTest方法，参数是1  @valid project id
- 执行projectTest模块的displayAfterCreatedTest方法，参数是999  @non-existent project id
- 执行projectTest模块的displayAfterCreatedTest方法  @zero project id
- 执行projectTest模块的displayAfterCreatedTest方法，参数是-1  @negative project id
- 执行projectTest模块的displayAfterCreatedTest方法，参数是null  @method signature validated

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

su('admin');

$projectTest = new projectzenTest();

r($projectTest->displayAfterCreatedTest(1)) && p() && e('valid project id');
r($projectTest->displayAfterCreatedTest(999)) && p() && e('non-existent project id');
r($projectTest->displayAfterCreatedTest(0)) && p() && e('zero project id');
r($projectTest->displayAfterCreatedTest(-1)) && p() && e('negative project id');
r($projectTest->displayAfterCreatedTest(null)) && p() && e('method signature validated');
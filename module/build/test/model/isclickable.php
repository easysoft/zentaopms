#!/usr/bin/env php
<?php

/**

title=测试 buildModel::isClickable();
timeout=0
cid=15501

- 执行buildTest模块的isClickableTest方法，参数是'create', 'bug', false  @1
- 执行buildTest模块的isClickableTest方法，参数是'create', 'testtask', false  @1
- 执行buildTest模块的isClickableTest方法，参数是'create', 'testtask', true  @0
- 执行buildTest模块的isClickableTest方法，参数是'edit', 'bug', false  @1
- 执行buildTest模块的isClickableTest方法，参数是'delete', 'story', false  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->gen(5);

su('admin');

$buildTest = new buildTest();

r($buildTest->isClickableTest('create', 'bug', false)) && p() && e('1');
r($buildTest->isClickableTest('create', 'testtask', false)) && p() && e('1');
r($buildTest->isClickableTest('create', 'testtask', true)) && p() && e('0');
r($buildTest->isClickableTest('edit', 'bug', false)) && p() && e('1');
r($buildTest->isClickableTest('delete', 'story', false)) && p() && e('1');
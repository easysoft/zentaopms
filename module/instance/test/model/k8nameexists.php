#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::k8nameExists();
timeout=0
cid=16809

- 执行instanceTest模块的k8nameExistsTest方法，参数是'test-k8name-1'  @1
- 执行instanceTest模块的k8nameExistsTest方法，参数是'nonexistent-k8name'  @0
- 执行instanceTest模块的k8nameExistsTest方法，参数是''  @0
- 执行instanceTest模块的k8nameExistsTest方法，参数是'test-k8name-5'  @0
- 执行instanceTest模块的k8nameExistsTest方法，参数是'test-k8name-special'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('instance');
$table->id->range('1-5');
$table->k8name->range('test-k8name-1,test-k8name-2,test-k8name-3,test-k8name-4,test-k8name-5');
$table->deleted->range('0{4},1');
$table->gen(5);

su('admin');

$instanceTest = new instanceModelTest();

r($instanceTest->k8nameExistsTest('test-k8name-1')) && p() && e('1');
r($instanceTest->k8nameExistsTest('nonexistent-k8name')) && p() && e('0');
r($instanceTest->k8nameExistsTest('')) && p() && e('0');
r($instanceTest->k8nameExistsTest('test-k8name-5')) && p() && e('0');
r($instanceTest->k8nameExistsTest('test-k8name-special')) && p() && e('0');
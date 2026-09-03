#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::deleteByIDs();
timeout=0
cid=0

- 执行errorlog模块的deleteByIDsTest方法，参数是array(2, 3) @1
- 执行errorlog模块的getListTest方法 @2
- 执行errorlog模块的getByIDTest方法，参数是2 @0
- 执行errorlog模块的getByIDTest方法，参数是3 @0
- 执行errorlog模块的getByIDTest方法，参数是4 @4
- 执行errorlog模块的deleteByIDsTest方法，参数是array(1, 'abc', 4) @1
- 执行errorlog模块的getListTest方法 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$errorlog = zenData('errorlog');
$errorlog->requestID->range('req-001{4}');
$errorlog->gen(4);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->deleteByIDsTest(array(2, 3))) && p() && e('1');
r(count($errorlogModel->getListTest())) && p() && e('2');
r($errorlogModel->getByIDTest(2)) && p() && e('0');
r($errorlogModel->getByIDTest(3)) && p() && e('0');
r($errorlogModel->getByIDTest(4)) && p('id') && e('4');
r($errorlogModel->deleteByIDsTest(array(1, 'abc', 4))) && p() && e('1');
r(count($errorlogModel->getListTest())) && p() && e('0');

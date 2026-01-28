#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::initCase();
timeout=0
cid=19203

- 执行testtaskTest模块的initCaseTest方法，参数是1, 'Test Case Title', '2024-01-01 10:00:00', 'no', 'phpunit' 属性product @1
- 执行testtaskTest模块的initCaseTest方法，参数是2, 'Another Test Case', '2024-01-02 11:00:00', 'yes', 'jest' 属性type @unit
- 执行testtaskTest模块的initCaseTest方法，参数是3, 'Feature Test Case', '2024-01-03 12:00:00', 'no', 'selenium', 'feature', 'functest' 属性type @feature
- 执行testtaskTest模块的initCaseTest方法，参数是0, '', '2024-01-04 13:00:00', 'yes', '' 属性product @0
- 执行testtaskTest模块的initCaseTest方法，参数是999, 'Large Product ID Test Case', '2024-12-31 23:59:59', 'auto', 'custom' 属性frame @custom

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$user = zenData('user');
$user->account->range('admin,test1,test2');
$user->realname->range('管理员,测试用户1,测试用户2');
$user->password->range('123456');
$user->gen(3);

su('admin');

$testtaskTest = new testtaskModelTest();

r($testtaskTest->initCaseTest(1, 'Test Case Title', '2024-01-01 10:00:00', 'no', 'phpunit')) && p('product') && e('1');
r($testtaskTest->initCaseTest(2, 'Another Test Case', '2024-01-02 11:00:00', 'yes', 'jest')) && p('type') && e('unit');
r($testtaskTest->initCaseTest(3, 'Feature Test Case', '2024-01-03 12:00:00', 'no', 'selenium', 'feature', 'functest')) && p('type') && e('feature');
r($testtaskTest->initCaseTest(0, '', '2024-01-04 13:00:00', 'yes', '')) && p('product') && e('0');
r($testtaskTest->initCaseTest(999, 'Large Product ID Test Case', '2024-12-31 23:59:59', 'auto', 'custom')) && p('frame') && e('custom');
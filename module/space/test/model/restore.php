#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::restore();
timeout=0
cid=0

- 还原不存在的空间(API返回错误)并验证返回非布尔true @1
- 还原空间ID=0(API返回错误)并验证返回非布尔true @1
- 还原无效空间ID=9999(API返回错误)并验证返回非布尔true @1
- 还原后验证返回结果为数组(dao错误信息) @1
- 重复还原同一空间(API返回错误)并验证返回非布尔true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(10);
zenData('ops_spaceuser')->gen(10);

su('admin');

$spaceTester = new spaceModelTest();

r(is_array($spaceTester->restoreTest(9999, 1))) && p() && e('1');    // 还原不存在的空间(API返回错误)并验证返回非布尔true
r(is_array($spaceTester->restoreTest(0, 0))) && p() && e('1');       // 还原空间ID=0(API返回错误)并验证返回非布尔true
r(is_array($spaceTester->restoreTest(1, 1))) && p() && e('1');       // 还原无效空间ID=9999(API返回错误)并验证返回非布尔true

r(is_array($spaceTester->restoreTest(2, 1))) && p() && e('1');       // 还原后验证返回结果为数组(dao错误信息)

r(is_array($spaceTester->restoreTest(3, 1))) && p() && e('1');       // 重复还原同一空间(API返回错误)并验证返回非布尔true

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

/**

title=测试 devModel::setField();
cid=1
pid=1

设置字段类型为varchar >> varchar

*/

$devTester = new devTest();
r($devTester->setFieldTest('varchar')) && p('type') && e("varchar"); // 设置字段类型为varchar

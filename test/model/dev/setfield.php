#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dev.class.php';

/**

title=测试 devModel::setField();
cid=1
pid=1

设置字段类型为varchar >> varchar

*/

$devTester = new devTest();
r($devTester->setFieldTest('varchar')) && p('type') && e("varchar"); // 设置字段类型为varchar

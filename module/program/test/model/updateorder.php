#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';
su('admin');

zdTable('project')->gen(10);

/**

title=测试 programModel::updateOrder();
timeout=0
cid=1

*/

$programTester = new programTest();

r($programTester->updateOrderTest(10, 1)) && p('order') && e('1'); // 设置排序是1
r($programTester->updateOrderTest(10, 5)) && p('order') && e('5'); // 设置排序是5


r($programTester->updateOrderTest(0, 1))  && p('order') && e('0'); // 设置空的项目集
r($programTester->updateOrderTest(11, 1)) && p('order') && e('0'); // 设置不存在的项目集

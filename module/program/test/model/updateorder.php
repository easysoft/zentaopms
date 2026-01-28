#!/usr/bin/env php
<?php

/**

title=测试 programModel::updateOrder();
timeout=0
cid=17714

- 设置排序是1属性order @1
- 设置排序是5属性order @5
- 设置排序是10属性order @10
- 设置空的项目集属性order @0
- 设置不存在的项目集属性order @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->gen(10);

$programTester = new programModelTest();

r($programTester->updateOrderTest(10, 1))  && p('order') && e('1');  // 设置排序是1
r($programTester->updateOrderTest(10, 5))  && p('order') && e('5');  // 设置排序是5
r($programTester->updateOrderTest(10, 10)) && p('order') && e('10'); // 设置排序是10
r($programTester->updateOrderTest(0, 1))   && p('order') && e('0');  // 设置空的项目集
r($programTester->updateOrderTest(11, 1))  && p('order') && e('0');  // 设置不存在的项目集

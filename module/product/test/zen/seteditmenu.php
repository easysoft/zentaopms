#!/usr/bin/env php
<?php

/**

title=测试 productZen::setEditMenu();
timeout=0
cid=17611

- 步骤1:测试仅传入productID,programID为0
 - 属性productID @1
 - 属性programID @0
- 步骤2:测试同时传入productID和programID
 - 属性productID @1
 - 属性programID @1
- 步骤3:测试productID为0,programID为1
 - 属性productID @0
 - 属性programID @1
- 步骤4:测试productID和programID均为0
 - 属性productID @0
 - 属性programID @0
- 步骤5:测试传入较大的productID值
 - 属性productID @9999
 - 属性programID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->setEditMenuTest(1, 0)) && p('productID,programID') && e('1,0'); // 步骤1:测试仅传入productID,programID为0
r($productTest->setEditMenuTest(1, 1)) && p('productID,programID') && e('1,1'); // 步骤2:测试同时传入productID和programID
r($productTest->setEditMenuTest(0, 1)) && p('productID,programID') && e('0,1'); // 步骤3:测试productID为0,programID为1
r($productTest->setEditMenuTest(0, 0)) && p('productID,programID') && e('0,0'); // 步骤4:测试productID和programID均为0
r($productTest->setEditMenuTest(9999, 0)) && p('productID,programID') && e('9999,0'); // 步骤5:测试传入较大的productID值
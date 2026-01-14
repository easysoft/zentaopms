#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printCreateList();
timeout=0
cid=15692

- 执行commonTest模块的printCreateListTest方法，参数是1  @1
- 执行commonTest模块的printCreateListTest方法，参数是2  @1
- 执行commonTest模块的printCreateListTest方法，参数是3  @1
- 执行commonTest模块的printCreateListTest方法，参数是4  @1
- 执行commonTest模块的printCreateListTest方法，参数是5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

r($commonTest->printCreateListTest(1)) && p() && e('1');
r($commonTest->printCreateListTest(2)) && p() && e('1');
r($commonTest->printCreateListTest(3)) && p() && e('1');
r($commonTest->printCreateListTest(4)) && p() && e('1');
r($commonTest->printCreateListTest(5)) && p() && e('1');
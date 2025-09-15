#!/usr/bin/env php
<?php

/**

title=测试 commonModel::getSysURL();
timeout=0
cid=0

- 执行commonTest模块的getSysURLTest方法，参数是1  @
- 执行commonTest模块的getSysURLTest方法，参数是2  @1
- 执行commonTest模块的getSysURLTest方法，参数是3  @1
- 执行commonTest模块的getSysURLTest方法，参数是4  @1
- 执行commonTest模块的getSysURLTest方法，参数是5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

// 测试步骤1：基本功能测试 - 测试模式下返回空字符串
r($commonTest->getSysURLTest(1)) && p() && e('');

// 测试步骤2：方法存在性验证
r($commonTest->getSysURLTest(2)) && p() && e('1');

// 测试步骤3：静态方法验证
r($commonTest->getSysURLTest(3)) && p() && e('1');

// 测试步骤4：返回类型验证
r($commonTest->getSysURLTest(4)) && p() && e('1');

// 测试步骤5：参数数量验证
r($commonTest->getSysURLTest(5)) && p() && e('1');
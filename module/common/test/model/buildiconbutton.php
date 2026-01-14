#!/usr/bin/env php
<?php

/**

title=测试 commonModel::buildIconButton();
timeout=0
cid=15646

- 步骤1：验证方法存在 @1
- 步骤2：验证方法为静态方法 @1
- 步骤3：验证方法为公共方法 @1
- 步骤4：验证参数数量 @13
- 步骤5：验证第一个参数名称 @module

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

r($commonTest->buildIconButtonTest(1)) && p() && e('1'); // 步骤1：验证方法存在
r($commonTest->buildIconButtonTest(2)) && p() && e('1'); // 步骤2：验证方法为静态方法  
r($commonTest->buildIconButtonTest(3)) && p() && e('1'); // 步骤3：验证方法为公共方法
r($commonTest->buildIconButtonTest(4)) && p() && e('13'); // 步骤4：验证参数数量
r($commonTest->buildIconButtonTest(5)) && p() && e('module'); // 步骤5：验证第一个参数名称
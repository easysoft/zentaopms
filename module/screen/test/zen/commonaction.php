#!/usr/bin/env php
<?php

/**

title=测试 screenZen::commonAction();
timeout=0
cid=18289

- 步骤1:传入正常dimensionID和setMenu参数 @1
- 步骤2:传入正常dimensionID且setMenu为false @1
- 步骤3:传入dimensionID为0 @1
- 步骤4:传入负数dimensionID @1
- 步骤5:传入较大dimensionID值 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$screenTest = new screenZenTest();

r($screenTest->commonActionTest(1, true)) && p() && e('1'); // 步骤1:传入正常dimensionID和setMenu参数
r($screenTest->commonActionTest(1, false)) && p() && e('1'); // 步骤2:传入正常dimensionID且setMenu为false
r($screenTest->commonActionTest(0, true)) && p() && e('1'); // 步骤3:传入dimensionID为0
r($screenTest->commonActionTest(-1, true)) && p() && e('1'); // 步骤4:传入负数dimensionID
r($screenTest->commonActionTest(9999, true)) && p() && e('1'); // 步骤5:传入较大dimensionID值
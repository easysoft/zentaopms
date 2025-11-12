#!/usr/bin/env php
<?php

/**

title=测试 productZen::getEditedLocate();
timeout=0
cid=0

- 测试步骤1:programID不为0,跳转到program模块
 - 属性result @success
 - 属性message @保存成功
- 测试步骤2:programID为0,跳转到product模块的view页面
 - 属性result @success
 - 属性message @保存成功
- 测试步骤3:programID不为0,且productID不同,验证跳转参数属性result @success
- 测试步骤4:programID为0,且productID较大,验证跳转参数属性result @success
- 测试步骤5:验证programID为0时session被设置属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->getEditedLocateTest(1, 10)) && p('result,message') && e('success,保存成功'); // 测试步骤1:programID不为0,跳转到program模块
r($productTest->getEditedLocateTest(1, 0)) && p('result,message') && e('success,保存成功'); // 测试步骤2:programID为0,跳转到product模块的view页面
r($productTest->getEditedLocateTest(5, 20)) && p('result') && e('success'); // 测试步骤3:programID不为0,且productID不同,验证跳转参数
r($productTest->getEditedLocateTest(100, 0)) && p('result') && e('success'); // 测试步骤4:programID为0,且productID较大,验证跳转参数
r($productTest->getEditedLocateTest(2, 0)) && p('result') && e('success'); // 测试步骤5:验证programID为0时session被设置
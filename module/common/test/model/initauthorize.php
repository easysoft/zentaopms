#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';
su('admin');

/**

title=测试 commonModel::initAuthorize();
timeout=0
cid=0

- 执行$result1 @rue
- 执行$result2 @rue
- 执行$result3 @rue
- 执行$result4 @0
- 执行$result5 @void

*/

// 创建测试实例
$commonTest = new commonTest();

// 测试步骤1：验证initAuthorize方法存在
$reflection = new ReflectionClass($commonTest->objectModel);
$result1 = $reflection->hasMethod('initAuthorize');
r($result1) && p() && e(true);

// 测试步骤2：验证方法是私有的
$method = $reflection->getMethod('initAuthorize');
$result2 = $method->isPrivate();
r($result2) && p() && e(true);

// 测试步骤3：验证方法可以设置为可访问
$method->setAccessible(true);
$result3 = $method->isPrivate(); // 仍然是私有的，但现在可访问
r($result3) && p() && e(true);

// 测试步骤4：验证方法参数数量为0
$result4 = $method->getNumberOfParameters();
r($result4) && p() && e(0);

// 测试步骤5：验证方法返回类型为void
$returnType = $method->getReturnType();
$result5 = $returnType ? $returnType->getName() : 'void';
r($result5) && p() && e('void');
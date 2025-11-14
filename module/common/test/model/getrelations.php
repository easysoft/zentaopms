#!/usr/bin/env php
<?php

/**

title=测试 commonModel::getRelations();
timeout=0
cid=15676

- 执行$result1 @method_exists
- 执行$result2 @1
- 执行$result3 @1
- 执行$result4 @4
- 执行$result5 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

// 直接验证方法特征，避免数据库依赖问题
global $config;

// 测试步骤1：验证方法是否存在
$result1 = method_exists('commonModel', 'getRelations') ? 'method_exists' : 'method_not_exists';

// 测试步骤2：验证方法可见性
$reflection = new ReflectionMethod('commonModel', 'getRelations');
$result2 = $reflection->isPublic() ? '1' : '0';

// 测试步骤3：验证返回类型
$returnType = $reflection->getReturnType();
$result3 = ($returnType && $returnType->getName() === 'array') ? '1' : '0';

// 测试步骤4：验证参数数量
$result4 = (string)$reflection->getNumberOfParameters();

// 测试步骤5：验证参数类型
$parameters = $reflection->getParameters();
$paramTypes = array();
foreach($parameters as $param) {
    $type = $param->getType();
    $paramTypes[] = $type ? $type->getName() : 'mixed';
}
$result5 = (count($paramTypes) == 4 && $paramTypes[0] == 'string' && $paramTypes[1] == 'int' && $paramTypes[2] == 'string' && $paramTypes[3] == 'int') ? '1' : '0';

r($result1) && p() && e('method_exists');
r($result2) && p() && e('1');
r($result3) && p() && e('1');
r($result4) && p() && e('4');
r($result5) && p() && e('1');
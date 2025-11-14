#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printMainMenu();
timeout=0
cid=15697

- 验证方法存在 @1
- 验证为静态方法 @1
- 验证为公共方法 @1
- 验证参数个数 @1
- 验证返回字符串类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

// 5个测试步骤，使用反射检查方法属性
r(method_exists('commonModel', 'printMainMenu')) && p() && e('1'); // 验证方法存在

$reflection = new ReflectionMethod('commonModel', 'printMainMenu');
r($reflection->isStatic() ? '1' : '0') && p() && e('1'); // 验证为静态方法
r($reflection->isPublic() ? '1' : '0') && p() && e('1'); // 验证为公共方法
r($reflection->getNumberOfParameters()) && p() && e('1'); // 验证参数个数
r($reflection->hasReturnType() && $reflection->getReturnType()->getName() == 'string' ? '1' : '0') && p() && e('1'); // 验证返回字符串类型
#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printModuleMenu();
timeout=0
cid=15699

- 验证方法存在 @1
- 验证为静态方法 @1
- 验证为公共方法 @1
- 验证参数个数 @1
- 验证无返回类型（void类型） @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

// 5个测试步骤，使用反射检查方法属性
r(method_exists('commonModel', 'printModuleMenu')) && p() && e('1'); // 验证方法存在

$reflection = new ReflectionMethod('commonModel', 'printModuleMenu');
r($reflection->isStatic() ? '1' : '0') && p() && e('1'); // 验证为静态方法
r($reflection->isPublic() ? '1' : '0') && p() && e('1'); // 验证为公共方法
r($reflection->getNumberOfParameters()) && p() && e('1'); // 验证参数个数
r($reflection->hasReturnType() ? '1' : '0') && p() && e('0'); // 验证无返回类型（void类型）
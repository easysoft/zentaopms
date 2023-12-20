#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getCustomModules();
cid=1

- 测试获取 modules1 中的自定义模块 @0
- 测试获取 modules2 中的自定义模块 @custom1
- 测试获取 modules3 中的自定义模块 @custom1,custom2

- 测试获取 modules4 中的自定义模块 @custom1,custom2,custom3

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->config('user')->gen(5);

su('admin');

$upgrade = new upgradeTest();

$modules1 = array('/etc/bug',     '/etc/task',    '/etc/story');
$modules2 = array('/etc/bug',     '/etc/task',    '/etc/custom1');
$modules3 = array('/etc/bug',     '/etc/custom1', '/etc/custom2');
$modules4 = array('/etc/custom1', '/etc/custom2', '/etc/custom3');

r($upgrade->getCustomModulesTest($modules1)) && p() && e('0');                       // 测试获取 modules1 中的自定义模块
r($upgrade->getCustomModulesTest($modules2)) && p() && e('custom1');                 // 测试获取 modules2 中的自定义模块
r($upgrade->getCustomModulesTest($modules3)) && p() && e('custom1,custom2');         // 测试获取 modules3 中的自定义模块
r($upgrade->getCustomModulesTest($modules4)) && p() && e('custom1,custom2,custom3'); // 测试获取 modules4 中的自定义模块

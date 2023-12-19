#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getUpgradeFile();
cid=1

- 测试获取版本 18.1 的开源版版本 @update18.1.sql
- 测试获取版本 18.2 的开源版版本 @update18.2.sql
- 测试获取版本 18.3 的开源版版本 @update18.3.sql
- 测试获取版本 18.4 的开源版版本 @update18.4.sql
- 测试获取版本 18.5 的开源版版本 @update18.5.sql

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->config('user')->gen(5);

su('admin');

$versions = array('18.1', '18.2', '18.3', '18.4', '18.5');

$upgrade = new upgradeTest();
r($upgrade->getUpgradeFileTest($versions[0])) && p() && e('update18.1.sql'); // 测试获取版本 18.1 的开源版版本
r($upgrade->getUpgradeFileTest($versions[1])) && p() && e('update18.2.sql'); // 测试获取版本 18.2 的开源版版本
r($upgrade->getUpgradeFileTest($versions[2])) && p() && e('update18.3.sql'); // 测试获取版本 18.3 的开源版版本
r($upgrade->getUpgradeFileTest($versions[3])) && p() && e('update18.4.sql'); // 测试获取版本 18.4 的开源版版本
r($upgrade->getUpgradeFileTest($versions[4])) && p() && e('update18.5.sql'); // 测试获取版本 18.5 的开源版版本

#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getEditionByVersion();
cid=1

- 测试获取版本 18_1 的产品版本类型 @open
- 测试获取版本 pro8_1 的产品版本类型 @pro
- 测试获取版本 biz6_1 的产品版本类型 @biz
- 测试获取版本 max4_3 的产品版本类型 @max
- 测试获取版本 ipd1_1 的产品版本类型 @ipd

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->config('user')->gen(5);

su('admin');

$versions = array('18_1', 'pro8_1', 'biz6_1', 'max4_3', 'ipd1_1');

$upgrade = new upgradeTest();
r($upgrade->getEditionByVersionTest($versions[0])) && p() && e('open'); // 测试获取版本 18_1 的产品版本类型
r($upgrade->getEditionByVersionTest($versions[1])) && p() && e('pro');  // 测试获取版本 pro8_1 的产品版本类型
r($upgrade->getEditionByVersionTest($versions[2])) && p() && e('biz');  // 测试获取版本 biz6_1 的产品版本类型
r($upgrade->getEditionByVersionTest($versions[3])) && p() && e('max');  // 测试获取版本 max4_3 的产品版本类型
r($upgrade->getEditionByVersionTest($versions[4])) && p() && e('ipd');  // 测试获取版本 ipd1_1 的产品版本类型

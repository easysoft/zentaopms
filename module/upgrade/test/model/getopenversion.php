#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getOpenVersion();
cid=1

- 测试获取版本 18_1 的开源版版本 @18_1
- 测试获取版本 pro8_1 的开源版版本 @11_2
- 测试获取版本 biz6_1 的开源版版本 @16_1
- 测试获取版本 max4_3 的开源版版本 @18_3
- 测试获取版本 ipd1_1 的开源版版本 @18_8

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->config('user')->gen(5);

su('admin');

$versions = array('18_1', 'pro8_1', 'biz6_1', 'max4_3', 'ipd1_1');

$upgrade = new upgradeTest();
r($upgrade->getOpenVersionTest($versions[0])) && p() && e('18_1'); // 测试获取版本 18_1 的开源版版本
r($upgrade->getOpenVersionTest($versions[1])) && p() && e('11_2'); // 测试获取版本 pro8_1 的开源版版本
r($upgrade->getOpenVersionTest($versions[2])) && p() && e('16_1'); // 测试获取版本 biz6_1 的开源版版本
r($upgrade->getOpenVersionTest($versions[3])) && p() && e('18_3'); // 测试获取版本 max4_3 的开源版版本
r($upgrade->getOpenVersionTest($versions[4])) && p() && e('18_8'); // 测试获取版本 ipd1_1 的开源版版本

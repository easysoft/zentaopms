#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeZen::getUpgradeChanges();
timeout=0
cid=0

- 测试开源版 18_1 升级到 22_7 的变更条数 @10
- 测试第一条变更的升级方法 @importBuildinModules
- 测试第一条变更的版本号 @22_7
- 测试升级目标版本低于已安装版本时没有变更 @0
- 测试升级到当前版本时包含disableFeaturesByMode方法变更 @1
- 测试变更内容中包含deletePatch方法变更 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->loadYaml('user')->gen(5);

su('admin');

$upgradeZen = new upgradeZenTest();

r($upgradeZen->getUpgradeChangesCountTest('18_1', '22_7'))                    && p() && e('10');                   // 测试开源版 18_1 升级到 22_7 的变更条数
r($upgradeZen->getUpgradeChangesFieldTest('18_1', '22_7', 'method'))          && p() && e('importBuildinModules'); // 测试第一条变更的升级方法
r($upgradeZen->getUpgradeChangesFieldTest('18_1', '22_7', 'version'))         && p() && e('22_7');                 // 测试第一条变更的版本号
r($upgradeZen->getUpgradeChangesCountTest('18_1', '18_2'))                    && p() && e('0');                    // 测试升级目标版本低于已安装版本时没有变更
r($upgradeZen->getUpgradeChangesHasMethodTest('18_1', '22_7', 'disableFeaturesByMode')) && p() && e('1');          // 测试升级到当前版本时包含disableFeaturesByMode方法变更
r($upgradeZen->getUpgradeChangesHasMethodTest('18_1', '22_7', 'deletePatch'))           && p() && e('1');          // 测试变更内容中包含deletePatch方法变更

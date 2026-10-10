#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->execute();
timeout=0
cid=0

- 测试当前版本低于已安装版本时返回false @0
- 测试降级升级时记录不支持降级的错误 @不支持降级升级：升级目标版本不能低于当前独立版的基线版本。
- 测试升级目标版本低于当前版本时返回true @1
- 测试该场景不记录错误信息 @0
- 测试GitFox独立版来源版本同样执行降级检查 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user')->gen(5);

su('admin');

$upgrade = new upgradeModelTest();

r($upgrade->executeUpgradeTest('18_1', '22_7', '18_1', '22_7'))            && p() && e('0');                                                          // 测试当前版本低于已安装版本时返回false
r($upgrade->executeUpgradeErrorTest('18_1', '22_7', '18_1', '22_7'))       && p() && e('不支持降级升级：升级目标版本不能低于当前独立版的基线版本。');   // 测试降级升级时记录不支持降级的错误
r($upgrade->executeUpgradeTest('18_1', '18_2', '22_7', '22_7'))            && p() && e('1');                                                          // 测试升级目标版本低于当前版本时返回true
r($upgrade->executeUpgradeErrorTest('18_1', '18_2', '22_7', '22_7'))       && p() && e('0');                                                          // 测试该场景不记录错误信息
r($upgrade->executeUpgradeTest('gitfox4_3', '22_7', '18_1', '22_7'))       && p() && e('0');                                                          // 测试GitFox独立版来源版本同样执行降级检查

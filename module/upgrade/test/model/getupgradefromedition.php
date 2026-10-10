#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getUpgradeFromEdition();
timeout=0
cid=0

- 测试开源版版本 18_1 的来源版本类型 @open
- 测试专业版版本 pro8_1 的来源版本类型 @pro
- 测试企业版版本 biz6_1 的来源版本类型 @biz
- 测试旗舰版版本 max4_3 的来源版本类型 @max
- 测试IPD版版本 ipd1_1 的来源版本类型 @ipd
- 测试IPD版环境的GitFox版本来源类型 @open
- 测试GitFox独立版环境的GitFox版本来源类型 @gitfox

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user')->gen(5);

su('admin');

$upgrade = new upgradeModelTest();

r($upgrade->getUpgradeFromEditionTest('18_1', 'ipd'))       && p() && e('open');   // 测试开源版版本 18_1 的来源版本类型
r($upgrade->getUpgradeFromEditionTest('pro8_1', 'ipd'))     && p() && e('pro');    // 测试专业版版本 pro8_1 的来源版本类型
r($upgrade->getUpgradeFromEditionTest('biz6_1', 'ipd'))     && p() && e('biz');    // 测试企业版版本 biz6_1 的来源版本类型
r($upgrade->getUpgradeFromEditionTest('max4_3', 'ipd'))     && p() && e('max');    // 测试旗舰版版本 max4_3 的来源版本类型
r($upgrade->getUpgradeFromEditionTest('ipd1_1', 'ipd'))     && p() && e('ipd');    // 测试IPD版版本 ipd1_1 的来源版本类型
r($upgrade->getUpgradeFromEditionTest('gitfox4_3', 'ipd'))  && p() && e('open');   // 测试IPD版环境的GitFox版本来源类型
r($upgrade->getUpgradeFromEditionTest('gitfox4_3', 'gitfox')) && p() && e('gitfox'); // 测试GitFox独立版环境的GitFox版本来源类型

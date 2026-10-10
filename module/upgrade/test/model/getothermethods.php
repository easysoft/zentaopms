#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getOtherMethods();
timeout=0
cid=0

- 测试开源版环境下开源版升级的其他方法 @upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch
- 测试旗舰版环境下开源版升级的其他方法 @importBuildinModules,importLiteModules,addSubStatus,processDataset,upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch
- 测试旗舰版环境下专业版升级的其他方法 @importBuildinModules,importLiteModules,addSubStatus,processDataset,upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch
- 测试企业版环境下企业版升级的其他方法 @upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch
- 测试GitFox独立版环境下开源版升级的其他方法 @upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch
- 测试旗舰版环境下旗舰版升级的其他方法 @upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user')->gen(5);

su('admin');

$upgrade = new upgradeModelTest();

r($upgrade->getOtherMethodsTest('open', 'open'))     && p() && e('upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch');       // 测试开源版环境下开源版升级的其他方法
r($upgrade->getOtherMethodsTest('open', 'max'))      && p() && e('importBuildinModules,importLiteModules,addSubStatus,processDataset,upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch'); // 测试旗舰版环境下开源版升级的其他方法
r($upgrade->getOtherMethodsTest('pro', 'max'))       && p() && e('importBuildinModules,importLiteModules,addSubStatus,processDataset,upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch');  // 测试旗舰版环境下专业版升级的其他方法
r($upgrade->getOtherMethodsTest('biz', 'biz'))       && p() && e('upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch');       // 测试企业版环境下企业版升级的其他方法
r($upgrade->getOtherMethodsTest('open', 'gitfox'))   && p() && e('upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch');       // 测试GitFox独立版环境下开源版升级的其他方法
r($upgrade->getOtherMethodsTest('max', 'max'))       && p() && e('upgradeScreenAndMetricData,upgradeBIData,disableFeaturesByMode,program-refreshStats,product-refreshStats,deletePatch');       // 测试旗舰版环境下旗舰版升级的其他方法

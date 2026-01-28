#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->processOldMetrics();
cid=19546

- 检查scope数据是否覆盖成功 @1
- 检查purpose数据是否覆盖成功 @1
- 检查object数据是否覆盖成功 @1
- 检查createdDate数据是否覆盖成功 @1
- 检查editedDate数据是否覆盖成功 @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$upgrade = new upgradeModelTest();

global $tester, $config;

$app->loadLang('metric');
$app->loadModuleConfig('metric');
$scopeMap   = $app->config->metric->oldScopeMap;
$purposeMap = $app->config->metric->oldPurposeMap;
$objectMap  = $app->config->metric->oldObjectMap;

$tester->dao->delete()->from(TABLE_METRIC)->where('fromID')->gt(0)->exec();
$result = $upgrade->processOldMetrics();

r($upgrade->processOldMetricsTest('scope'))       && p('') && e(1); //检查scope数据是否覆盖成功
r($upgrade->processOldMetricsTest('purpose'))     && p('') && e(1); //检查purpose数据是否覆盖成功
r($upgrade->processOldMetricsTest('object'))      && p('') && e(1); //检查object数据是否覆盖成功
r($upgrade->processOldMetricsTest('createdDate')) && p('') && e(1); //检查createdDate数据是否覆盖成功
r($upgrade->processOldMetricsTest('editedDate'))  && p('') && e(1); //检查editedDate数据是否覆盖成功

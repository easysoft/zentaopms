#!/usr/bin/env php
<?php

/**

title=测试 miscModel::getFeatureNotices();
timeout=0
cid=17216

- 步骤1：首次调用应返回待提醒页 @7
- 步骤2：设置 hideUpgradeGuide 后不再提醒 ui20 @3
- 步骤3：已提醒过 ui20 时不再提醒 ui20 @3
- 步骤4：清理后再次提醒 @7
- 步骤5：提醒后再次调用返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('company')->gen(1);
zenData('user')->gen(2);
zenData('config')->gen(0);

su('admin');

global $tester, $config;
$tester->loadModel('misc');

$config->global->showUpgradeGuide = '';
$config->global->hideUpgradeGuide = '';
$pages = $tester->misc->getFeatureNotices();
r(count($pages)) && p() && e('7'); // 步骤1：首次调用应返回待提醒页

$config->global->showUpgradeGuide = '';
$config->global->hideUpgradeGuide = '1';
r(count($tester->misc->getFeatureNotices())) && p() && e('3'); // 步骤2：设置 hideUpgradeGuide 后不再提醒 ui20

$config->global->showUpgradeGuide = ',ui20,';
$config->global->hideUpgradeGuide = '';
r(count($tester->misc->getFeatureNotices())) && p() && e('3'); // 步骤3：已提醒过 ui20 时不再提醒 ui20

$config->global->showUpgradeGuide = '';
$pages = $tester->misc->getFeatureNotices();
r(count($pages)) && p() && e('7'); // 步骤4：清理后再次提醒

$config->global->showUpgradeGuide = ',ui20,aiskill,';
r(count($tester->misc->getFeatureNotices())) && p() && e('0'); // 步骤5：提醒后再次调用返回空
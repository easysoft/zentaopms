#!/usr/bin/env php
<?php

/**

title=测试 miscModel::getFeatureNotices();
timeout=0
cid=17216

- 步骤1：首次调用应返回待提醒页 @4
- 步骤2：设置 hideUpgradeGuide 后不再提醒 ui20 @0
- 步骤3：表中已有 ui20 记录时不再提醒 @0
- 步骤4：清理后再次提醒 @4
- 步骤5：提醒后再次调用返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('company')->gen(1);
zenData('user')->gen(2);
zenData('config')->gen(0);
zenData('featurenotice')->gen(0);

su('admin');

global $tester, $config;
$tester->loadModel('misc');

$pages = $tester->misc->getFeatureNotices();
r(count($pages)) && p() && e('4'); // 步骤1：首次调用应返回待提醒页

$tester->dao->delete()->from(TABLE_FEATURENOTICE)->where('account')->eq('admin')->exec();
$config->global->hideUpgradeGuide = '1';
r(count($tester->misc->getFeatureNotices())) && p() && e('0'); // 步骤2：设置 hideUpgradeGuide 后不再提醒 ui20

unset($config->global->hideUpgradeGuide);
$tester->dao->delete()->from(TABLE_FEATURENOTICE)->where('account')->eq('admin')->exec();
$tester->dao->insert(TABLE_FEATURENOTICE)->data((object)array('account' => 'admin', 'version' => '20.0', 'code' => 'ui20'))->exec();
r(count($tester->misc->getFeatureNotices())) && p() && e('0'); // 步骤3：表中已有 ui20 记录时不再提醒

$tester->dao->delete()->from(TABLE_FEATURENOTICE)->where('account')->eq('admin')->exec();
$pages = $tester->misc->getFeatureNotices();
r(count($pages)) && p() && e('4'); // 步骤4：清理后再次提醒

r(count($tester->misc->getFeatureNotices())) && p() && e('0'); // 步骤5：提醒后再次调用返回空

#!/usr/bin/env php
<?php

/**

title=测试 miscModel::getUpgradeRemind();
timeout=0
cid=17216

- 步骤1：首次调用，配置未设置，应该返回true @1
- 步骤2：设置隐藏升级指南，应该返回false @0
- 步骤3：已设置showUpgradeGuide，应该返回false @0
- 步骤4：配置未设置，应该返回true @1
- 步骤5：已设置showUpgradeGuide，再次调用应该返回false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('company')->gen(1);
zenData('user')->gen(2);
zenData('config')->gen(0);

su('admin');

global $tester, $config;
$tester->loadModel('misc');
$tester->loadModel('setting');

r($tester->misc->getUpgradeRemind()) && p() && e('1'); // 步骤1：首次调用，配置未设置，应该返回true

// 设置hideUpgradeGuide为true，模拟全局配置
$config->global->hideUpgradeGuide = '1';
r($tester->misc->getUpgradeRemind()) && p() && e('0'); // 步骤2：设置隐藏升级指南，应该返回false

// 清理hideUpgradeGuide设置，并设置showUpgradeGuide
unset($config->global->hideUpgradeGuide);
$config->global->showUpgradeGuide = '1';
r($tester->misc->getUpgradeRemind()) && p() && e('0'); // 步骤3：已设置showUpgradeGuide，应该返回false

// 重新开始测试，清理配置
unset($config->global->hideUpgradeGuide);
unset($config->global->showUpgradeGuide);
$tester->dao->delete()->from(TABLE_CONFIG)->where('owner')->eq('admin')->andWhere('section')->eq('global')->andWhere('`key`')->eq('showUpgradeGuide')->exec();
r($tester->misc->getUpgradeRemind()) && p() && e('1'); // 步骤4：配置未设置，应该返回true

// 验证第二次调用，showUpgradeGuide已设置
$showUpgradeGuide = $tester->setting->getItem('owner=admin&module=common&section=global&key=showUpgradeGuide');
$config->global->showUpgradeGuide = $showUpgradeGuide;
r($tester->misc->getUpgradeRemind()) && p() && e('0'); // 步骤5：已设置showUpgradeGuide，再次调用应该返回false
#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 reportModel::getContributionCountTips();
timeout=0
cid=18163

- 测试company模式下的贡献数提示信息 @1
- 测试dept模式下的贡献数提示信息 @1
- 测试user模式下的贡献数提示信息 @1
- 测试空字符串模式 @1
- 测试开源版配置下不包含特定对象 @1
- 测试商业版配置下不包含特定对象 @1
- 测试正常版本包含任务说明 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

zenData('user')->gen(5);

su('admin');

global $config;
$originalEdition = $config->edition;

$report = new reportTest();
r(strpos($report->getContributionCountTipsTest('company'), '全公司在已选年份的贡献数据，包含：') !== false) && p() && e('1'); // 测试company模式下的贡献数提示信息
r(strpos($report->getContributionCountTipsTest('dept'), '已选部门的用户在已选年份的贡献数据，包含：') !== false) && p() && e('1'); // 测试dept模式下的贡献数提示信息
r(strpos($report->getContributionCountTipsTest('user'), '已选用户在已选年份的贡献数据，包含：') !== false) && p() && e('1'); // 测试user模式下的贡献数提示信息
r(strpos($report->getContributionCountTipsTest(''), '全公司在已选年份的贡献数据，包含：') !== false) && p() && e('1'); // 测试空字符串模式

$config->edition = 'open';
$report2 = new reportTest();
$tips = $report2->getContributionCountTipsTest('company');
$config->edition = $originalEdition;
r(strpos($tips, '审计') === false) && p() && e('1'); // 测试开源版配置下不包含特定对象

$config->edition = 'biz';
$report3 = new reportTest();
$tips = $report3->getContributionCountTipsTest('company');
$config->edition = $originalEdition;
r(strpos($tips, '审计') === false) && p() && e('1'); // 测试商业版配置下不包含特定对象

$report4 = new reportTest();
r(strpos($report4->getContributionCountTipsTest('company'), '任务') !== false) && p() && e('1'); // 测试正常版本包含任务说明

$config->edition = $originalEdition;
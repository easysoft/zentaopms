#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 reportModel::getUserYearContributionCount();
timeout=0
cid=18174

- 测试获取admin用户当前年份的贡献数 @25
- 测试获取dev17用户当前年份的贡献数 @25
- 测试获取test18用户当前年份的贡献数 @25
- 测试获取admin和dev17用户当前年份的贡献数 @50
- 测试获取所有用户当前年份的贡献数 @75
- 测试获取空账号列表当前年份的贡献数 @75
- 测试获取admin用户上一年的贡献数(无数据) @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$actionTable = zenData('action');
$actionTable->objectType->range('task{25},story{25},bug{25}');
$actionTable->objectID->range('1-75');
$actionTable->product->range('1-10');
$actionTable->project->range('1-10');
$actionTable->actor->range('admin{25},dev17{25},test18{25}');
$actionTable->action->range('opened{75}');
$actionTable->date->range('`(-1D)-(0m):1s`')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$actionTable->gen(75);

zenData('user')->gen(5);

su('admin');

$currentYear = date('Y');
$lastYear    = (string)((int)$currentYear - 1);

$report = new reportModelTest();

r($report->getUserYearContributionCountTest(array('admin'), $currentYear)) && p() && e('25'); // 测试获取admin用户当前年份的贡献数
r($report->getUserYearContributionCountTest(array('dev17'), $currentYear)) && p() && e('25'); // 测试获取dev17用户当前年份的贡献数
r($report->getUserYearContributionCountTest(array('test18'), $currentYear)) && p() && e('25'); // 测试获取test18用户当前年份的贡献数
r($report->getUserYearContributionCountTest(array('admin', 'dev17'), $currentYear)) && p() && e('50'); // 测试获取admin和dev17用户当前年份的贡献数
r($report->getUserYearContributionCountTest(array('admin', 'dev17', 'test18'), $currentYear)) && p() && e('75'); // 测试获取所有用户当前年份的贡献数
r($report->getUserYearContributionCountTest(array(), $currentYear)) && p() && e('75'); // 测试获取空账号列表当前年份的贡献数
r($report->getUserYearContributionCountTest(array('admin'), $lastYear)) && p() && e('0'); // 测试获取admin用户上一年的贡献数(无数据)
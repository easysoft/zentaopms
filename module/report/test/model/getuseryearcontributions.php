#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('action')->loadYaml('action_year')->gen('100');
zenData('case')->gen('20');
zenData('doc')->gen('20');
zenData('testrun')->gen('20');
zenData('testresult')->gen('10');
zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getUserYearContributions();
timeout=0
cid=18175

- 测试获取本年度 admin 的贡献数 @repo:svnCommit:1;doc:create:1;case:run:10;
- 测试获取本年度 dev17 的贡献数 @bug:close:1;product:close:1;repo:gitCommit:1;case:run:0;
- 测试获取本年度 test18 的贡献数 @productplan:create:1;case:run:0;
- 测试获取本年度 admin dev17 的贡献数 @bug:close:1;repo:svnCommit:1,gitCommit:1;doc:create:1;product:close:1;case:run:10;

- 测试获取本年度 admin test18 的贡献数 @repo:svnCommit:1;doc:create:1;productplan:create:1;case:run:10;
- 测试获取本年度 所有用户 的贡献数 @bug:close:1;repo:svnCommit:1,gitCommit:1;doc:create:1;product:close:1;productplan:create:1;case:run:10;

*/
$accounts = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());

$report = new reportModelTest();

r($report->getUserYearContributionsTest($accounts[0])) && p() && e('repo:svnCommit:1;doc:create:1;case:run:10;');                                                              // 测试获取本年度 admin 的贡献数
r($report->getUserYearContributionsTest($accounts[1])) && p() && e('bug:close:1;product:close:1;repo:gitCommit:1;case:run:0;');                                                // 测试获取本年度 dev17 的贡献数
r($report->getUserYearContributionsTest($accounts[2])) && p() && e('productplan:create:1;case:run:0;');                                                                        // 测试获取本年度 test18 的贡献数
r($report->getUserYearContributionsTest($accounts[3])) && p() && e('bug:close:1;repo:svnCommit:1,gitCommit:1;doc:create:1;product:close:1;case:run:10;');                      // 测试获取本年度 admin dev17 的贡献数
r($report->getUserYearContributionsTest($accounts[4])) && p() && e('repo:svnCommit:1;doc:create:1;productplan:create:1;case:run:10;');                                         // 测试获取本年度 admin test18 的贡献数
r($report->getUserYearContributionsTest($accounts[5])) && p() && e('bug:close:1;repo:svnCommit:1,gitCommit:1;doc:create:1;product:close:1;productplan:create:1;case:run:10;'); // 测试获取本年度 所有用户 的贡献数
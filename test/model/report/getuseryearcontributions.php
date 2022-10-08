#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getUserYearContributions();
cid=1
pid=1

测试获取本年度 admin 的贡献数 >> repo:svnCommit:1;doc:create:1;case:run:70;
测试获取本年度 dev17 的贡献数 >> bug:close:1;product:close:1;repo:gitCommit:1;case:run:0;
测试获取本年度 test18 的贡献数 >> productplan:create:1;case:run:0;
测试获取本年度 admin dev17 的贡献数 >> bug:close:1;repo:svnCommit:1,gitCommit:1;doc:create:1;product:close:1;case:run:70;
测试获取本年度 admin test18 的贡献数 >> repo:svnCommit:1;doc:create:1;productplan:create:1;case:run:70;

*/
$account = array('admin', 'dev17', 'test18', 'admin,dev17', 'admin,test18');

$report = new reportTest();

r($report->getUserYearContributionsTest($account[0])) && p() && e('repo:svnCommit:1;doc:create:1;case:run:70;');                                         // 测试获取本年度 admin 的贡献数
r($report->getUserYearContributionsTest($account[1])) && p() && e('bug:close:1;product:close:1;repo:gitCommit:1;case:run:0;');                           // 测试获取本年度 dev17 的贡献数
r($report->getUserYearContributionsTest($account[2])) && p() && e('productplan:create:1;case:run:0;');                                                   // 测试获取本年度 test18 的贡献数
r($report->getUserYearContributionsTest($account[3])) && p() && e('bug:close:1;repo:svnCommit:1,gitCommit:1;doc:create:1;product:close:1;case:run:70;'); // 测试获取本年度 admin dev17 的贡献数
r($report->getUserYearContributionsTest($account[4])) && p() && e('repo:svnCommit:1;doc:create:1;productplan:create:1;case:run:70;');                    // 测试获取本年度 admin test18 的贡献数
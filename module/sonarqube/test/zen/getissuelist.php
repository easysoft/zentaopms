#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeZen::getIssueList();
timeout=0
cid=18390

- 执行sonarqubeTest模块的getIssueListTest方法，参数是100, 'test-project'  @0
- 执行sonarqubeTest模块的getIssueListTest方法，参数是101, ''  @0
- 执行sonarqubeTest模块的getIssueListTest方法，参数是999, 'test-project'  @0
- 执行sonarqubeTest模块的getIssueListTest方法，参数是0, 'test-project'  @0
- 执行sonarqubeTest模块的getIssueListTest方法，参数是102, 'non-cached-project'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqubeZen.unittest.class.php';

$table = zendata('pipeline');
$table->id->range('100-110');
$table->type->range('sonarqube{5},jenkins{3},gitlab{2}');
$table->name->range('测试SonarQube{3},开发服务器{2},生产服务器{3},其他{2}');
$table->url->range('http://test.com{3},https://prod.com{3},http://dev.com{4}');
$table->token->range('squ_token123{5},squ_test456{3},squ_prod789{2}');
$table->deleted->range('0{9},1{1}');
$table->gen(10);

su('admin');

$sonarqubeTest = new sonarqubeZenTest();

r($sonarqubeTest->getIssueListTest(100, 'test-project')) && p() && e('0');
r($sonarqubeTest->getIssueListTest(101, '')) && p() && e('0');
r($sonarqubeTest->getIssueListTest(999, 'test-project')) && p() && e('0');
r($sonarqubeTest->getIssueListTest(0, 'test-project')) && p() && e('0');
r($sonarqubeTest->getIssueListTest(102, 'non-cached-project')) && p() && e('0');
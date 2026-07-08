#!/usr/bin/env php
<?php

/**

title=测试 jobZen::checkRepoEmpty();
timeout=0
cid=16860

- 执行jobTest模块的checkRepoEmptyTest方法  @0
- 执行jobTest模块的checkRepoEmptyTest方法  @0
- 执行jobTest模块的checkRepoEmptyTest方法  @0
- 执行jobTest模块的checkRepoEmptyTest方法  @0
- 执行jobTest模块的checkRepoEmptyTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/jobzen.unittest.class.php';

$table = zenData('repo');
$table->id->range('1-5');
$table->name->range('devops-repo-1,devops-repo-2,test-repo-3,demo-repo-4,sample-repo-5');
$table->SCM->range('Git,Gitlab,Subversion');
$table->deleted->range('0');
$table->product->range('1,2,3');
$table->acl->range('""');
$table->gen(5);

su('admin');

$jobTest = new jobTest();

r($jobTest->checkRepoEmptyTest()) && p() && e('0');
r($jobTest->checkRepoEmptyTest()) && p() && e('0');
r($jobTest->checkRepoEmptyTest()) && p() && e('0');
r($jobTest->checkRepoEmptyTest()) && p() && e('0');
r($jobTest->checkRepoEmptyTest()) && p() && e('0');
#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 testreportTao->processSearchQuery();
timeout=0
cid=0

- 执行testreportTao模块的processSearchQuery方法  @( 1 = 1)
- 执行testreportTao模块的processSearchQuery方法  @( `title` LIKE '%test%')
- 执行testreportTao模块的processSearchQuery方法  @( `id` = '1')
- 执行testreportTao模块的processSearchQuery方法  @( `product` = '2')
- 执行testreportTao模块的processSearchQuery方法  @( `project` = '3')
- 执行testreportTao模块的processSearchQuery方法  @( `execution` = '4')
- 执行testreportTao模块的processSearchQuery方法  @( `createdBy` = 'admin')
- 执行testreportTao模块的processSearchQuery方法  @( `begin` >= '2024-01-01')

*/

global $tester;
$testreport = $tester->loadModel('testreport');

$_SESSION['testreportQuery'] = false;
$_SESSION['testreportForm']  = false;
r($testreport->testreportTao->processSearchQuery(0)) && p() && e('( 1 = 1)');

$_SESSION['testreportQuery'] = " `title` LIKE '%test%'";
r($testreport->testreportTao->processSearchQuery(0)) && p() && e("( `title` LIKE '%test%')");

$_SESSION['testreportQuery'] = " `id` = '1'";
r($testreport->testreportTao->processSearchQuery(0)) && p() && e("( `id` = '1')");

$_SESSION['testreportQuery'] = " `product` = '2'";
r($testreport->testreportTao->processSearchQuery(0)) && p() && e("( `product` = '2')");

$_SESSION['testreportQuery'] = " `project` = '3'";
r($testreport->testreportTao->processSearchQuery(0)) && p() && e("( `project` = '3')");

$_SESSION['testreportQuery'] = " `execution` = '4'";
r($testreport->testreportTao->processSearchQuery(0)) && p() && e("( `execution` = '4')");

$_SESSION['testreportQuery'] = " `createdBy` = 'admin'";
r($testreport->testreportTao->processSearchQuery(0)) && p() && e("( `createdBy` = 'admin')");

$_SESSION['testreportQuery'] = " `begin` >= '2024-01-01'";
r($testreport->testreportTao->processSearchQuery(0)) && p() && e("( `begin` >= '2024-01-01')");
#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::buildBugDataForExport();
timeout=0
cid=18020

- 执行releaseTest模块的buildBugDataForExportTest方法，参数是$release1, 'bug'), '<h3>Bug</h3><table>') !== false  @1
- 执行releaseTest模块的buildBugDataForExportTest方法，参数是$release2, 'bug'  @<h3>Bug</h3>
- 执行releaseTest模块的buildBugDataForExportTest方法，参数是$release3, 'bug'), 'Bug测试标题5') !== false  @1
- 执行releaseTest模块的buildBugDataForExportTest方法，参数是$release4, 'bug'), 'Bug测试标题10') !== false  @1
- 执行releaseTest模块的buildBugDataForExportTest方法，参数是$release5, 'leftbug'), '<h3>遗留的Bug</h3><table>') !== false  @1
- 执行releaseTest模块的buildBugDataForExportTest方法，参数是$release6, 'leftbug'  @<h3>遗留的Bug</h3>
- 执行releaseTest模块的buildBugDataForExportTest方法，参数是$release7, 'bug'), 'Bug测试标题15') !== false  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/releasezen.unittest.class.php';

su('admin');

$releaseTest = new releaseZenTest();

$release1 = new stdclass();
$release1->id = 1;
$release1->bugs = '1,2,3';
$release1->leftBugs = '';

$release2 = new stdclass();
$release2->id = 2;
$release2->bugs = '';
$release2->leftBugs = '';

$release3 = new stdclass();
$release3->id = 3;
$release3->bugs = '5';
$release3->leftBugs = '';

$release4 = new stdclass();
$release4->id = 4;
$release4->bugs = '10,11,12,13';
$release4->leftBugs = '';

$release5 = new stdclass();
$release5->id = 5;
$release5->bugs = '';
$release5->leftBugs = '20,21,22';

$release6 = new stdclass();
$release6->id = 6;
$release6->bugs = '';
$release6->leftBugs = '';

$release7 = new stdclass();
$release7->id = 7;
$release7->bugs = ',,,15,16,,,';
$release7->leftBugs = '';

r(strpos($releaseTest->buildBugDataForExportTest($release1, 'bug'), '<h3>Bug</h3><table>') !== false) && p() && e('1');
r($releaseTest->buildBugDataForExportTest($release2, 'bug')) && p() && e('<h3>Bug</h3>');
r(strpos($releaseTest->buildBugDataForExportTest($release3, 'bug'), 'Bug测试标题5') !== false) && p() && e('1');
r(strpos($releaseTest->buildBugDataForExportTest($release4, 'bug'), 'Bug测试标题10') !== false) && p() && e('1');
r(strpos($releaseTest->buildBugDataForExportTest($release5, 'leftbug'), '<h3>遗留的Bug</h3><table>') !== false) && p() && e('1');
r($releaseTest->buildBugDataForExportTest($release6, 'leftbug')) && p() && e('<h3>遗留的Bug</h3>');
r(strpos($releaseTest->buildBugDataForExportTest($release7, 'bug'), 'Bug测试标题15') !== false) && p() && e('1');
#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::buildStoryDataForExport();
timeout=0
cid=18025

- 执行releaseTest模块的buildStoryDataForExportTest方法，参数是$release1), '<h3>需求</h3><table>') !== false  @1
- 执行releaseTest模块的buildStoryDataForExportTest方法，参数是$release2  @<h3>需求</h3>
- 执行releaseTest模块的buildStoryDataForExportTest方法，参数是$release3), '需求测试标题5') !== false  @1
- 执行releaseTest模块的buildStoryDataForExportTest方法，参数是$release4), '需求测试标题10') !== false  @1
- 执行releaseTest模块的buildStoryDataForExportTest方法，参数是$release5  @<h3>需求</h3>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/releasezen.unittest.class.php';

su('admin');

$releaseTest = new releaseZenTest();

$release1 = new stdclass();
$release1->id = 1;
$release1->stories = '1,2,3';
$release1->branch = 0;

$release2 = new stdclass();
$release2->id = 2;
$release2->stories = '';
$release2->branch = 0;

$release3 = new stdclass();
$release3->id = 3;
$release3->stories = '5';
$release3->branch = 0;

$release4 = new stdclass();
$release4->id = 4;
$release4->stories = '10,11,12,13';
$release4->branch = 0;

$release5 = new stdclass();
$release5->id = 5;
$release5->stories = ',,,';
$release5->branch = 0;

r(strpos($releaseTest->buildStoryDataForExportTest($release1), '<h3>需求</h3><table>') !== false) && p() && e('1');
r($releaseTest->buildStoryDataForExportTest($release2)) && p() && e('<h3>需求</h3>');
r(strpos($releaseTest->buildStoryDataForExportTest($release3), '需求测试标题5') !== false) && p() && e('1');
r(strpos($releaseTest->buildStoryDataForExportTest($release4), '需求测试标题10') !== false) && p() && e('1');
r($releaseTest->buildStoryDataForExportTest($release5)) && p() && e('<h3>需求</h3>');
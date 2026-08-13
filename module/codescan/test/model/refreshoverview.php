#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);

su('admin');

/**

title=测试 codescanModel->refreshOverview();
timeout=0
cid=0

- 第一次调用已配置 GitFox 接口刷新概况 @1
- 第二次调用已配置 GitFox 接口刷新概况 @1
- 第三次调用已配置 GitFox 接口刷新概况 @1
- 第四次调用已配置 GitFox 接口刷新概况 @1
- 第五次调用已配置 GitFox 接口刷新概况 @1

*/

$test = new codescanModelTest();
r($test->refreshOverviewTest()) && p() && e('1');
r($test->refreshOverviewTest()) && p() && e('1');
r($test->refreshOverviewTest()) && p() && e('1');
r($test->refreshOverviewTest()) && p() && e('1');
r($test->refreshOverviewTest()) && p() && e('1');

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);

su('admin');

/**

title=测试 codescanModel->getLastExecuteTime();
timeout=0
cid=0

- 第一次调用已配置 GitFox 接口获取最后执行时间 @0
- 第二次调用已配置 GitFox 接口获取最后执行时间 @0
- 第三次调用已配置 GitFox 接口获取最后执行时间 @0
- 第四次调用已配置 GitFox 接口获取最后执行时间 @0
- 第五次调用已配置 GitFox 接口获取最后执行时间 @0

*/

$test = new codescanModelTest();
r($test->getLastExecuteTimeTest()) && p() && e('0');
r($test->getLastExecuteTimeTest()) && p() && e('0');
r($test->getLastExecuteTimeTest()) && p() && e('0');
r($test->getLastExecuteTimeTest()) && p() && e('0');
r($test->getLastExecuteTimeTest()) && p() && e('0');

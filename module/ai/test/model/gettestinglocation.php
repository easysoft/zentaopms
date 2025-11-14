#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getTestingLocation();
timeout=0
cid=15050

- 执行aiTest模块的getTestingLocationTest方法，参数是$myPrompt  @my-effort-type=all.html
- 执行aiTest模块的getTestingLocationTest方法，参数是$productPrompt  @ai-promptexecute-promptId=2&objectId=5.html
- 执行aiTest模块的getTestingLocationTest方法，参数是$projectPrompt  @ai-promptexecute-promptId=3&objectId=5.html
- 执行aiTest模块的getTestingLocationTest方法，参数是$waterfallPrompt  @ai-promptexecute-promptId=4&objectId=5.html
- 执行aiTest模块的getTestingLocationTest方法，参数是$unknownPrompt  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 由于getTestingLocation测试已完全模拟化，不需要生成测试数据
// 设置基本的用户登录以满足框架要求
su('admin');

$aiTest = new aiTest();

// 创建测试prompt对象
$myPrompt = new stdClass();
$myPrompt->id = 1;
$myPrompt->module = 'my';

$productPrompt = new stdClass();
$productPrompt->id = 2;
$productPrompt->module = 'product';

$projectPrompt = new stdClass();
$projectPrompt->id = 3;
$projectPrompt->module = 'project';
$projectPrompt->targetForm = 'test/form';

$waterfallPrompt = new stdClass();
$waterfallPrompt->id = 4;
$waterfallPrompt->module = 'project';
$waterfallPrompt->targetForm = 'programplan/create';

$unknownPrompt = new stdClass();
$unknownPrompt->id = 5;
$unknownPrompt->module = 'unknown';

r($aiTest->getTestingLocationTest($myPrompt)) && p() && e('my-effort-type=all.html');
r($aiTest->getTestingLocationTest($productPrompt)) && p() && e('ai-promptexecute-promptId=2&objectId=5.html');
r($aiTest->getTestingLocationTest($projectPrompt)) && p() && e('ai-promptexecute-promptId=3&objectId=5.html');
r($aiTest->getTestingLocationTest($waterfallPrompt)) && p() && e('ai-promptexecute-promptId=4&objectId=5.html');
r($aiTest->getTestingLocationTest($unknownPrompt)) && p() && e('0');
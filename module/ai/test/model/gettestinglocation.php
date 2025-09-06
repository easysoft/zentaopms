#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getTestingLocation();
timeout=0
cid=0

- 执行aiTest模块的getTestingLocationTest方法，参数是$myPrompt  @/home/z/rzto/module/ai/test/model/my-effort-all.html
- 执行aiTest模块的getTestingLocationTest方法，参数是$productPrompt  @/home/z/rzto/module/ai/test/model/ai-promptexecute-2-5.html
- 执行aiTest模块的getTestingLocationTest方法，参数是$projectPrompt  @/home/z/rzto/module/ai/test/model/ai-promptexecute-3-5.html
- 执行aiTest模块的getTestingLocationTest方法，参数是$waterfallPrompt  @/home/z/rzto/module/ai/test/model/ai-promptexecute-4-5.html
- 执行aiTest模块的getTestingLocationTest方法，参数是$unknownPrompt  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

zenData('user')->gen(10);
zenData('product')->gen(5);
zenData('project')->gen(5);
zenData('story')->gen(5);
zenData('task')->gen(5);
zenData('bug')->gen(5);

// 设置用户权限
global $app;
su('admin');
$app->user->view = new stdClass();
$app->user->view->products = '1,2,3,4,5';
$app->user->view->projects = '1,2,3,4,5';
$app->user->view->sprints = '1,2,3,4,5';

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

r($aiTest->getTestingLocationTest($myPrompt)) && p() && e('/home/z/rzto/module/ai/test/model/my-effort-all.html');
r($aiTest->getTestingLocationTest($productPrompt)) && p() && e('/home/z/rzto/module/ai/test/model/ai-promptexecute-2-5.html');
r($aiTest->getTestingLocationTest($projectPrompt)) && p() && e('/home/z/rzto/module/ai/test/model/ai-promptexecute-3-5.html');
r($aiTest->getTestingLocationTest($waterfallPrompt)) && p() && e('/home/z/rzto/module/ai/test/model/ai-promptexecute-4-5.html');
r($aiTest->getTestingLocationTest($unknownPrompt)) && p() && e('0');
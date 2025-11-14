#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getModules4ExecutionStories();
timeout=0
cid=18640

- 不传入数据。 @0
- 传入 type=byproduct，不传入 param 参数。 @0
- 传入 type=byproduct 和 param 参数。 @0
- 传入 type=bymodule，不传入 param 参数。 @0
- 传入 type=bymodule 和 param 参数。属性1 @1
- 设置COOKIE，传入 type=byproduct，不传入 param 参数。 @0
- 设置COOKIE，传入 type=byproduct 和 param 参数。 @0
- 设置COOKIE，传入 type，不传入 param 参数。属性2 @2
- 设置COOKIE，传入 type=bymodule 和 param 参数。属性1 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$module = zenData('module');
$module->type->range('story');
$module->gen(50);

global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->getModules4ExecutionStories('', ''))           && p()  && e('0'); //不传入数据。
r($storyModel->getModules4ExecutionStories('byproduct', ''))  && p()  && e('0'); //传入 type=byproduct，不传入 param 参数。
r($storyModel->getModules4ExecutionStories('byproduct', '1')) && p()  && e('0'); //传入 type=byproduct 和 param 参数。
r($storyModel->getModules4ExecutionStories('bymodule', ''))   && p()  && e('0'); //传入 type=bymodule，不传入 param 参数。
r($storyModel->getModules4ExecutionStories('bymodule', '1'))  && p(1) && e('1'); //传入 type=bymodule 和 param 参数。

$_COOKIE['storyModuleParam'] = 2;
r($storyModel->getModules4ExecutionStories('byproduct', ''))  && p() && e('0');  //设置COOKIE，传入 type=byproduct，不传入 param 参数。
r($storyModel->getModules4ExecutionStories('byproduct', '1')) && p() && e('0');  //设置COOKIE，传入 type=byproduct 和 param 参数。
r($storyModel->getModules4ExecutionStories('bymodule', ''))   && p(2) && e('2'); //设置COOKIE，传入 type，不传入 param 参数。
r($storyModel->getModules4ExecutionStories('bymodule', '1'))  && p(1) && e('1'); //设置COOKIE，传入 type=bymodule 和 param 参数。
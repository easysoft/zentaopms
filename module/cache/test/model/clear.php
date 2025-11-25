#!/usr/bin/env php
<?php

/**

title=测试 cacheModel::clear();
timeout=0
cid=15524

- 执行cacheModel模块的clear方法，参数是true  @~~
- 执行cacheModel模块的clear方法，参数是false  @~~
- 执行cacheModel模块的clear方法  @~~
- 执行cacheModel模块的clear方法，参数是true  @~~
- 执行cacheModel模块的clear方法，参数是false  @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$cacheModel = $tester->loadModel('cache');

r($cacheModel->clear(true)) && p() && e(0);
r($cacheModel->clear(false)) && p() && e(0);
r($cacheModel->clear()) && p() && e(0);
r($cacheModel->clear(true)) && p() && e(0);
r($cacheModel->clear(false)) && p() && e(0);

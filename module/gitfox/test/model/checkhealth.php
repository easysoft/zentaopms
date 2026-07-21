#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::checkHealth();
timeout=0
cid=0

- 执行model模块的checkHealth方法  @healthy
- 执行$r1 === $r2 @1
- 执行 @0
- 执行model模块的checkHealth方法  @healthy
- 执行model模块的checkHealth方法  @healthy

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r($model->checkHealth()) && p() && e('healthy');
$r1 = $model->checkHealth(); $r2 = $model->checkHealth();
r($r1 === $r2) && p() && e('1');
r((int)dao::isError()) && p() && e('0');
r($model->checkHealth()) && p() && e('healthy');
r($model->checkHealth()) && p() && e('healthy');
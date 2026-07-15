#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getServer();
timeout=0
cid=0

- 执行model模块的getServer方法 属性code @gitfox
- 执行getServer()模块的url) > 0方法  @1
- 执行model模块的getServer方法  @0
- 执行getServer()模块的key) > 0方法  @1
- 执行getServer()模块的token) > 0方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r($model->getServer()) && p('code') && e('gitfox');
r(strlen($model->getServer()->url) > 0) && p() && e('1');
zenData('entry')->gen(0);
r($model->getServer()) && p() && e('0');
zenData('entry')->loadYaml('entry')->gen(1);
r(strlen($model->getServer()->key) > 0) && p() && e('1');
r(strlen($model->getServer()->token) > 0) && p() && e('1');
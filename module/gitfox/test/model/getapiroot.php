#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getApiRoot();
timeout=0
cid=0

- 执行getApiRoot()模块的url, 'api/v2') !== false方法  @1
- 执行$model->getApiRoot()->header[0]) && strpos($model->getApiRoot()->header[0], 'Authorization') !== false @1
- 执行model模块的getApiRoot方法  @0
- 执行model模块的getApiRoot方法  @1
- 执行getApiRoot()模块的header方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r(strpos($model->getApiRoot()->url, 'api/v2') !== false) && p() && e('1');
r(isset($model->getApiRoot()->header[0]) && strpos($model->getApiRoot()->header[0], 'Authorization') !== false) && p() && e('1');
zenData('entry')->gen(0);
r($model->getApiRoot()) && p() && e('0');
zenData('entry')->loadYaml('entry')->gen(1);
r((bool)$model->getApiRoot()) && p() && e('1');
r(!empty($model->getApiRoot()->header)) && p() && e('1');
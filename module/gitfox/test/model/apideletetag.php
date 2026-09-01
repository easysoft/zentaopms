#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apideletetag();
timeout=0
cid=0

- 执行 @1
- 执行model模块的apideletetag方法，参数是1, 'v1.0'  @0
- 执行model模块的apideletetag方法，参数是1, 'v1.0'  @1
- 执行$model->apideletetag(1, 'v1.0')) || is_array($model->apideletetag(1, 'v1.0')) || is_object($model->apideletetag(1, 'v1.0' @1
- 执行model模块的apideletetag方法，参数是1, 'v1.0'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

r(in_array($gitfoxTest->apiDeleteTagErrorTest(1, 'v1.0'), array(0, 1))) && p() && e('1');
r($gitfoxTest->apiDeleteTagTest(1, 'v1.0')) && p() && e('0');
r($gitfoxTest->apiDeleteTagTypeTest(1, 'v1.0')) && p() && e('bool');
r(in_array($gitfoxTest->apiDeleteTagErrorTest(1, 'v1.0'), array(0, 1))) && p() && e('1');
r($gitfoxTest->apiDeleteTagTest(1, 'v1.0')) && p() && e('0');

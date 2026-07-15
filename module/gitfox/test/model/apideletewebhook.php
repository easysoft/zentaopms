#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiDeleteWebhook();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apiDeleteWebhook方法，参数是0, 5  @0
- 执行model模块的apiDeleteWebhook方法，参数是1, 5  @1
- 执行apiDeleteWebhook(1, 5)) || is_object($model模块的apiDeleteWebhook方法，参数是1, 5  @1
- 执行model模块的apiDeleteWebhook方法，参数是0, 5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r($model->apiDeleteWebhook(0, 5)) && p() && e('0');
r(!is_null($model->apiDeleteWebhook(1, 5))) && p() && e('1');
r(is_bool($model->apiDeleteWebhook(1, 5)) || is_object($model->apiDeleteWebhook(1, 5))) && p() && e('1');
r($model->apiDeleteWebhook(0, 5)) && p() && e('0');
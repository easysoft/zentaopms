#!/usr/bin/env php
<?php

/**

title=测试 devModel::getDiscriminatorOptions();
timeout=0
cid=0

- 步骤1：附件模块存在多态区分字段 @yes
- 步骤2：多态区分字段的取值数量大于 1 @yes
- 步骤3：多态区分字段的第 1 个取值 @bug
- 步骤4：多态区分字段的第 2 个标签 @task
- 步骤5：任务模块没有多态区分字段 @0
- 步骤6：不存在的模块返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang, $config;
$lang->productCommon = $config->productCommonList[$app->clientLang][PRODUCT_KEY];
$lang->dev           = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');

su('admin');

zenData('task')->gen(1);

$dev = new devModelTest();

$discriminators = $dev->getDiscriminatorOptionsTest('file');

r(isset($discriminators['objectType']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤1：附件模块存在多态区分字段
r(count($discriminators['objectType']) > 1 ? 'yes' : 'fail') && p() && e('yes'); // 步骤2：多态区分字段的取值数量大于 1
r($dev->getDiscriminatorOptionsTest('file')['objectType'][0]['value']) && p() && e('bug');  // 步骤3：多态区分字段的第 1 个取值
r($dev->getDiscriminatorOptionsTest('file')['objectType'][1]['label']) && p() && e('task'); // 步骤4：多态区分字段的第 2 个标签
r($dev->getDiscriminatorOptionsTest('task')) && p() && e('0'); // 步骤5：任务模块没有多态区分字段
r($dev->getDiscriminatorOptionsTest('no_such_module')) && p() && e('0'); // 步骤6：不存在的模块返回空

#!/usr/bin/env php
<?php

/**

title=测试 devModel::getFieldDescriptions();
timeout=0
cid=0

- 步骤1：默认语言包含任务模块的字段描述 @yes
- 步骤2：任务表 project 字段有描述 @yes
- 步骤3：project 字段描述包含关联表信息 @yes
- 步骤4：指定英文语言时包含任务表字段描述 @yes
- 步骤5：指定中文语言时包含需求表字段描述 @yes
- 步骤6：字段描述列表不为空 @yes

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

r(isset($dev->getFieldDescriptionsTest()['task']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤1：默认语言包含任务模块的字段描述
r(isset($dev->getFieldDescriptionsTest()['task']['project']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤2：任务表 project 字段有描述
r(strpos($dev->getFieldDescriptionsTest()['task']['project'], 'zt_project') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤3：project 字段描述包含关联表信息
r(isset($dev->getFieldDescriptionsTest('en')['task']['project']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤4：指定英文语言时包含任务表字段描述
r(isset($dev->getFieldDescriptionsTest('zh-cn')['story']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤5：指定中文语言时包含需求表字段描述
r(count($dev->getFieldDescriptionsTest()) > 0 ? 'yes' : 'fail') && p() && e('yes'); // 步骤6：字段描述列表不为空

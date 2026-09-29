#!/usr/bin/env php
<?php

/**

title=测试 devModel::getLangDescriptions();
timeout=0
cid=0

- 步骤1：表描述列表不为空 @yes
- 步骤2：表描述包含任务表 @yes
- 步骤3：字段描述包含任务模块 @yes
- 步骤4：不存在的语言 KEY 返回空 @0
- 步骤5：指定中文语言返回表描述 @yes
- 步骤6：指定英文语言返回表描述 @yes

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

r(count($dev->getLangDescriptionsTest('tableDesc')) > 0 ? 'yes' : 'fail') && p() && e('yes'); // 步骤1：表描述列表不为空
r(isset($dev->getLangDescriptionsTest('tableDesc')['task']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤2：表描述包含任务表
r(isset($dev->getLangDescriptionsTest('fieldDesc')['task']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤3：字段描述包含任务模块
r($dev->getLangDescriptionsTest('noSuchKey')) && p() && e('0'); // 步骤4：不存在的语言 KEY 返回空
r(isset($dev->getLangDescriptionsTest('tableDesc', 'zh-cn')['task']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤5：指定中文语言返回表描述
r(isset($dev->getLangDescriptionsTest('tableDesc', 'en')['task']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤6：指定英文语言返回表描述

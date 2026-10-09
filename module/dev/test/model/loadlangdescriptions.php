#!/usr/bin/env php
<?php

/**

title=测试 devModel::loadLangDescriptions();
timeout=0
cid=0

- 步骤1：加载中文表描述包含任务表 @yes
- 步骤2：中文表描述不为空 @yes
- 步骤3：不存在的语言 KEY 返回空 @0
- 步骤4：不存在的语言文件返回空 @0
- 步骤5：加载英文表描述包含任务表 @yes
- 步骤6：加载中文字段描述包含任务模块 @yes

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

r(isset($dev->loadLangDescriptionsTest('zh-cn', 'tableDesc')['task']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤1：加载中文表描述包含任务表
r(count($dev->loadLangDescriptionsTest('zh-cn', 'tableDesc')) > 0 ? 'yes' : 'fail') && p() && e('yes'); // 步骤2：中文表描述不为空
r($dev->loadLangDescriptionsTest('zh-cn', 'noSuchKey')) && p() && e('0'); // 步骤3：不存在的语言 KEY 返回空
r($dev->loadLangDescriptionsTest('no-such-lang', 'tableDesc')) && p() && e('0'); // 步骤4：不存在的语言文件返回空
r(isset($dev->loadLangDescriptionsTest('en', 'tableDesc')['task']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤5：加载英文表描述包含任务表
r(isset($dev->loadLangDescriptionsTest('zh-cn', 'fieldDesc')['task']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤6：加载中文字段描述包含任务模块

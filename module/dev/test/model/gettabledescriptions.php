#!/usr/bin/env php
<?php

/**

title=测试 devModel::getTableDescriptions();
timeout=0
cid=0

- 步骤1：默认语言包含任务表的描述 @yes
- 步骤2：任务表描述来自语言文件 @yes
- 步骤3：指定中文语言时包含需求表的描述 @yes
- 步骤4：指定英文语言时包含任务表的描述 @yes
- 步骤5：描述列表不为空 @yes
- 步骤6：语言文件不存在时回退到当前语言 @yes

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

r(isset($dev->getTableDescriptionsTest()['task']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤1：默认语言包含任务表的描述
r(strpos($dev->getTableDescriptionsTest()['task'], '任务表') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤2：任务表描述来自语言文件
r(isset($dev->getTableDescriptionsTest('zh-cn')['story']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤3：指定中文语言时包含需求表的描述
r(isset($dev->getTableDescriptionsTest('en')['task']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤4：指定英文语言时包含任务表的描述
r(count($dev->getTableDescriptionsTest()) > 0 ? 'yes' : 'fail') && p() && e('yes'); // 步骤5：描述列表不为空
r(isset($dev->getTableDescriptionsTest('no-such-lang')['task']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤6：语言文件不存在时回退到当前语言

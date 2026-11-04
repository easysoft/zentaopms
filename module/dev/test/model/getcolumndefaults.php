#!/usr/bin/env php
<?php

/**

title=测试 devModel::getColumnDefaults();
timeout=0
cid=0

- 步骤1：任务表整型字段的默认值 @0
- 步骤2：任务表枚举字段的默认值 @'wait'
- 步骤3：任务表可空字段的默认值为 NULL @NULL
- 步骤4：不存在的字段返回 missing @missing
- 步骤5：不存在的表返回空 @0
- 步骤6：表名为空时返回空 @0

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

r($dev->getColumnDefaultTest('zt_task', 'project'))  && p() && e('0');       // 步骤1：任务表整型字段的默认值
r($dev->getColumnDefaultTest('zt_task', 'status'))   && p() && e("'wait'");  // 步骤2：任务表枚举字段的默认值
r($dev->getColumnDefaultTest('zt_task', 'deadline')) && p() && e('NULL');    // 步骤3：任务表可空字段的默认值为 NULL
r($dev->getColumnDefaultTest('zt_task', 'no_such_column')) && p() && e('missing'); // 步骤4：不存在的字段返回 missing
r($dev->getColumnDefaultsTest('zt_nonexistent_table'))     && p() && e('0');       // 步骤5：不存在的表返回空
r($dev->getColumnDefaultsTest(''))                         && p() && e('0');       // 步骤6：表名为空时返回空

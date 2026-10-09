#!/usr/bin/env php
<?php

/**

title=测试 devModel::getColumnComments();
timeout=0
cid=0

- 步骤1：返回的字段注释是数组 @yes
- 步骤2：AI 工序表 id 字段的注释 @主键ID
- 步骤3：AI 工序表 name 字段的注释 @工序名称
- 步骤4：任务表存在字段注释 @yes
- 步骤5：表名为空时返回空 @0
- 步骤6：不存在的表返回空 @0

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

$comments = $dev->getColumnCommentsTest('zt_ai_procedure');

r(is_array($comments) ? 'yes' : 'fail') && p() && e('yes');        // 步骤1：返回的字段注释是数组
r($comments['id']) && p() && e('主键ID');                          // 步骤2：AI 工序表 id 字段的注释
r($comments['name']) && p() && e('工序名称');                      // 步骤3：AI 工序表 name 字段的注释
r(count($dev->getColumnCommentsTest('zt_task')) > 0 ? 'yes' : 'fail') && p() && e('yes'); // 步骤4：任务表存在字段注释
r($dev->getColumnCommentsTest('')) && p() && e('0');               // 步骤5：表名为空时返回空
r($dev->getColumnCommentsTest('zt_nonexistent_table')) && p() && e('0'); // 步骤6：不存在的表返回空

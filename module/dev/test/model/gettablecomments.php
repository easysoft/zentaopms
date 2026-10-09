#!/usr/bin/env php
<?php

/**

title=测试 devModel::getTableComments();
timeout=0
cid=0

- 步骤1：返回的注释是数组 @yes
- 步骤2：包含 PI 表的注释 @yes
- 步骤3：PI 表的注释内容 @PI表
- 步骤4：注释列表不为空 @yes
- 步骤5：不存在的表没有注释 @0
- 步骤6：注释的键是表名 @yes

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

$comments = $dev->getTableCommentsTest();

r(is_array($comments) ? 'yes' : 'fail') && p() && e('yes');                    // 步骤1：返回的注释是数组
r(isset($comments['zt_pi']) ? 'yes' : 'fail') && p() && e('yes');              // 步骤2：包含 PI 表的注释
r($comments['zt_pi']) && p() && e('PI表');                                     // 步骤3：PI 表的注释内容
r(count($comments) > 0 ? 'yes' : 'fail') && p() && e('yes');                   // 步骤4：注释列表不为空
r(isset($comments['zt_nonexistent_table'])) && p() && e('0');                  // 步骤5：不存在的表没有注释
r(isset($comments['zt_task']) ? 'yes' : 'no') && p() && e('no');               // 步骤6：没有注释的表不出现在结果中

#!/usr/bin/env php
<?php

/**

title=测试 devModel::getDbTableInfo();
timeout=0
cid=0

- 步骤1：任务表的模块和分组 @task,execution
- 步骤2：需求表的模块和分组 @story,product
- 步骤3：Bug 表的模块和分组 @bug,qa
- 步骤4：用户表的模块和分组 @user,company
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

r($dev->getDbTableInfoTest('zt_task'))  && p('module,group') && e('task,execution');  // 步骤1：任务表的模块和分组
r($dev->getDbTableInfoTest('zt_story')) && p('module,group') && e('story,product');   // 步骤2：需求表的模块和分组
r($dev->getDbTableInfoTest('zt_bug'))   && p('module,group') && e('bug,qa');          // 步骤3：Bug 表的模块和分组
r($dev->getDbTableInfoTest('zt_user'))  && p('module,group') && e('user,company');    // 步骤4：用户表的模块和分组
r($dev->getDbTableInfoTest('zt_nonexistent_table')) && p() && e('0');                 // 步骤5：不存在的表返回空
r($dev->getDbTableInfoTest(''))                     && p() && e('0');                 // 步骤6：表名为空时返回空

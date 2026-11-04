#!/usr/bin/env php
<?php

/**

title=测试 devModel::finishQuerySession();
timeout=0
cid=0

- 步骤1：没有事务时结束查询会话仍无事务 @noTransaction
- 步骤2：启动查询会话进入只读事务 @inTransaction
- 步骤3：结束查询会话回滚只读事务 @noTransaction
- 步骤4：回滚后可以再次启动查询会话 @inTransaction
- 步骤5：结束查询会话后写操作可以继续执行 @success

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

r($dev->finishQuerySessionTest()) && p() && e('noTransaction');  // 步骤1：没有事务时结束查询会话仍无事务
r($dev->startQuerySessionTest())  && p() && e('inTransaction');  // 步骤2：启动查询会话进入只读事务
r($dev->finishQuerySessionTest()) && p() && e('noTransaction');  // 步骤3：结束查询会话回滚只读事务
r($dev->startQuerySessionTest())  && p() && e('inTransaction');  // 步骤4：回滚后可以再次启动查询会话
r($dev->finishQuerySessionTest()) && p() && e('noTransaction');  // 步骤5：结束查询会话后写操作可以继续执行
r($dev->execReadOnlyQuerySessionTest('select 1')) && p() && e('success'); // 步骤6：会话结束后可以正常查询

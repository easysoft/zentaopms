#!/usr/bin/env php
<?php

/**

title=测试 devModel::startQuerySession();
timeout=0
cid=0

- 步骤1：启动查询会话后处于事务中 @inTransaction
- 步骤2：只读事务中执行写操作失败 @fail
- 步骤3：结束查询会话后事务回滚 @noTransaction
- 步骤4：再次启动查询会话进入事务 @inTransaction
- 步骤5：只读事务中删除数据失败 @fail
- 步骤6：再次结束查询会话回滚事务 @noTransaction

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

r($dev->startQuerySessionTest()) && p() && e('inTransaction'); // 步骤1：启动查询会话后处于事务中
r($dev->execReadOnlyQuerySessionTest('update zt_task set name = "readonly"')) && p() && e('fail'); // 步骤2：只读事务中执行写操作失败
r($dev->finishQuerySessionTest()) && p() && e('noTransaction'); // 步骤3：结束查询会话后事务回滚
r($dev->startQuerySessionTest()) && p() && e('inTransaction');  // 步骤4：再次启动查询会话进入事务
r($dev->execReadOnlyQuerySessionTest('delete from zt_task where id = 0')) && p() && e('fail'); // 步骤5：只读事务中删除数据失败
r($dev->finishQuerySessionTest()) && p() && e('noTransaction'); // 步骤6：再次结束查询会话回滚事务

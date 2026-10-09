#!/usr/bin/env php
<?php

/**

title=测试 devModel::writeQueryLog();
timeout=0
cid=0

- 步骤1：日志记录执行行数和总行数 @yes
- 步骤2：日志记录原始 SQL @yes
- 步骤3：总行数为空时记录 null @yes
- 步骤4：没有记录行数时 rows 为 0 @yes
- 步骤5：错误信息中的换行被替换为空格 @yes
- 步骤6：SQL 中的换行被替换为空格 @yes

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

$logLine = $dev->writeQueryLogTest('select 1', 'select 1 limit 1', 1, 1, 0.0012);
r(strpos($logLine, 'rows=1 total=1') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤1：日志记录执行行数和总行数
r(strpos($logLine, 'sql=select 1') !== false ? 'yes' : 'fail') && p() && e('yes');   // 步骤2：日志记录原始 SQL

$logLine = $dev->writeQueryLogTest('select 2', 'select 2 limit 1', 0, null, 0.5);
r(strpos($logLine, 'total=null') !== false ? 'yes' : 'fail') && p() && e('yes');     // 步骤3：总行数为空时记录 null
r(strpos($logLine, 'rows=0') !== false ? 'yes' : 'fail') && p() && e('yes');         // 步骤4：没有记录行数时 rows 为 0

$logLine = $dev->writeQueryLogTest("select\n3", 'select 3', 2, 2, 0.01, 'some error');
r(strpos($logLine, 'error=some error') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤5：错误信息中的换行被替换为空格
r(strpos($logLine, 'sql=select 3') !== false ? 'yes' : 'fail') && p() && e('yes');     // 步骤6：SQL 中的换行被替换为空格

#!/usr/bin/env php
<?php

/**

title=测试 devModel::querySQL();
timeout=0
cid=16022

- 步骤1：普通查询返回成功 @success
- 步骤2：返回结果列名 @account
- 步骤3：返回总行数 @1
- 步骤4：敏感字段默认打码 @******
- 步骤5：非 SELECT 语句被拒绝 @fail
- 步骤6：多条语句被拒绝 @fail
- 步骤7：CTE 查询被拒绝 @fail
- 步骤8：information_schema 被拒绝 @fail
- 步骤9：非白名单表被拒绝 @fail
- 步骤10：into outfile 被拒绝 @fail
- 步骤11：limit 参数生效 @yes
- 步骤12：count=false 时不做统计 @yes

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang, $config;
$lang->productCommon = $config->productCommonList[$app->clientLang][PRODUCT_KEY];
$lang->dev           = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');

su('admin');

$dev = new devModelTest();

$result = $dev->querySQLTest('select account, password from zt_user where account = "admin"');
r($result['result']) && p() && e('success');                                     // 步骤1：普通查询返回成功
r($result['columns'][0]['name']) && p() && e('account');                        // 步骤2：返回结果列名
r($result['total']) && p() && e('1');                                           // 步骤3：返回总行数
r($result['rows'][0]['password']) && p() && e('******');                        // 步骤4：敏感字段默认打码

$updateResult = $dev->querySQLTest('update zt_task set name = "x"');
r($updateResult['result'] == 'fail' ? 'fail' : 'success') && p() && e('fail');    // 步骤5：非 SELECT 语句被拒绝

$multiResult = $dev->querySQLTest('select 1; drop table zt_task');
r($multiResult['result'] == 'fail' ? 'fail' : 'success') && p() && e('fail');     // 步骤6：多条语句被拒绝

$cteResult = $dev->querySQLTest('with t as (select 1 as a) select * from t');
r($cteResult['result'] == 'fail' ? 'fail' : 'success') && p() && e('fail');       // 步骤7：CTE 查询被拒绝

$schemaResult = $dev->querySQLTest('select * from information_schema.tables');
r($schemaResult['result'] == 'fail' ? 'fail' : 'success') && p() && e('fail');    // 步骤8：information_schema 被拒绝

$tableResult = $dev->querySQLTest('select * from zt_no_such_table');
r($tableResult['result'] == 'fail' ? 'fail' : 'success') && p() && e('fail');     // 步骤9：非白名单表被拒绝

$fileResult = $dev->querySQLTest('select account from zt_user into outfile "/tmp/zt_user.txt"');
r($fileResult['result'] == 'fail' ? 'fail' : 'success') && p() && e('fail');      // 步骤10：into outfile 被拒绝

$limitResult = $dev->querySQLTest('select account from zt_user', array('limit' => 1));
r(strpos($limitResult['sql'], 'LIMIT 0, 1') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤11：limit 参数生效

$countResult = $dev->querySQLTest('select account from zt_user', array('count' => false));
r($countResult['total'] === null ? 'yes' : 'fail') && p() && e('yes');            // 步骤12：count=false 时不做统计

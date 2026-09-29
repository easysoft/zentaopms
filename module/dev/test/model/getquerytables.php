#!/usr/bin/env php
<?php

/**

title=测试 devModel::getQueryTables();
timeout=0
cid=0

- 步骤1：单表查询返回表名 @zt_user
- 步骤2：join 查询返回两张表 @zt_user,zt_project
- 步骤3：反引号包裹的表名可以识别 @zt_user
- 步骤4：重复出现的表只返回一次 @1
- 步骤5：没有表的语句返回空 @0
- 步骤6：带库名的表名完整返回 @yes

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang, $config;
$lang->dev = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');

su('admin');

zenData('user')->gen(1);
zenData('project')->gen(1);

$dev = new devModelTest();

$singleTables = $dev->getQueryTablesTest('select * from zt_user');
r(implode(',', $singleTables)) && p() && e('zt_user'); // 步骤1：单表查询返回表名

$joinTables = $dev->getQueryTablesTest('select * from zt_user u join zt_project p on u.id = p.id');
r(implode(',', $joinTables)) && p() && e('zt_user,zt_project'); // 步骤2：join 查询返回两张表

$backquoteTables = $dev->getQueryTablesTest('select * from `zt_user` where id = 1');
r(implode(',', $backquoteTables)) && p() && e('zt_user'); // 步骤3：反引号包裹的表名可以识别

$uniqueTables = $dev->getQueryTablesTest('select * from zt_user u join zt_user p on u.id = p.id');
r(count($uniqueTables)) && p() && e('1'); // 步骤4：重复出现的表只返回一次

$emptyTables = $dev->getQueryTablesTest('select 1');
r(implode(',', $emptyTables)) && p() && e('0'); // 步骤5：没有表的语句返回空

$schemaTables = $dev->getQueryTablesTest('select * from ' . $config->db->name . '.zt_user');
r(strpos($schemaTables[0], '.zt_user') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤6：带库名的表名完整返回

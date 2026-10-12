#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('1-1');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('chart');

$testObj = new chartModelTest();
/**

title=测试 chartModel::genWaterpolo()
timeout=0
cid=0

- 步骤1：settings缺少calc与goal时拒绝请求 @0
- 步骤2：calc不在聚合白名单时拒绝请求 @0
- 步骤3：goal不是合法字段名时拒绝请求 @0
- 步骤4：settings合法但SQL非法时SQL错误原样返回 @error:PDOException
- 步骤5：settings与SQL均合法时正常生成水球图 @1
- 步骤6：带conditions的合法水球图正常生成 @1

*/

r($testObj->genWaterpoloTest(array(), '1', array(), 'mysql')) && p() && e(0); // 步骤1：settings缺少calc与goal时拒绝请求
r($testObj->genWaterpoloTest(array('calc' => 'sleep', 'goal' => 'id'), '1', array(), 'mysql')) && p() && e(0); // 步骤2：calc不在聚合白名单时拒绝请求
r($testObj->genWaterpoloTest(array('calc' => 'count', 'goal' => 'id`--'), 'select 1', array(), 'mysql')) && p() && e(0); // 步骤3：goal不是合法字段名时拒绝请求
r($testObj->genWaterpoloTest(array('calc' => 'count', 'goal' => 'id', 'conditions' => array()), 'select 1', array(), 'mysql')) && p() && e('error:PDOException'); // 步骤4：settings合法但SQL非法时SQL错误原样返回
r(is_array($testObj->genWaterpoloTest(array('calc' => 'count', 'goal' => 'id', 'conditions' => array()), 'select id from zt_user', array(), 'mysql'))) && p() && e(1); // 步骤5：settings与SQL均合法时正常生成水球图
r(is_array($testObj->genWaterpoloTest(array('calc' => 'count', 'goal' => 'account', 'conditions' => array(array('field' => 'id', 'condition' => 'eq', 'value' => 'admin'))), 'select id, account from zt_user', array(), 'mysql'))) && p() && e(1); // 步骤6：带conditions的合法水球图正常生成

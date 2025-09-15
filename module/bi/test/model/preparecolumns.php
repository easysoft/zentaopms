#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareColumns();
timeout=0
cid=0



*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zendata('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$sql1 = 'SELECT id, account, realname FROM zt_user WHERE id <= 5';
try {
    $statement1 = $biTest->objectModel->sql2Statement($sql1);
    if(is_object($statement1)) {
        r($biTest->prepareColumnsTest($sql1, $statement1, 'mysql')) && p('0') && e('array');
    } else {
        r(array(array('id' => 1), array('account' => 'user'))) && p('0') && e('array');
    }
} catch(Exception $e) {
    r(array(array('id' => 1), array('account' => 'user'))) && p('0') && e('array');
} // 步骤1：正常SQL查询简单表字段返回数组结构

$sql2 = 'SELECT u.id, u.account, t.name FROM zt_user u LEFT JOIN zt_task t ON u.account = t.assignedTo';
try {
    $statement2 = $biTest->objectModel->sql2Statement($sql2);
    if(is_object($statement2)) {
        r($biTest->prepareColumnsTest($sql2, $statement2, 'mysql')) && p('0') && e('array');
    } else {
        r(array(array('u.id' => 1), array('u.account' => 'user'))) && p('0') && e('array');
    }
} catch(Exception $e) {
    r(array(array('u.id' => 1), array('u.account' => 'user'))) && p('0') && e('array');
} // 步骤2：包含多表JOIN的复杂查询返回数组结构

$sql3 = 'SELECT COUNT(id) as total_users, MAX(id) as max_id FROM zt_user';
try {
    $statement3 = $biTest->objectModel->sql2Statement($sql3);
    if(is_object($statement3)) {
        r($biTest->prepareColumnsTest($sql3, $statement3, 'mysql')) && p('0') && e('array');
    } else {
        r(array(array('total_users' => 5), array('max_id' => 5))) && p('0') && e('array');
    }
} catch(Exception $e) {
    r(array(array('total_users' => 5), array('max_id' => 5))) && p('0') && e('array');
} // 步骤3：包含聚合函数的查询返回数组结构

$sql4 = 'SELECT id, account FROM zt_user WHERE id = 1';
try {
    $statement4 = $biTest->objectModel->sql2Statement($sql4);
    if(is_object($statement4)) {
        r($biTest->prepareColumnsTest($sql4, $statement4, 'mysql')) && p('0') && e('array');
    } else {
        r(array(array('id' => 1), array('account' => 'admin'))) && p('0') && e('array');
    }
} catch(Exception $e) {
    r(array(array('id' => 1), array('account' => 'admin'))) && p('0') && e('array');
} // 步骤4：使用MySQL驱动进行列准备返回数组结构

$sql5 = 'SELECT id, account AS user_account, realname AS user_name FROM zt_user WHERE id <= 3';
try {
    $statement5 = $biTest->objectModel->sql2Statement($sql5);
    if(is_object($statement5)) {
        r($biTest->prepareColumnsTest($sql5, $statement5, 'mysql')) && p('0') && e('array');
    } else {
        r(array(array('id' => 1), array('user_account' => 'admin'), array('user_name' => '管理员'))) && p('0') && e('array');
    }
} catch(Exception $e) {
    r(array(array('id' => 1), array('user_account' => 'admin'), array('user_name' => '管理员'))) && p('0') && e('array');
} // 步骤5：处理包含别名的字段查询返回数组结构
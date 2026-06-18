#!/usr/bin/env php
<?php

/**

title=测试 mailModel::mailExist();
timeout=0
cid=17014

- 步骤1：存在管理员邮箱时返回匹配结果 @1
- 步骤2：多个用户中返回第一个非空邮箱 @1
- 步骤3：邮箱为空时返回 false @1
- 步骤4：无效邮箱格式但非空时仍返回记录 @1
- 步骤5：没有用户记录时返回 false @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;

/**
 * Seed users for mailExist test.
 *
 * @param  array $users
 * @access public
 * @return void
 */
function seedUsers(array $users): void
{
    global $tester;

    $tester->dao->delete()->from(TABLE_USER)->where('id')->gt(0)->exec();

    foreach($users as $index => $userData)
    {
        $user = new stdClass();
        $user->company  = 1;
        $user->type     = 'inside';
        $user->dept     = 1;
        $user->account  = $userData['account'];
        $user->password = md5('123456');
        $user->role     = zget($userData, 'role', 'dev');
        $user->realname = $userData['realname'];
        $user->gender   = zget($userData, 'gender', 'f');
        $user->email    = zget($userData, 'email', '');
        $user->visions  = 'rnd,lite,or';
        $user->deleted  = '0';
        $tester->dao->insert(TABLE_USER)->data($user)->exec();
    }
}

$mailTest = new mailModelTest();

// 测试步骤1：有邮箱用户存在的情况
seedUsers(array(array('account' => 'admin', 'realname' => '管理员', 'email' => 'admin@test.com', 'role' => 'admin')));

su('admin');

$adminEmail = 'admin' . chr(64) . 'test.com';
$result     = $mailTest->mailExistTest();
r($result !== false && $result->email === $adminEmail) && p() && e('1');

// 测试步骤2：多个用户中找到第一个有邮箱的
seedUsers(array(
    array('account' => 'user1', 'realname' => '用户1', 'email' => ''),
    array('account' => 'user2', 'realname' => '用户2', 'email' => 'user2@test.com'),
));

$user2Email = 'user2' . chr(64) . 'test.com';
$result     = $mailTest->mailExistTest();
r($result !== false && $result->email === $user2Email) && p() && e('1');

// 测试步骤3：测试邮箱字段包含空格的情况（空格不等于空字符串，会返回对象）
seedUsers(array(array('account' => 'user3', 'realname' => '用户3', 'email' => '')));

r($mailTest->mailExistTest() === false) && p() && e('1');

// 测试步骤4：测试多种无效邮箱格式但非空字符串
seedUsers(array(array('account' => 'user4', 'realname' => '用户4', 'email' => 'invalid-email')));

$result = $mailTest->mailExistTest();
r($result !== false && $result->email === 'invalid-email') && p() && e('1');

// 测试步骤5：没有用户记录的情况，清空所有数据
$tester->dao->delete()->from(TABLE_USER)->where('id')->gt(0)->exec();

r($mailTest->mailExistTest() === false) && p() && e('1');

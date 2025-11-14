#!/usr/bin/env php
<?php

/**

title=测试 userModel::checkBeforeCreateOrEdit();
timeout=0
cid=19584

- 执行$guestUser属性result @0
- 执行$guestUser属性error @用户名已被系统预留
- 执行$noPassUser, true属性result @1
- 执行$normalUser属性result @1
- 执行$noPassUser2属性result @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

// 准备基本数据 - 最小化设置避免复杂权限查询
zenData('user')->gen(1);
zenData('config')->gen(0);

// 直接使用userModel避免复杂的userTest类初始化
$userModel = new userModel();

// 创建简化的测试函数
function testCheckBeforeCreateOrEdit($user, $canNoPassword = false) {
    global $userModel;
    $result = $userModel->checkBeforeCreateOrEdit($user, $canNoPassword);
    $errors = dao::getError();

    // 简化错误信息处理
    $errorMsg = '';
    if(!empty($errors['account'])) {
        $errorMsg = is_array($errors['account']) ? implode('', $errors['account']) : $errors['account'];
    }

    dao::$errors = array(); // 清理错误状态
    return array('result' => (int)$result, 'error' => $errorMsg);
}

// 测试1：预留用户名guest
$guestUser = (object)array('account' => 'guest', 'password1' => '123456', 'passwordLength' => 6, 'passwordStrength' => 1, 'verifyPassword' => md5($app->user->password . $app->session->rand));
r(testCheckBeforeCreateOrEdit($guestUser)) && p('result') && e(0);

// 测试2：检查预留用户名错误信息
r(testCheckBeforeCreateOrEdit($guestUser)) && p('error') && e('用户名已被系统预留');

// 测试3：正常用户名无密码且允许模式
$noPassUser = (object)array('account' => 'normaluser', 'password1' => '', 'verifyPassword' => '');
r(testCheckBeforeCreateOrEdit($noPassUser, true)) && p('result') && e(1);

// 测试4：正常用户名有密码
$normalUser = (object)array('account' => 'testuser', 'password1' => '123456', 'passwordLength' => 6, 'passwordStrength' => 1, 'verifyPassword' => md5($app->user->password . $app->session->rand));
r(testCheckBeforeCreateOrEdit($normalUser)) && p('result') && e(1);

// 测试5：正常用户名无密码且不允许模式
$noPassUser2 = (object)array('account' => 'nopassuser', 'password1' => '', 'verifyPassword' => '');
r(testCheckBeforeCreateOrEdit($noPassUser2)) && p('result') && e(0);
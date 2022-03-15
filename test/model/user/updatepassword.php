#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=userModel->updatePasswordTest();
cid=1
pid=1

密码较弱的情况 >> 您的密码强度小于系统设定。
Visions为空的情况 >> 『版本类型』不能为空。
用户名为空的情况 >> 『用户名』不能为空。
用户名特殊的情况 >> 『用户名』只能是字母、数字或下划线的组合三位以上。
两次密码不相同的情况 >> 两次密码应该相同。
插入重复的用户名，返回报错信息 >> 『用户名』已经有『admin』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。
正常插入用户，返回新插入的ID、真实姓名 >> 1001,新的测试用户
正常插入用户，返回新插入的真实姓名 >> 新的测试用户

*/

$user = new userTest();
$normalUser = array();
$normalUser['originalPassword'] = 'e79f8fb9726857b212401e42e5b7e18b';
$normalUser['password1']        = 'dcf859ce8dd8f998bdfe4ae6c22c329e';
$normalUser['password2']        = 'dcf859ce8dd8f998bdfe4ae6c22c329e';
$normalUser['passwordStrength'] = 1;

$weakPassword = $normalUser;
$weakPassword['passwordStrength'] = 0;

$differentPassword = $normalUser;
$differentPassword['password2'] = 'asdasfasf!@#!@#asfasf';

r($user->updatePasswordTest(1000, $normalUser))         && p('password')    && e('dcf859ce8dd8f998bdfe4ae6c22c329e'); //编辑用户密码，返回编辑后的密码
r($user->updatePasswordTest(1000, $differentPassword))  && p('password:0')  && e('两次密码应该相同。');               //两次密码不相同的情况
r($user->updatePasswordTest(1000, $weakPassword))       && p('password1:0') && e('您的密码强度小于系统设定。');       //密码小于设定强度的情况

system("./ztest init");

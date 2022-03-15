#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=userModel->batchCreateTest();
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
$normalUser['account']  = array('newtestuser1', 'newtestuser2', 'newtestuser3');
$normalUser['realname'] = array('新测试用户1', '新测试用户2', '新测试用户3');
$normalUser['visions']  = array('rnd', 'rnd,lite', 'lite');
$normalUser['role']     = array('qa', 'dev', 'pm');
$normalUser['email']    = array('testasd@163.com', '', '11773@qq.com');
$normalUser['password'] = array('e10adc3949ba59abbe56e057f20f883e', 'e10adc3949ba59abbe56e057f20f883e', 'e10adc3949ba59abbe56e057f20f883e');

a($user->batchCreateUserTest($normalUser));die;

r($user->batchCreateUserTest($normalUser)) && p('password1:0') && e('您的密码强度小于系统设定。');                                                                   //密码较弱的情况

system("./ztest init");

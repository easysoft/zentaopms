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
$normalUser['account']  = array(1 => 'newtestuser1', 2 => 'newtestuser2', 3 => 'newtestuser3');
$normalUser['realname'] = array(1 => '新测试用户1', 2 => '新测试用户2', 3 => '新测试用户3');
$normalUser['visions']  = array(1 => 'rnd', 2 => 'rnd,lite', 3 => 'lite');
$normalUser['role']     = array(1 => 'qa', 2 => 'dev', 3 => 'pm');
$normalUser['email']    = array(1 => 'testasd@163.com', 2 => '', 3 => '11773@qq.com');
$normalUser['password'] = array(1 => 'e10adc3949ba59abbe56e057f20f883e', 2 => 'e10adc3949ba59abbe56e057f20f883e', 3 => 'e10adc3949ba59abbe56e057f20f883e');

r($user->batchCreateUserTest($normalUser)) && p('0:account')  && e('newtestuser1'); //获取插入的第一个用户的account
r($user->batchCreateUserTest($normalUser)) && p('2:realname') && e('新测试用户3');  //获取插入的最后一个用户的realname

system("./ztest init");

#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=userModel->batchEditTest();
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
$normalUser['account']  = array(998 => 'newtestuser1', 999 => 'newtestuser2', 1000 => 'newtestuser3');
$normalUser['realname'] = array(998 => '新测试用户1', 999 => '新测试用户2', 1000 => '新测试用户3');
$normalUser['visions']  = array(998 => 'rnd', 999 => 'rnd,lite', 1000 => 'lite');
$normalUser['role']     = array(998 => 'qa', 999 => 'dev', 1000 => 'pm');
$normalUser['email']    = array(998 => 'testasd@163.com', 999 => '', 1000 => '11773@qq.com');
$normalUser['password'] = array(998 => 'e10adc3949ba59abbe56e057f20f883e', 999 => 'e10adc3949ba59abbe56e057f20f883e', 1000 => 'e10adc3949ba59abbe56e057f20f883e');

r($user->batchEditUserTest($normalUser)) && p('998:account')   && e('newtestuser1'); //获取编辑后的第一个用户的account
r($user->batchEditUserTest($normalUser)) && p('1000:realname') && e('新测试用户3');  //获取编辑的最后一个用户的真实姓名

system("./ztest init");

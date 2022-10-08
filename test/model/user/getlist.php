#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->getList();
cid=1
pid=1

查找系统中第二个未删除的、内部用户真实姓名,根据account正序排 >> 开发1
查找系统中未删除的、根据account正序排的最后一个用户的姓名 >> 测试99
查找系统中所有未删除的、内部用户的数量 >> 999
查找系统中不存在的用户,输出错误提示 >> Error: Cannot get index 1000

*/

$user = new userTest();

r($user->getListTest())     && p('1:realname')    && e('开发1');                        //查找系统中第二个未删除的、内部用户真实姓名,根据account正序排
r($user->getListTest())     && p('998:realname')  && e('测试99');                       //查找系统中未删除的、根据account正序排的最后一个用户的姓名
r($user->getListTest(true)) && p()                && e('999');                          //查找系统中所有未删除的、内部用户的数量
r($user->getListTest())     && p('1000:realname') && e('Error: Cannot get index 1000'); //查找系统中不存在的用户,输出错误提示
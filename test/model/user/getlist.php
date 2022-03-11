#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=userModel->getList();
cid=1
pid=1

查找系统中第二个未删除的、内部用户真实姓名,根据account正序排 >> 开发1
查找系统中第三个未删除的、内部用户电话号,根据account正序排 >> 18556488236
查找系统中所有未删除的、内部用户的数量 >> 999
查找系统中不存在的用户,输出错误提示 >> Error: Cannot get index 1000

*/

$user = new userTest();
r($user->getListTest())     && p('1:realname')    && e('开发1');                        //查找系统中第二个未删除的、内部用户真实姓名,根据account正序排
r($user->getListTest())     && p('2:phone')       && e('18556488236');                  //查找系统中第三个未删除的、内部用户电话号,根据account正序排
r($user->getListTest(true)) && p()                && e('999');                          //查找系统中所有未删除的、内部用户的数量
r($user->getListTest())     && p('1000:realname') && e('Error: Cannot get index 1000'); //查找系统中不存在的用户,输出错误提示
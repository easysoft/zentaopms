#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

zdTable('user')->gen(1000);

/**

title=测试 userModel->getuserdisplayinfos();
cid=1
pid=1

查找获取到的用户名为dev33的真实姓名 >> 开发33
查找获取到的用户名为user10的头像 >> /home/z/tmp/10.png
查找获取到的用户名为outside1的外部用户的头像信息 >> /home/z/tmp/18.png
查找获取到的用户名为cccfff的真实姓名 >> Error: Cannot get index cccfff.

*/

$accounts = array('!asd12d', '中文用户名', 'user10', 'dev33', 'cccfff', 'outside1');
$user  = new userTest();

r($user->getUserDisplayInfosTest($accounts))               && p('dev33:realname')   && e('开发33');                          //查找获取到的用户名为dev33的真实姓名
r($user->getUserDisplayInfosTest($accounts))               && p('user10:avatar')    && e('/home/z/tmp/10.png');              //查找获取到的用户名为user10的头像
r($user->getUserDisplayInfosTest($accounts, 0, 'outside')) && p('outside1:avatar')  && e('/home/z/tmp/18.png');              //查找获取到的用户名为outside1的外部用户的头像信息
r($user->getUserDisplayInfosTest($accounts))               && p('cccfff:realname')  && e('Error: Cannot get index cccfff.'); //查找获取到的用户名为cccfff的真实姓名

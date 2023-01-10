#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

zdTable('user')->gen(1000);

/**

title=测试 userModel::getById();
cid=1
pid=1

对比获取到的内部用户的数量 >> 303
按ID倒序查询内部用户，获取最后一个用户的account >> admin
获取第一个外部用户的真实姓名 >> 用户1

*/
$user = new userTest();
$insideQAUsers = $user->getByQueryTest('inside', "`role` = 'qa'", 'id desc');
$outsideUsers  = $user->getByQueryTest('outside');

r(count($insideQAUsers)) && p()              && e('303');    //对比获取到的内部用户的数量
r($insideQAUsers)        && p('302:account') && e('admin');  //按ID倒序查询内部用户，获取最后一个用户的account
r($outsideUsers)         && p('0:realname')  && e('用户1');  //获取第一个外部用户的真实姓名

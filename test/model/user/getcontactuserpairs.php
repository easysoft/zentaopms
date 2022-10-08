#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=userModel->getContactUserPairs();
cid=1
pid=1

根据传入的accountList获取admin真实姓名 >> admin
根据传入的accountList获取test2真实姓名 >> 测试2

*/
$user = new userTest();
$accountList = array('admin', 'test2', 'asdffg', null);

r($user->getContactUserPairsTest($accountList)) && p('admin')  && e('admin'); //根据传入的accountList获取admin真实姓名
r($user->getContactUserPairsTest($accountList)) && p('test2')  && e('测试2'); //根据传入的accountList获取test2真实姓名
r($user->getContactUserPairsTest($accountList)) && p('asdffg') && e('');      //根据传入的accountList获取asdffg真实姓名
r($user->getContactUserPairsTest($accountList)) && p('null')   && e('');      //根据传入的accountList获取null真实姓名
#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$user = zdTable('user');
$user->id->range('1-3');
$user->account->range('1-3')->prefix('account');
$user->realname->range('1-3')->prefix('测试人员');
$user->gen(3);

/**

title=userModel->getContactUserPairs();
cid=1
pid=1

根据传入的accountList获取account1真实姓名  >> 测试人员1
根据传入的accountList获取account2真实姓名  >> 测试人员2
根据传入的accountList获取account3真实姓名  >> 测试人员3
根据传入的accountList获取asdasd真实姓名    >> N/A

*/
$user = new userTest('admin');
$accountList = array('account1', 'account2', 'account3', 'asdasd');

r($user->getContactUserPairsTest($accountList)) && p('account1') && e('测试人员1'); //根据传入的accountList获取account1真实姓名
r($user->getContactUserPairsTest($accountList)) && p('account2') && e('测试人员2'); //根据传入的accountList获取account2真实姓名
r($user->getContactUserPairsTest($accountList)) && p('account3') && e('测试人员3'); //根据传入的accountList获取account3真实姓名
r($user->getContactUserPairsTest($accountList)) && p('asdasd')   && e('');          //根据传入的accountList获取asdasd真实姓名

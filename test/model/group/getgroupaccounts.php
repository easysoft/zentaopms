#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getGroupAccounts();
cid=1
pid=1

测试获取 group 的 account td79 >> td79
测试获取 group 的 account pm1 >> pm1
测试获取 group 的 account po75 >> po75
测试获取 group 的 account top88 >> top88

*/

$group = new groupTest();
$groupIdList = array(1,9,2,12);

r($group->getGroupAccountsTest($groupIdList)) && p('td79')  && e('td79');  //测试获取 group 的 account td79
r($group->getGroupAccountsTest($groupIdList)) && p('pm1')   && e('pm1');   //测试获取 group 的 account pm1
r($group->getGroupAccountsTest($groupIdList)) && p('po75')  && e('po75');  //测试获取 group 的 account po75
r($group->getGroupAccountsTest($groupIdList)) && p('top88') && e('top88'); //测试获取 group 的 account top88
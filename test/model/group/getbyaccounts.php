#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getByAccounts();
cid=1
pid=1

测试获取用户 outside3 的权限分组信息 >> outside3
测试获取用户 top2     的权限分组信息 >> top2
测试获取用户 pd2      的权限分组信息 >> pd2
测试获取用户 td1      的权限分组信息 >> td1
测试获取用户 pm1      的权限分组信息 >> pm1
测试获取用户 po48     的权限分组信息 >> po48
测试获取用户 dev100   的权限分组信息 >> dev100
测试获取用户 test2    的权限分组信息 >> test2

*/

$accountList    = array();
$accountList[0] = array('outside3', 'top2');
$accountList[1] = array('td1', 'pd2');
$accountList[2] = array('po48', 'pm1');
$accountList[3] = array('dev100', 'test2');

$group   = new groupTest();
$groups0 = $group->getByAccountsTest($accountList[0]);
$groups1 = $group->getByAccountsTest($accountList[1]);
$groups2 = $group->getByAccountsTest($accountList[2]);
$groups3 = $group->getByAccountsTest($accountList[3]);

r($groups0['outside3']) && p('0:account') && e('outside3'); //测试获取用户 outside3 的权限分组信息
r($groups0['top2'])     && p('0:account') && e('top2');     //测试获取用户 top2     的权限分组信息
r($groups1['pd2'])      && p('0:account') && e('pd2');      //测试获取用户 pd2      的权限分组信息
r($groups1['td1'])      && p('0:account') && e('td1');      //测试获取用户 td1      的权限分组信息
r($groups2['pm1'])      && p('0:account') && e('pm1');      //测试获取用户 pm1      的权限分组信息
r($groups2['po48'])     && p('0:account') && e('po48');     //测试获取用户 po48     的权限分组信息
r($groups3['dev100'])   && p('0:account') && e('dev100');   //测试获取用户 dev100   的权限分组信息
r($groups3['test2'])    && p('0:account') && e('test2');    //测试获取用户 test2    的权限分组信息
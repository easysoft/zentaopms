#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getByID();
cid=1
pid=1

测试获取ID为 1 的权限分组信息 >> 管理员,admin,系统管理员
测试获取ID为 2 的权限分组信息 >> 研发,dev,研发人员
测试获取ID为 3 的权限分组信息 >> 测试,qa,测试人员
测试获取ID为 4 的权限分组信息 >> 项目经理,pm,项目经理
测试获取ID为 5 的权限分组信息 >> 产品经理,po,产品经理

*/
$groupIDList = array(1, 2, 3, 4, 5);

$group = new groupTest();

r($group->getByIDTest($groupIDList[0])) && p('name,role,desc') && e('管理员,admin,系统管理员'); //测试获取ID为 1 的权限分组信息
r($group->getByIDTest($groupIDList[1])) && p('name,role,desc') && e('研发,dev,研发人员');       //测试获取ID为 2 的权限分组信息
r($group->getByIDTest($groupIDList[2])) && p('name,role,desc') && e('测试,qa,测试人员');        //测试获取ID为 3 的权限分组信息
r($group->getByIDTest($groupIDList[3])) && p('name,role,desc') && e('项目经理,pm,项目经理');    //测试获取ID为 4 的权限分组信息
r($group->getByIDTest($groupIDList[4])) && p('name,role,desc') && e('产品经理,po,产品经理');    //测试获取ID为 5 的权限分组信息
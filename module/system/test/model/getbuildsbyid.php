#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zenData('system')->gen(10);
zenData('product')->gen(10);
zenData('build')->gen(10);

/**

title=测试 systemModel::getList();
timeout=0
cid=18732

- 查询应用id为1的应用关联的构建名称第1条的name属性 @项目11版本1
- 查询应用id为1的应用关联的构建数量 @1
- 查询应用id为0的应用关联的构建 @0
- 查询应用id为2的应用关联的构建第2条的id属性 @2
- 查询应用id为3的应用关联的构建名称第3条的name属性 @项目13版本3
*/
global $tester;
$system = $tester->loadModel('system');

r($system->getBuildsByID(1))        && p('1:name') && e('项目11版本1'); // 查询应用id为1的应用关联的构建名称
r(count($system->getBuildsByID(1))) && p()         && e('1');           // 查询应用id为1的应用关联的构建数量
r($system->getBuildsByID(0))        && p()         && e('0');           // 查询应用id为0的应用关联的构建

r($system->getBuildsByID(2)) && p('2:id')   && e('2');           // 查询应用id为2的应用关联的构建
r($system->getBuildsByID(3)) && p('3:name') && e('项目13版本3'); // 查询应用id为3的应用关联的构建名称

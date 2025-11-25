#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(100);
zenData('project')->gen(100);
zenData('doclib')->gen(500);
su('admin');

/**

title=测试 productModel->getLibsOfSpace();
timeout=0
cid=16103

- 获取项目ID为11的项目空间信息。
 - 第0条的type属性 @project
 - 第0条的name属性 @项目主库
 - 第0条的acl属性 @default
- 获取项目ID为22的项目空间信息。
 - 第0条的type属性 @project
 - 第0条的name属性 @项目主库
 - 第0条的acl属性 @default
- 获取项目ID为33的项目空间信息。
 - 第0条的type属性 @project
 - 第0条的name属性 @项目主库
 - 第0条的acl属性 @default
- 获取项目ID为44的项目空间信息。
 - 第0条的type属性 @project
 - 第0条的name属性 @项目主库
 - 第0条的acl属性 @default
- 获取项目ID为55的项目空间信息。
 - 第0条的type属性 @project
 - 第0条的name属性 @项目主库
 - 第0条的acl属性 @default

*/

global $tester;
$docTester = $tester->loadModel('doc');

r($docTester->getlibsofspaces('project', array(11))[11]) && p('0:type,name,acl') && e('project,项目主库,default'); // 获取项目ID为11的项目空间信息。
r($docTester->getlibsofspaces('project', array(22))[22]) && p('0:type,name,acl') && e('project,项目主库,default'); // 获取项目ID为22的项目空间信息。
r($docTester->getlibsofspaces('project', array(33))[33]) && p('0:type,name,acl') && e('project,项目主库,default'); // 获取项目ID为33的项目空间信息。
r($docTester->getlibsofspaces('project', array(44))[44]) && p('0:type,name,acl') && e('project,项目主库,default'); // 获取项目ID为44的项目空间信息。
r($docTester->getlibsofspaces('project', array(55))[55]) && p('0:type,name,acl') && e('project,项目主库,default'); // 获取项目ID为55的项目空间信息。

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
cid=16102

- 获取项目ID为11的项目空间信息。
 - 第101条的type属性 @project
 - 第101条的name属性 @项目主库
 - 第101条的acl属性 @default
- 获取项目ID为22的项目空间信息。
 - 第112条的type属性 @project
 - 第112条的name属性 @项目主库
 - 第112条的acl属性 @default
- 获取项目ID为33的项目空间信息。
 - 第123条的type属性 @project
 - 第123条的name属性 @项目主库
 - 第123条的acl属性 @default
- 获取项目ID为44的项目空间信息。
 - 第134条的type属性 @project
 - 第134条的name属性 @项目主库
 - 第134条的acl属性 @default
- 获取项目ID为55的项目空间信息。
 - 第145条的type属性 @project
 - 第145条的name属性 @项目主库
 - 第145条的acl属性 @default


*/

global $tester;
$tester->loadModel('doc');

r($tester->doc->getlibsofspace('project', 11)) && p('101:type,name,acl') && e('project,项目主库,default'); // 获取项目ID为11的项目空间信息。
r($tester->doc->getlibsofspace('project', 22)) && p('112:type,name,acl') && e('project,项目主库,default'); // 获取项目ID为22的项目空间信息。
r($tester->doc->getlibsofspace('project', 33)) && p('123:type,name,acl') && e('project,项目主库,default'); // 获取项目ID为33的项目空间信息。
r($tester->doc->getlibsofspace('project', 44)) && p('134:type,name,acl') && e('project,项目主库,default'); // 获取项目ID为44的项目空间信息。
r($tester->doc->getlibsofspace('project', 55)) && p('145:type,name,acl') && e('project,项目主库,default'); // 获取项目ID为55的项目空间信息。

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('user')->gen(10);
zenData('module')->loadYaml('module')->gen(4);
zenData('product')->loadYaml('product')->gen(2);

/**

title=bugModel->getModuleOwner();
timeout=0
cid=15386

- 没有模块，返回产品测试负责人 @admin

- 模块有负责人，返回模块负责人 @user1

- 模块没有负责人，没有上级模块，返回产品测试负责人 @admin

- 模块没有负责人，有上级模块，并且上级模块没有负责人，返回产品测试负责人 @user1

- 模块没有负责人，有上级模块，上级模块有负责人，返回上级模块负责人 @admin

- 没有模块，没有产品测试负责人，返回空 @0

*/

$moduleIDList  = array(0, 1, 2, 3, 4);
$productIDList = array(1, 2);

$bug=new bugModelTest();

r($bug->getModuleOwnerTest($moduleIDList[0], $productIDList[0]))    && p('0') && e('admin'); // 没有模块，返回产品测试负责人
r($bug->getModuleOwnerTest($moduleIDList[1], $productIDList[0]))    && p('0') && e('user1'); // 模块有负责人，返回模块负责人
r($bug->getModuleOwnerTest($moduleIDList[2], $productIDList[0]))    && p('0') && e('admin'); // 模块没有负责人，没有上级模块，返回产品测试负责人
r($bug->getModuleOwnerTest($moduleIDList[3], $productIDList[0]))    && p('0') && e('user1'); // 模块没有负责人，有上级模块，并且上级模块没有负责人，返回产品测试负责人
r($bug->getModuleOwnerTest($moduleIDList[4], $productIDList[0]))    && p('0') && e('admin'); // 模块没有负责人，有上级模块，上级模块有负责人，返回上级模块负责人
r($bug->getModuleOwnerTest($moduleIDList[0], $productIDList[1])[0]) && p()    && e('0');     // 没有模块，没有产品测试负责人，返回空
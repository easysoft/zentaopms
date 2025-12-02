#!/usr/bin/env php
<?php

/**

title=测试 docModel->setMenuByType();
timeout=0
cid=16153

- 测试空数据
 - 第45条的type属性 @mine
 - 第45条的name属性 @我的文档库45
- 设置我的文档库导航
 - 第45条的type属性 @mine
 - 第45条的name属性 @我的文档库45
- 设置项目文档库导航
 - 第16条的type属性 @project
 - 第16条的name属性 @项目文档主库16
- 设置执行文档库导航
 - 第20条的type属性 @execution
 - 第20条的name属性 @执行文档主库20
- 设置产品文档库导航
 - 第26条的type属性 @product
 - 第26条的name属性 @产品文档主库26
- 设置自定义文档库导航
 - 第37条的type属性 @custom
 - 第37条的name属性 @自定义文档库37

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';
zenData('project')->loadYaml('execution')->gen(10);
zenData('product')->loadYaml('product')->gen(5);
zenData('doclib')->loadYaml('doclib')->gen(50);
zenData('user')->gen(5);
su('admin');

$typeList     = array('', 'mine', 'project', 'execution', 'product', 'custom');
$objectIdList = array(0, 11, 101, 1);
$libIdList    = array(0, 11, 16, 20, 26, 6);
$appendList   = array(0, 1);

$docTester = new docTest();
r($docTester->setMenuByTypeTest($typeList[0], $objectIdList[0], $libIdList[1], $appendList[0])[0]) && p('45:type,name') && e('mine,我的文档库45');        // 测试空数据
r($docTester->setMenuByTypeTest($typeList[1], $objectIdList[0], $libIdList[1], $appendList[0])[0]) && p('45:type,name') && e('mine,我的文档库45');        // 设置我的文档库导航
r($docTester->setMenuByTypeTest($typeList[2], $objectIdList[1], $libIdList[2], $appendList[0])[0]) && p('16:type,name') && e('project,项目文档主库16');   // 设置项目文档库导航
r($docTester->setMenuByTypeTest($typeList[3], $objectIdList[2], $libIdList[3], $appendList[0])[0]) && p('20:type,name') && e('execution,执行文档主库20'); // 设置执行文档库导航
r($docTester->setMenuByTypeTest($typeList[4], $objectIdList[3], $libIdList[4], $appendList[0])[0]) && p('26:type,name') && e('product,产品文档主库26');   // 设置产品文档库导航
r($docTester->setMenuByTypeTest($typeList[5], $objectIdList[0], $libIdList[5], $appendList[0])[0]) && p('37:type,name') && e('custom,自定义文档库37');    // 设置自定义文档库导航

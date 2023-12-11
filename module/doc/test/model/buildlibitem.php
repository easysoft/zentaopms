#!/usr/bin/env php
<?php

/**

title=测试 docModel->buildLibItem();
cid=1

- 构造api文档库节点
 - 属性type @apiLib
 - 属性name @项目接口库1
 - 属性objectType @api
- 构造api文档库并且展示文档节点
 - 属性type @apiLib
 - 属性name @项目接口库1
 - 属性objectType @api
- 构造自定义文档库节点
 - 属性type @docLib
 - 属性name @自定义文档库6
 - 属性objectType @custom
- 构造自定义文档库并且展示文档节点
 - 属性type @docLib
 - 属性name @自定义文档库6
 - 属性objectType @custom
- 构造我的文档库节点
 - 属性type @docLib
 - 属性name @我的文档库11
 - 属性objectType @mine
- 构造我的文档库并且展示文档节点
 - 属性type @docLib
 - 属性name @我的文档库11
 - 属性objectType @mine
- 构造项目文档库节点
 - 属性type @docLib
 - 属性name @项目文档主库16
 - 属性objectType @project
- 构造项目文档库并且展示文档节点
 - 属性type @docLib
 - 属性name @项目文档主库16
 - 属性objectType @project
- 构造执行文档库节点
 - 属性type @docLib
 - 属性name @执行文档主库20
 - 属性objectType @execution
- 构造执行文档库并且展示文档节点
 - 属性type @docLib
 - 属性name @执行文档主库20
 - 属性objectType @execution
- 构造产品文档库节点
 - 属性type @docLib
 - 属性name @产品文档主库26
 - 属性objectType @product
- 构造产品文档库并且展示文档节点
 - 属性type @docLib
 - 属性name @产品文档主库26
 - 属性objectType @product

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('project')->config('execution')->gen(10);
zdTable('product')->config('product')->gen(5);
zdTable('module')->config('module')->gen(3);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$libID     = 1;
$libIds    = array(1, 6, 11, 16, 20, 26);
$types     = array('api', 'custom', 'mine', 'project', 'execution', 'product');
$moduleID  = 1;
$objectIds = array(0, 1, 11, 101);
$showDoc   = array(0, 1);

$docTester = new docTest();
r($docTester->buildLibItemTest($libID, $libIds[0], $types[0], $moduleID, $objectIds[2], $showDoc[0])) && p('type,name,objectType') && e('apiLib,项目接口库1,api');          // 构造api文档库节点
r($docTester->buildLibItemTest($libID, $libIds[0], $types[0], $moduleID, $objectIds[2], $showDoc[1])) && p('type,name,objectType') && e('apiLib,项目接口库1,api');          // 构造api文档库并且展示文档节点
r($docTester->buildLibItemTest($libID, $libIds[1], $types[1], $moduleID, $objectIds[0], $showDoc[0])) && p('type,name,objectType') && e('docLib,自定义文档库6,custom');     // 构造自定义文档库节点
r($docTester->buildLibItemTest($libID, $libIds[1], $types[1], $moduleID, $objectIds[0], $showDoc[1])) && p('type,name,objectType') && e('docLib,自定义文档库6,custom');     // 构造自定义文档库并且展示文档节点
r($docTester->buildLibItemTest($libID, $libIds[2], $types[2], $moduleID, $objectIds[1], $showDoc[0])) && p('type,name,objectType') && e('docLib,我的文档库11,mine');        // 构造我的文档库节点
r($docTester->buildLibItemTest($libID, $libIds[2], $types[2], $moduleID, $objectIds[1], $showDoc[1])) && p('type,name,objectType') && e('docLib,我的文档库11,mine');        // 构造我的文档库并且展示文档节点
r($docTester->buildLibItemTest($libID, $libIds[3], $types[3], $moduleID, $objectIds[3], $showDoc[0])) && p('type,name,objectType') && e('docLib,项目文档主库16,project');   // 构造项目文档库节点
r($docTester->buildLibItemTest($libID, $libIds[3], $types[3], $moduleID, $objectIds[3], $showDoc[1])) && p('type,name,objectType') && e('docLib,项目文档主库16,project');   // 构造项目文档库并且展示文档节点
r($docTester->buildLibItemTest($libID, $libIds[4], $types[4], $moduleID, $objectIds[2], $showDoc[0])) && p('type,name,objectType') && e('docLib,执行文档主库20,execution'); // 构造执行文档库节点
r($docTester->buildLibItemTest($libID, $libIds[4], $types[4], $moduleID, $objectIds[2], $showDoc[1])) && p('type,name,objectType') && e('docLib,执行文档主库20,execution'); // 构造执行文档库并且展示文档节点
r($docTester->buildLibItemTest($libID, $libIds[5], $types[5], $moduleID, $objectIds[3], $showDoc[0])) && p('type,name,objectType') && e('docLib,产品文档主库26,product');   // 构造产品文档库节点
r($docTester->buildLibItemTest($libID, $libIds[5], $types[5], $moduleID, $objectIds[3], $showDoc[1])) && p('type,name,objectType') && e('docLib,产品文档主库26,product');   // 构造产品文档库并且展示文档节点

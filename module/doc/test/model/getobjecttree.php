#!/usr/bin/env php
<?php

/**

title=测试 docModel->getObjectTree();
cid=1

- 获取api文档树 @0
- 获取搜索后的api文档树 @0
- 获取切换版本后的api文档树 @0
- 获取自定义文档树
 - 第6条的type属性 @docLib
 - 第6条的name属性 @自定义文档库6
 - 第6条的objectType属性 @custom
 - 第6条的objectID属性 @0
- 获取搜索后的自定义文档树
 - 第6条的type属性 @docLib
 - 第6条的name属性 @自定义文档库6
 - 第6条的objectType属性 @custom
 - 第6条的objectID属性 @0
- 获取切换版本后的自定义文档树
 - 第6条的type属性 @docLib
 - 第6条的name属性 @自定义文档库6
 - 第6条的objectType属性 @custom
 - 第6条的objectID属性 @0
- 获取我的文档树
 - 第11条的type属性 @docLib
 - 第11条的name属性 @我的文档库11
 - 第11条的objectType属性 @mine
 - 第11条的objectID属性 @0
- 获取搜索后的我的文档树
 - 第11条的type属性 @docLib
 - 第11条的name属性 @我的文档库11
 - 第11条的objectType属性 @mine
 - 第11条的objectID属性 @0
- 获取切换版本后的我的文档树
 - 第11条的type属性 @docLib
 - 第11条的name属性 @我的文档库11
 - 第11条的objectType属性 @mine
 - 第11条的objectID属性 @0
- 获取项目文档树
 - 第16条的type属性 @docLib
 - 第16条的name属性 @项目文档主库16
 - 第16条的objectType属性 @project
 - 第16条的objectID属性 @11
- 获取搜索后的项目文档树
 - 第16条的type属性 @docLib
 - 第16条的name属性 @项目文档主库16
 - 第16条的objectType属性 @project
 - 第16条的objectID属性 @11
- 获取切换版本后的项目文档树
 - 第16条的type属性 @docLib
 - 第16条的name属性 @项目文档主库16
 - 第16条的objectType属性 @project
 - 第16条的objectID属性 @11
- 获取执行文档树
 - 第20条的type属性 @docLib
 - 第20条的name属性 @执行文档主库20
 - 第20条的objectType属性 @execution
 - 第20条的objectID属性 @101
- 获取搜索后的执行文档树
 - 第20条的type属性 @docLib
 - 第20条的name属性 @执行文档主库20
 - 第20条的objectType属性 @execution
 - 第20条的objectID属性 @101
- 获取切换版本后的执行文档树
 - 第20条的type属性 @docLib
 - 第20条的name属性 @执行文档主库20
 - 第20条的objectType属性 @execution
 - 第20条的objectID属性 @101
- 获取产品文档树
 - 第26条的type属性 @docLib
 - 第26条的name属性 @产品文档主库26
 - 第26条的objectType属性 @product
 - 第26条的objectID属性 @1
- 获取搜索后的产品文档树
 - 第26条的type属性 @docLib
 - 第26条的name属性 @产品文档主库26
 - 第26条的objectType属性 @product
 - 第26条的objectID属性 @1
- 获取切换版本后的产品文档树
 - 第26条的type属性 @docLib
 - 第26条的name属性 @产品文档主库26
 - 第26条的objectType属性 @product
 - 第26条的objectID属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$userqueryTable = zdTable('userquery');
$userqueryTable->id->range('1-2');
$userqueryTable->sql->range("`(( 1 AND `title` LIKE '%文档%' ) AND ( 1 ))`");
$userqueryTable->gen(1);

zdTable('project')->config('execution')->gen(10);
zdTable('product')->config('product')->gen(5);
zdTable('module')->config('module')->gen(3);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('apilibrelease')->gen(0);
zdTable('user')->gen(5);
su('admin');

$libID       = 1;
$libIds      = array(1, 6, 11, 16, 20, 26);
$types       = array('api', 'custom', 'mine', 'project', 'execution', 'product');
$moduleID    = 1;
$objectIds   = array(0, 1, 11, 101);
$browseTypes = array('', 'bysearch', 'bysearch');
$queries     = array(0, 1);

$docTester = new docTest();
r($docTester->getObjectTreeTest($libID, $libIds, $types[0], $moduleID, $objectIds[2], $browseTypes[0], $queries[0])) && p()                                   && e('0');                                   // 获取api文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[0], $moduleID, $objectIds[2], $browseTypes[1], $queries[1])) && p()                                   && e('0');                                   // 获取搜索后的api文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[0], $moduleID, $objectIds[2], $browseTypes[2], $queries[1])) && p()                                   && e('0');                                   // 获取切换版本后的api文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[1], $moduleID, $objectIds[0], $browseTypes[0], $queries[0])) && p('6:type,name,objectType,objectID')  && e('docLib,自定义文档库6,custom,0');       // 获取自定义文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[1], $moduleID, $objectIds[0], $browseTypes[1], $queries[1])) && p('6:type,name,objectType,objectID')  && e('docLib,自定义文档库6,custom,0');       // 获取搜索后的自定义文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[1], $moduleID, $objectIds[0], $browseTypes[2], $queries[1])) && p('6:type,name,objectType,objectID')  && e('docLib,自定义文档库6,custom,0');       // 获取切换版本后的自定义文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[2], $moduleID, $objectIds[0], $browseTypes[0], $queries[0])) && p('11:type,name,objectType,objectID') && e('docLib,我的文档库11,mine,0');          // 获取我的文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[2], $moduleID, $objectIds[0], $browseTypes[1], $queries[1])) && p('11:type,name,objectType,objectID') && e('docLib,我的文档库11,mine,0');          // 获取搜索后的我的文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[2], $moduleID, $objectIds[0], $browseTypes[2], $queries[1])) && p('11:type,name,objectType,objectID') && e('docLib,我的文档库11,mine,0');          // 获取切换版本后的我的文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[3], $moduleID, $objectIds[2], $browseTypes[0], $queries[0])) && p('16:type,name,objectType,objectID') && e('docLib,项目文档主库16,project,11');    // 获取项目文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[3], $moduleID, $objectIds[2], $browseTypes[1], $queries[1])) && p('16:type,name,objectType,objectID') && e('docLib,项目文档主库16,project,11');    // 获取搜索后的项目文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[3], $moduleID, $objectIds[2], $browseTypes[2], $queries[1])) && p('16:type,name,objectType,objectID') && e('docLib,项目文档主库16,project,11');    // 获取切换版本后的项目文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[4], $moduleID, $objectIds[3], $browseTypes[0], $queries[0])) && p('20:type,name,objectType,objectID') && e('docLib,执行文档主库20,execution,101'); // 获取执行文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[4], $moduleID, $objectIds[3], $browseTypes[1], $queries[1])) && p('20:type,name,objectType,objectID') && e('docLib,执行文档主库20,execution,101'); // 获取搜索后的执行文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[4], $moduleID, $objectIds[3], $browseTypes[2], $queries[1])) && p('20:type,name,objectType,objectID') && e('docLib,执行文档主库20,execution,101'); // 获取切换版本后的执行文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[5], $moduleID, $objectIds[1], $browseTypes[0], $queries[0])) && p('26:type,name,objectType,objectID') && e('docLib,产品文档主库26,product,1');     // 获取产品文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[5], $moduleID, $objectIds[1], $browseTypes[1], $queries[1])) && p('26:type,name,objectType,objectID') && e('docLib,产品文档主库26,product,1');     // 获取搜索后的产品文档树
r($docTester->getObjectTreeTest($libID, $libIds, $types[5], $moduleID, $objectIds[1], $browseTypes[2], $queries[1])) && p('26:type,name,objectType,objectID') && e('docLib,产品文档主库26,product,1');     // 获取切换版本后的产品文档树

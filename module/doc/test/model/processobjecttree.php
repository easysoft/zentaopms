#!/usr/bin/env php
<?php

/**

title=测试 docModel->processObjectTree();
cid=16147

- 处理api文档库数据 @0
- 处理自定义文档库数据 @0
- 处理我的文档库数据 @0
- 处理项目文档库数据 @0
- 处理执行文档库数据
 - 属性name @附件库
 - 属性type @annex
 - 属性objectType @execution
 - 属性objectID @101
- 处理产品文档库数据
 - 属性name @附件库
 - 属性type @annex
 - 属性objectType @product
 - 属性objectID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('execution')->gen(10);
zenData('product')->loadYaml('product')->gen(5);
zenData('module')->loadYaml('module')->gen(3);
zenData('doclib')->loadYaml('doclib')->gen(30);
zenData('doc')->loadYaml('doc')->gen(50);
zenData('apilibrelease')->gen(0);
zenData('user')->gen(5);
su('admin');

$libID     = 1;
$types     = array('api', 'custom', 'mine', 'project', 'execution', 'product');
$objectIds = array(0, 1, 11, 101);

$docTester = new docModelTest();
r($docTester->processObjectTree($libID, $types[0], $objectIds[2])) && p()                                && e('0');                          // 处理api文档库数据
r($docTester->processObjectTree($libID, $types[1], $objectIds[0])) && p()                                && e('0');                          // 处理自定义文档库数据
r($docTester->processObjectTree($libID, $types[2], $objectIds[0])) && p()                                && e('0');                          // 处理我的文档库数据
r($docTester->processObjectTree($libID, $types[3], $objectIds[2])) && p()                                && e('0');                          // 处理项目文档库数据
r($docTester->processObjectTree($libID, $types[4], $objectIds[3])) && p('name,type,objectType,objectID') && e('附件库,annex,execution,101'); // 处理执行文档库数据
r($docTester->processObjectTree($libID, $types[5], $objectIds[1])) && p('name,type,objectType,objectID') && e('附件库,annex,product,1');     // 处理产品文档库数据

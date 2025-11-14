#!/usr/bin/env php
<?php

/**

title=测试 docZen::setSpacePageStorage();
timeout=0
cid=16226

- 测试自定义空间类型
 - 属性spaceType @custom
 - 属性cookieType @custom
 - 属性cookieObjectID @1
 - 属性cookieLibID @10
- 测试产品空间类型
 - 属性spaceType @product
 - 属性cookieType @product
 - 属性cookieObjectID @2
 - 属性cookieLibID @20
- 测试项目空间类型
 - 属性spaceType @project
 - 属性cookieType @project
 - 属性cookieObjectID @3
 - 属性cookieLibID @30
- 测试执行空间类型
 - 属性spaceType @execution
 - 属性cookieType @execution
 - 属性cookieBrowseType @draft
- 测试我的空间类型
 - 属性spaceType @mine
 - 属性cookieType @mine
 - 属性cookieLibID @50
- 测试空对象ID和库ID
 - 属性spaceType @custom
 - 属性cookieObjectID @0
 - 属性cookieLibID @0
 - 属性cookieModuleID @0
- 测试不同浏览类型
 - 属性cookieBrowseType @bySearch
 - 属性cookieParam @100
- 测试不同参数值
 - 属性cookieModuleID @20
 - 属性cookieParam @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->setSpacePageStorageTest('custom', 'all', 1, 10, 5, 0)) && p('spaceType,cookieType,cookieObjectID,cookieLibID') && e('custom,custom,1,10'); // 测试自定义空间类型
r($docTest->setSpacePageStorageTest('product', 'all', 2, 20, 8, 0)) && p('spaceType,cookieType,cookieObjectID,cookieLibID') && e('product,product,2,20'); // 测试产品空间类型
r($docTest->setSpacePageStorageTest('project', 'all', 3, 30, 10, 0)) && p('spaceType,cookieType,cookieObjectID,cookieLibID') && e('project,project,3,30'); // 测试项目空间类型
r($docTest->setSpacePageStorageTest('execution', 'draft', 4, 40, 12, 0)) && p('spaceType,cookieType,cookieBrowseType') && e('execution,execution,draft'); // 测试执行空间类型
r($docTest->setSpacePageStorageTest('mine', 'all', 0, 50, 0, 0)) && p('spaceType,cookieType,cookieLibID') && e('mine,mine,50'); // 测试我的空间类型
r($docTest->setSpacePageStorageTest('custom', 'all', 0, 0, 0, 0)) && p('spaceType,cookieObjectID,cookieLibID,cookieModuleID') && e('custom,0,0,0'); // 测试空对象ID和库ID
r($docTest->setSpacePageStorageTest('product', 'bySearch', 5, 60, 15, 100)) && p('cookieBrowseType,cookieParam') && e('bySearch,100'); // 测试不同浏览类型
r($docTest->setSpacePageStorageTest('project', 'bysearch', 6, 70, 20, 200)) && p('cookieModuleID,cookieParam') && e('20,200'); // 测试不同参数值
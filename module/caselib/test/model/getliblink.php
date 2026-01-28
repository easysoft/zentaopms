#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);

su('admin');

/**

title=测试 caselibModel->getLibLink();
timeout=0
cid=15532

- 测试获取模块 caselib 方法 browse 用例库 1.5 级下拉的链接
 - 属性isCaselibBrowse @1
 - 属性isItself @1
- 测试获取模块 caselib 方法 create 用例库 1.5 级下拉的链接
 - 属性isCaselibBrowse @1
 - 属性isItself @0
- 测试获取模块 caselib 方法 edit 用例库 1.5 级下拉的链接
 - 属性isCaselibBrowse @0
 - 属性isItself @1
- 测试获取模块 tree 方法 browse 用例库 1.5 级下拉的链接
 - 属性isCaselibBrowse @0
 - 属性isItself @1
- 测试获取模块 tree 方法 create 用例库 1.5 级下拉的链接
 - 属性isCaselibBrowse @0
 - 属性isItself @1
- 测试获取模块 tree 方法 edit 用例库 1.5 级下拉的链接
 - 属性isCaselibBrowse @0
 - 属性isItself @1

*/

$caselib = new caselibModelTest();

$moduleList = array('caselib', 'tree');
$methodList = array('browse', 'create', 'edit');

r($caselib->getLibLinkTest($moduleList[0], $methodList[0])) && p('isCaselibBrowse,isItself') && e('1,1'); // 测试获取模块 caselib 方法 browse 用例库 1.5 级下拉的链接
r($caselib->getLibLinkTest($moduleList[0], $methodList[1])) && p('isCaselibBrowse,isItself') && e('1,0'); // 测试获取模块 caselib 方法 create 用例库 1.5 级下拉的链接
r($caselib->getLibLinkTest($moduleList[0], $methodList[2])) && p('isCaselibBrowse,isItself') && e('0,1'); // 测试获取模块 caselib 方法 edit 用例库 1.5 级下拉的链接
r($caselib->getLibLinkTest($moduleList[1], $methodList[0])) && p('isCaselibBrowse,isItself') && e('0,1'); // 测试获取模块 tree 方法 browse 用例库 1.5 级下拉的链接
r($caselib->getLibLinkTest($moduleList[1], $methodList[1])) && p('isCaselibBrowse,isItself') && e('0,1'); // 测试获取模块 tree 方法 create 用例库 1.5 级下拉的链接
r($caselib->getLibLinkTest($moduleList[1], $methodList[2])) && p('isCaselibBrowse,isItself') && e('0,1'); // 测试获取模块 tree 方法 edit 用例库 1.5 级下拉的链接
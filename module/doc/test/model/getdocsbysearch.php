#!/usr/bin/env php
<?php

/**

title=测试 docModel->getDocsBySearch();
cid=1

- 根据搜索条件获取我的空间下文档
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 根据搜索条件获取我的空间下文档
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 根据搜索条件获取项目空间下文档
 - 第21条的lib属性 @16
 - 第21条的title属性 @项目文档21
- 根据搜索条件获取项目空间下文档
 - 第21条的lib属性 @16
 - 第21条的title属性 @项目文档21
- 根据搜索条件获取执行空间下文档
 - 第23条的lib属性 @20
 - 第23条的title属性 @执行文档23
- 根据搜索条件获取执行空间下文档
 - 第23条的lib属性 @20
 - 第23条的title属性 @执行文档23
- 根据搜索条件获取产品空间下文档
 - 第41条的lib属性 @26
 - 第41条的title属性 @产品文档41
- 根据搜索条件获取产品空间下文档
 - 第41条的lib属性 @26
 - 第41条的title属性 @产品文档41
- 根据搜索条件获取自定义空间下文档
 - 第11条的lib属性 @6
 - 第11条的title属性 @自定义文档11
- 根据搜索条件获取自定义空间下文档
 - 第11条的lib属性 @6
 - 第11条的title属性 @自定义文档11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$userqueryTable = zdTable('userquery');
$userqueryTable->id->range('1');
$userqueryTable->sql->range("`(( 1 AND `title` LIKE '%文档%' ) AND ( 1 ))`");
$userqueryTable->gen(1);

zdTable('project')->config('execution')->gen(10);
zdTable('product')->config('product')->gen(5);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$typeList     = array('mine', 'project', 'execution', 'product', 'custom');
$objectIdList = array(0, 11, 101, 1);
$libIdList    = array(11, 16, 20, 26, 6);
$queryIdList  = array(0, 1);

$docTester = new docTest();
r($docTester->getDocsBySearchTest($typeList[0], $objectIdList[0], $libIdList[0], $queryIdList[0])) && p('1:lib,title')  && e('11,我的文档1');   // 根据搜索条件获取我的空间下文档
r($docTester->getDocsBySearchTest($typeList[0], $objectIdList[0], $libIdList[0], $queryIdList[1])) && p('1:lib,title')  && e('11,我的文档1');   // 根据搜索条件获取我的空间下文档
r($docTester->getDocsBySearchTest($typeList[1], $objectIdList[1], $libIdList[1], $queryIdList[0])) && p('21:lib,title') && e('16,项目文档21');  // 根据搜索条件获取项目空间下文档
r($docTester->getDocsBySearchTest($typeList[1], $objectIdList[1], $libIdList[1], $queryIdList[1])) && p('21:lib,title') && e('16,项目文档21');  // 根据搜索条件获取项目空间下文档
r($docTester->getDocsBySearchTest($typeList[2], $objectIdList[2], $libIdList[2], $queryIdList[0])) && p('23:lib,title') && e('20,执行文档23');  // 根据搜索条件获取执行空间下文档
r($docTester->getDocsBySearchTest($typeList[2], $objectIdList[2], $libIdList[2], $queryIdList[1])) && p('23:lib,title') && e('20,执行文档23');  // 根据搜索条件获取执行空间下文档
r($docTester->getDocsBySearchTest($typeList[3], $objectIdList[3], $libIdList[3], $queryIdList[0])) && p('41:lib,title') && e('26,产品文档41');  // 根据搜索条件获取产品空间下文档
r($docTester->getDocsBySearchTest($typeList[3], $objectIdList[3], $libIdList[3], $queryIdList[1])) && p('41:lib,title') && e('26,产品文档41');  // 根据搜索条件获取产品空间下文档
r($docTester->getDocsBySearchTest($typeList[4], $objectIdList[0], $libIdList[4], $queryIdList[0])) && p('11:lib,title') && e('6,自定义文档11'); // 根据搜索条件获取自定义空间下文档
r($docTester->getDocsBySearchTest($typeList[4], $objectIdList[0], $libIdList[4], $queryIdList[1])) && p('11:lib,title') && e('6,自定义文档11'); // 根据搜索条件获取自定义空间下文档

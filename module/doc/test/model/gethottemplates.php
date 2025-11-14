#!/usr/bin/env php
<?php

/**

title=测试 docModel->getHotTemplates();
timeout=0
cid=16095

- 获取所有文档模板
 - 第0条的id属性 @1
 - 第0条的title属性 @产品模板1
- 获取产品范围所有文档模板
 - 第1条的id属性 @2
 - 第1条的title属性 @产品模板2
- 获取项目范围所有文档模板
 - 第0条的id属性 @6
 - 第0条的title属性 @项目模板6
- 获取所有范围下最新文档模板
 - 第0条的id属性 @1
 - 第0条的title属性 @产品模板1
- 获取产品范围下最新文档模板
 - 第0条的id属性 @1
 - 第0条的title属性 @产品模板1
- 获取项目范围下最新文档模板
 - 第0条的id属性 @6
 - 第0条的title属性 @项目模板6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->loadYaml('template')->gen(20);
zenData('module')->loadYaml('templatemodule')->gen(20);
zenData('user')->gen(5);
su('admin');

$scopeIdList = array(0, 1, 2);
$limits      = array(0, 1);

$docTester = new docTest();
r($docTester->getHotTemplatesTest($scopeIdList[0], $limits[0])) && p('0:id,title') && e('1,产品模板1');  // 获取所有文档模板
r($docTester->getHotTemplatesTest($scopeIdList[1], $limits[0])) && p('1:id,title') && e('2,产品模板2');  // 获取产品范围所有文档模板
r($docTester->getHotTemplatesTest($scopeIdList[2], $limits[0])) && p('0:id,title') && e('6,项目模板6');  // 获取项目范围所有文档模板
r($docTester->getHotTemplatesTest($scopeIdList[0], $limits[1])) && p('0:id,title') && e('1,产品模板1');  // 获取所有范围下最新文档模板
r($docTester->getHotTemplatesTest($scopeIdList[1], $limits[1])) && p('0:id,title') && e('1,产品模板1');  // 获取产品范围下最新文档模板
r($docTester->getHotTemplatesTest($scopeIdList[2], $limits[1])) && p('0:id,title') && e('6,项目模板6');  // 获取项目范围下最新文档模板

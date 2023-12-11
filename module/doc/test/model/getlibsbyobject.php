#!/usr/bin/env php
<?php

/**

title=测试 docModel->getLibsByObject();
cid=1

- 获取我的文档库
 - 第11条的type属性 @mine
 - 第11条的name属性 @我的文档库11
- 获取项目文档库
 - 第1条的type属性 @api
 - 第1条的name属性 @项目接口库1
- 获取执行文档库
 - 第20条的type属性 @execution
 - 第20条的name属性 @执行文档主库20
- 获取产品文档库
 - 第4条的type属性 @api
 - 第4条的name属性 @产品接口库4
- 获取自定义文档库
 - 第6条的type属性 @custom
 - 第6条的name属性 @自定义文档库6
- 获取我的文档库和id=1的文档库
 - 第1条的type属性 @api
 - 第1条的name属性 @项目接口库1
- 获取项目文档库和id=1的文档库
 - 第1条的type属性 @api
 - 第1条的name属性 @项目接口库1
- 获取执行文档库和id=1的文档库
 - 第1条的type属性 @api
 - 第1条的name属性 @项目接口库1
- 获取产品文档库和id=1的文档库
 - 第1条的type属性 @api
 - 第1条的name属性 @项目接口库1
- 获取自定义文档库和id=1的文档库
 - 第1条的type属性 @api
 - 第1条的name属性 @项目接口库1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('project')->config('execution')->gen(10);
zdTable('product')->config('product')->gen(5);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('user')->gen(5);
su('admin');

$typeList     = array('mine', 'project', 'execution', 'product', 'custom');
$objectIdList = array(0, 11, 101, 1);
$appendLibs   = array(0, 1);

$docTester = new docTest();
r($docTester->getLibsByObjectTest($typeList[0], $objectIdList[0], $appendLibs[0])) && p('11:type,name') && e('mine,我的文档库11');        // 获取我的文档库
r($docTester->getLibsByObjectTest($typeList[1], $objectIdList[1], $appendLibs[0])) && p('1:type,name')  && e('api,项目接口库1');          // 获取项目文档库
r($docTester->getLibsByObjectTest($typeList[2], $objectIdList[2], $appendLibs[0])) && p('20:type,name') && e('execution,执行文档主库20'); // 获取执行文档库
r($docTester->getLibsByObjectTest($typeList[3], $objectIdList[3], $appendLibs[0])) && p('4:type,name')  && e('api,产品接口库4');          // 获取产品文档库
r($docTester->getLibsByObjectTest($typeList[4], $objectIdList[0], $appendLibs[0])) && p('6:type,name')  && e('custom,自定义文档库6');     // 获取自定义文档库
r($docTester->getLibsByObjectTest($typeList[0], $objectIdList[0], $appendLibs[1])) && p('1:type,name')  && e('api,项目接口库1');          // 获取我的文档库和id=1的文档库
r($docTester->getLibsByObjectTest($typeList[1], $objectIdList[1], $appendLibs[1])) && p('1:type,name')  && e('api,项目接口库1');          // 获取项目文档库和id=1的文档库
r($docTester->getLibsByObjectTest($typeList[2], $objectIdList[2], $appendLibs[1])) && p('1:type,name')  && e('api,项目接口库1');          // 获取执行文档库和id=1的文档库
r($docTester->getLibsByObjectTest($typeList[3], $objectIdList[3], $appendLibs[1])) && p('1:type,name')  && e('api,项目接口库1');          // 获取产品文档库和id=1的文档库
r($docTester->getLibsByObjectTest($typeList[4], $objectIdList[0], $appendLibs[1])) && p('1:type,name')  && e('api,项目接口库1');          // 获取自定义文档库和id=1的文档库

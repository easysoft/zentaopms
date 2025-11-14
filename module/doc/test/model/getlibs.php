#!/usr/bin/env php
<?php

/**

title=测试 docModel->getLibs();
timeout=0
cid=16100

- 获取系统中所有的文档库属性6 @自定义文档库6
- 查看id为22的文档库属性22 @敏捷项目1 / 迭代7 / 执行文档主库22
- 获取系统中包括id=1的文档库属性1 @项目接口库1
- 获取系统中所有除了我的的文档库属性8 @自定义文档库8
- 获取系统中包括已删除的文档库属性11 @我的文档库11
- 查看id为30的文档库属性30 @产品5 / 产品文档主库30
- 获取系统中包括id=1的文档库属性1 @项目接口库1
- 获取系统中带有对象名字的包括已删除的非我的文档库属性6 @自定义文档库6
- 获取系统中有api的文档库属性6 @自定义文档库6
- 获取系统中包括id=1的文档库属性1 @项目接口库1
- 获取系统中有api的非我的文档库属性8 @自定义文档库8
- 获取产品下的文档库属性26 @产品文档主库26
- 获取产品下的带对象名字的文档库属性26 @产品1 / 产品文档主库26
- 获取产品下包括id=1的文档库属性1 @项目接口库1
- 获取产品下的非我的文档库属性26 @产品文档主库26
- 获取项目下的文档库属性16 @项目文档主库16
- 获取项目下的带对象名字的文档库属性16 @敏捷项目1 / 项目文档主库16
- 获取项目下包括id=1的文档库属性1 @项目接口库1
- 获取项目下的非我的文档库属性16 @项目文档主库16
- 获取执行下的文档库属性20 @执行文档主库20
- 获取执行下的带对象名字的文档库属性20 @敏捷项目1 / 迭代5 / 执行文档主库20
- 获取执行下包括id=1的文档库属性1 @项目接口库1
- 获取执行下的非我的文档库属性20 @执行文档主库20
- 查询自定义文档库属性7 @自定义文档库7
- 查询自定义文档库属性10 @产品接口库5 / 自定义文档库10
- 获取包括id=1的文档库属性1 @项目接口库1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('project')->loadYaml('execution')->gen(10);
zenData('product')->loadYaml('product')->gen(5);
zenData('doclib')->loadYaml('doclib')->gen(30);
zenData('user')->gen(5);
su('admin');

$types        = array('all', 'includeDeleted', 'hasApi', 'product', 'project', 'execution', 'custom', 'mine');
$extras       = array('', 'withObject');
$appendLibs   = array('0', '1');
$objectIds    = array(0, 1, 11, 101);
$excludeTypes = array('', 'mine');

$docTester = new docTest();
r($docTester->getLibsTest($types[0], $extras[0], $appendLibs[0], $objectIds[0], $excludeTypes[0])) && p('6')  && e('自定义文档库6');                      // 获取系统中所有的文档库
r($docTester->getLibsTest($types[0], $extras[1], $appendLibs[0], $objectIds[0], $excludeTypes[0])) && p('22') && e('敏捷项目1 / 迭代7 / 执行文档主库22'); // 查看id为22的文档库
r($docTester->getLibsTest($types[0], $extras[0], $appendLibs[1], $objectIds[0], $excludeTypes[0])) && p('1')  && e('项目接口库1	');                       // 获取系统中包括id=1的文档库
r($docTester->getLibsTest($types[0], $extras[0], $appendLibs[0], $objectIds[0], $excludeTypes[1])) && p('8')  && e('自定义文档库8');                      // 获取系统中所有除了我的的文档库
r($docTester->getLibsTest($types[1], $extras[0], $appendLibs[0], $objectIds[0], $excludeTypes[0])) && p('11') && e('我的文档库11');                       // 获取系统中包括已删除的文档库
r($docTester->getLibsTest($types[1], $extras[1], $appendLibs[0], $objectIds[0], $excludeTypes[0])) && p('30') && e('产品5 / 产品文档主库30');             // 查看id为30的文档库
r($docTester->getLibsTest($types[1], $extras[0], $appendLibs[1], $objectIds[0], $excludeTypes[0])) && p('1')  && e('项目接口库1	');                       // 获取系统中包括id=1的文档库
r($docTester->getLibsTest($types[1], $extras[0], $appendLibs[0], $objectIds[0], $excludeTypes[1])) && p('6')  && e('自定义文档库6');                      // 获取系统中带有对象名字的包括已删除的非我的文档库
r($docTester->getLibsTest($types[2], $extras[0], $appendLibs[0], $objectIds[0], $excludeTypes[0])) && p('6')  && e('自定义文档库6');                      // 获取系统中有api的文档库
r($docTester->getLibsTest($types[2], $extras[0], $appendLibs[1], $objectIds[0], $excludeTypes[0])) && p('1')  && e('项目接口库1	');                       // 获取系统中包括id=1的文档库
r($docTester->getLibsTest($types[2], $extras[0], $appendLibs[0], $objectIds[0], $excludeTypes[1])) && p('8')  && e('自定义文档库8');                      // 获取系统中有api的非我的文档库
r($docTester->getLibsTest($types[3], $extras[0], $appendLibs[0], $objectIds[1], $excludeTypes[0])) && p('26') && e('产品文档主库26');                     // 获取产品下的文档库
r($docTester->getLibsTest($types[3], $extras[1], $appendLibs[0], $objectIds[1], $excludeTypes[0])) && p('26') && e('产品1 / 产品文档主库26');             // 获取产品下的带对象名字的文档库
r($docTester->getLibsTest($types[3], $extras[0], $appendLibs[1], $objectIds[0], $excludeTypes[0])) && p('1')  && e('项目接口库1	');                       // 获取产品下包括id=1的文档库
r($docTester->getLibsTest($types[3], $extras[0], $appendLibs[0], $objectIds[1], $excludeTypes[1])) && p('26') && e('产品文档主库26');                     // 获取产品下的非我的文档库
r($docTester->getLibsTest($types[4], $extras[0], $appendLibs[0], $objectIds[2], $excludeTypes[0])) && p('16') && e('项目文档主库16');                     // 获取项目下的文档库
r($docTester->getLibsTest($types[4], $extras[1], $appendLibs[0], $objectIds[2], $excludeTypes[0])) && p('16') && e('敏捷项目1 / 项目文档主库16');         // 获取项目下的带对象名字的文档库
r($docTester->getLibsTest($types[4], $extras[0], $appendLibs[1], $objectIds[0], $excludeTypes[0])) && p('1')  && e('项目接口库1	');                       // 获取项目下包括id=1的文档库
r($docTester->getLibsTest($types[4], $extras[0], $appendLibs[0], $objectIds[2], $excludeTypes[1])) && p('16') && e('项目文档主库16');                     // 获取项目下的非我的文档库
r($docTester->getLibsTest($types[5], $extras[0], $appendLibs[0], $objectIds[3], $excludeTypes[0])) && p('20') && e('执行文档主库20');                     // 获取执行下的文档库
r($docTester->getLibsTest($types[5], $extras[1], $appendLibs[0], $objectIds[3], $excludeTypes[0])) && p('20') && e('敏捷项目1 / 迭代5 / 执行文档主库20'); // 获取执行下的带对象名字的文档库
r($docTester->getLibsTest($types[5], $extras[0], $appendLibs[1], $objectIds[0], $excludeTypes[0])) && p('1')  && e('项目接口库1	');                       // 获取执行下包括id=1的文档库
r($docTester->getLibsTest($types[5], $extras[0], $appendLibs[0], $objectIds[3], $excludeTypes[1])) && p('20') && e('执行文档主库20');                     // 获取执行下的非我的文档库
r($docTester->getLibsTest($types[6], $extras[0], $appendLibs[0], $objectIds[0], $excludeTypes[0])) && p('7')  && e('自定义文档库7');                      // 查询自定义文档库
r($docTester->getLibsTest($types[6], $extras[1], $appendLibs[0], $objectIds[0], $excludeTypes[0])) && p('10') && e('产品接口库5 / 自定义文档库10');       // 查询自定义文档库
r($docTester->getLibsTest($types[7], $extras[0], $appendLibs[1], $objectIds[0], $excludeTypes[0])) && p('1')  && e('项目接口库1	');                       // 获取包括id=1的文档库

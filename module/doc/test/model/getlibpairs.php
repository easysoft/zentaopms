#!/usr/bin/env php
<?php

/**

title=测试 docModel->getLibPairs();
timeout=0
cid=1

- 获取系统中所有的文档库属性6 @自定义文档库6
- 获取系统中带对象名字的所有的文档库属性21 @敏捷项目1 / 迭代6 / 执行文档主库21
- 获取系统中所有除了我的的文档库属性8 @自定义文档库8
- 获取系统中包括已删除的文档库属性11 @我的文档库11
- 获取系统中带有对象名字的包括已删除的文档库属性16 @敏捷项目1 / 项目文档主库16
- 获取系统中带有对象名字的包括已删除的非我的文档库属性6 @自定义文档库6
- 获取系统中有api的文档库属性6 @自定义文档库6
- 获取系统中带对象名字的有api的文档库属性5 @产品2 / 产品接口库5
- 获取系统中有api的非我的文档库属性8 @自定义文档库8
- 获取产品下的文档库属性26 @产品文档主库26
- 获取产品下的带对象名字的文档库属性26 @产品1 / 产品文档主库26
- 获取产品下的非我的文档库属性26 @产品文档主库26
- 获取项目下的文档库属性16 @项目文档主库16
- 获取项目下的带对象名字的文档库属性16 @敏捷项目1 / 项目文档主库16
- 获取项目下的非我的文档库属性16 @项目文档主库16
- 获取执行下的文档库属性20 @执行文档主库20
- 获取执行下的带对象名字的文档库属性20 @敏捷项目1 / 迭代5 / 执行文档主库20
- 获取执行下的非我的文档库属性20 @执行文档主库20
- 获取自定义的文档库属性6 @自定义文档库6
- 获取带对象名字的自定义的文档库属性7 @项目接口库2 / 自定义文档库7
- 获取非我的自定义的文档库属性8 @自定义文档库8
- 获取我的文档库属性11 @我的文档库11
- 获取带对象名字的我的文档库属性11 @自定义文档库6 / 我的文档库11
- 获取非我的文档库属性11 @我的文档库11

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
$objectIds    = array(0, 1, 11, 101);
$excludeTypes = array('', 'mine');

$docTester = new docTest();
r($docTester->getLibPairsTest($types[0], $extras[0], $objectIds[0], $excludeTypes[0])) && p('6')  && e('自定义文档库6');                      // 获取系统中所有的文档库
r($docTester->getLibPairsTest($types[0], $extras[1], $objectIds[0], $excludeTypes[0])) && p('21') && e('敏捷项目1 / 迭代6 / 执行文档主库21'); // 获取系统中带对象名字的所有的文档库
r($docTester->getLibPairsTest($types[0], $extras[0], $objectIds[0], $excludeTypes[1])) && p('8')  && e('自定义文档库8');                      // 获取系统中所有除了我的的文档库
r($docTester->getLibPairsTest($types[1], $extras[0], $objectIds[0], $excludeTypes[0])) && p('11') && e('我的文档库11');                       // 获取系统中包括已删除的文档库
r($docTester->getLibPairsTest($types[1], $extras[1], $objectIds[0], $excludeTypes[0])) && p('16') && e('敏捷项目1 / 项目文档主库16');         // 获取系统中带有对象名字的包括已删除的文档库
r($docTester->getLibPairsTest($types[1], $extras[0], $objectIds[0], $excludeTypes[1])) && p('6')  && e('自定义文档库6');                      // 获取系统中带有对象名字的包括已删除的非我的文档库
r($docTester->getLibPairsTest($types[2], $extras[0], $objectIds[0], $excludeTypes[0])) && p('6')  && e('自定义文档库6');                      // 获取系统中有api的文档库
r($docTester->getLibPairsTest($types[2], $extras[1], $objectIds[0], $excludeTypes[0])) && p('5')  && e('产品2 / 产品接口库5');                // 获取系统中带对象名字的有api的文档库
r($docTester->getLibPairsTest($types[2], $extras[0], $objectIds[0], $excludeTypes[1])) && p('8')  && e('自定义文档库8');                      // 获取系统中有api的非我的文档库
r($docTester->getLibPairsTest($types[3], $extras[0], $objectIds[1], $excludeTypes[0])) && p('26') && e('产品文档主库26');                     // 获取产品下的文档库
r($docTester->getLibPairsTest($types[3], $extras[1], $objectIds[1], $excludeTypes[0])) && p('26') && e('产品1 / 产品文档主库26');             // 获取产品下的带对象名字的文档库
r($docTester->getLibPairsTest($types[3], $extras[0], $objectIds[1], $excludeTypes[1])) && p('26') && e('产品文档主库26');                     // 获取产品下的非我的文档库
r($docTester->getLibPairsTest($types[4], $extras[0], $objectIds[2], $excludeTypes[0])) && p('16') && e('项目文档主库16');                     // 获取项目下的文档库
r($docTester->getLibPairsTest($types[4], $extras[1], $objectIds[2], $excludeTypes[0])) && p('16') && e('敏捷项目1 / 项目文档主库16');         // 获取项目下的带对象名字的文档库
r($docTester->getLibPairsTest($types[4], $extras[0], $objectIds[2], $excludeTypes[1])) && p('16') && e('项目文档主库16');                     // 获取项目下的非我的文档库
r($docTester->getLibPairsTest($types[5], $extras[0], $objectIds[3], $excludeTypes[0])) && p('20') && e('执行文档主库20');                     // 获取执行下的文档库
r($docTester->getLibPairsTest($types[5], $extras[1], $objectIds[3], $excludeTypes[0])) && p('20') && e('敏捷项目1 / 迭代5 / 执行文档主库20'); // 获取执行下的带对象名字的文档库
r($docTester->getLibPairsTest($types[5], $extras[0], $objectIds[3], $excludeTypes[1])) && p('20') && e('执行文档主库20');                     // 获取执行下的非我的文档库
r($docTester->getLibPairsTest($types[6], $extras[0], $objectIds[0], $excludeTypes[0])) && p('6')  && e('自定义文档库6');                      // 获取自定义的文档库
r($docTester->getLibPairsTest($types[6], $extras[1], $objectIds[0], $excludeTypes[0])) && p('7')  && e('项目接口库2 / 自定义文档库7');        // 获取带对象名字的自定义的文档库
r($docTester->getLibPairsTest($types[6], $extras[0], $objectIds[0], $excludeTypes[1])) && p('8')  && e('自定义文档库8');                      // 获取非我的自定义的文档库
r($docTester->getLibPairsTest($types[7], $extras[0], $objectIds[0], $excludeTypes[0])) && p('11') && e('我的文档库11');                       // 获取我的文档库
r($docTester->getLibPairsTest($types[7], $extras[1], $objectIds[0], $excludeTypes[0])) && p('11') && e('自定义文档库6 / 我的文档库11');       // 获取带对象名字的我的文档库
r($docTester->getLibPairsTest($types[7], $extras[0], $objectIds[0], $excludeTypes[1])) && p('11') && e('我的文档库11');                       // 获取非我的文档库

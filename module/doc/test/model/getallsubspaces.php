#!/usr/bin/env php
<?php
/**

title=测试 docModel->getAllSubSpaces();
cid=16069

- 测试获取项目接口库1属性mine.1 @我的空间/项目接口库1
- 测试获取自定义文档库6属性mine.6 @我的空间/自定义文档库6
- 测试获取项目接口库2属性custom.2 @团队空间/项目接口库2
- 测试获取自定义文档库7属性custom.7 @团队空间/自定义文档库7
- 测试获取产品1属性product.1 @产品空间/产品1
- 测试获取敏捷项目1属性project.11 @项目空间/敏捷项目1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$docLibData = zenData('doclib')->loadYaml('doclib');
$docLibData->type->range('mine,custom,product,project,execution');
$docLibData->parent->range('0');
$docLibData->gen(10);

zenData('product')->loadYaml('product')->gen(10);
zenData('project')->loadYaml('execution')->gen(10);
zenData('user')->gen(5);
su('admin');

$typeList    = array('all', 'mine', 'custom', 'product', 'project', 'execution');
$spaceIdList = array(0, 1, 2, 3, 4, 101);

$docTester = new docModelTest();
r($docTester->objectModel->getAllSubSpaces()) && p('mine.1')     && e('我的空间/项目接口库1');   // 测试获取项目接口库1
r($docTester->objectModel->getAllSubSpaces()) && p('mine.6')     && e('我的空间/自定义文档库6'); // 测试获取自定义文档库6
r($docTester->objectModel->getAllSubSpaces()) && p('custom.2')   && e('团队空间/项目接口库2');   // 测试获取项目接口库2
r($docTester->objectModel->getAllSubSpaces()) && p('custom.7')   && e('团队空间/自定义文档库7'); // 测试获取自定义文档库7
r($docTester->objectModel->getAllSubSpaces()) && p('product.1')  && e('产品空间/产品1');         // 测试获取产品1
r($docTester->objectModel->getAllSubSpaces()) && p('project.11') && e('项目空间/敏捷项目1');     // 测试获取敏捷项目1

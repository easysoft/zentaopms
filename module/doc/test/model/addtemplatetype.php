#!/usr/bin/env php
<?php

/**

title=测试 docModel->addTemplateType();
timeout=0
cid=16041

- 添加产品模板类型
 - 属性short @custom1
 - 属性path @,1,
- 添加产品子模板类型
 - 属性short @custom2
 - 属性path @,1,2,
- 添加项目模板类型
 - 属性short @custom3
 - 属性path @,3,
- 添加项目子模板类型
 - 属性short @custom4
 - 属性path @,3,4,
- 添加执行模板类型
 - 属性short @custom5
 - 属性path @,5,
- 添加执行子模板类型
 - 属性short @custom6
 - 属性path @,5,6,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('module')->gen(0);
zenData('user')->gen(5);
su('admin');

$productModule      = array('name' => '产品计划',     'root' => 1, 'grade' => 1, 'order' => 10, 'type' => 'doctemplate');
$productSubModule   = array('name' => '软件产品计划', 'root' => 1, 'grade' => 2, 'order' => 20, 'type' => 'doctemplate', 'parent' => 1);
$projectModule      = array('name' => '项目计划',     'root' => 2, 'grade' => 1, 'order' => 10, 'type' => 'doctemplate');
$projectSubModule   = array('name' => '质量保证计划', 'root' => 2, 'grade' => 2, 'order' => 20, 'type' => 'doctemplate', 'parent' => 3);
$executionModule    = array('name' => '开发计划',       'root' => 3, 'grade' => 1, 'order' => 10, 'type' => 'doctemplate');
$executionSubModule = array('name' => '软件需求说明书', 'root' => 3, 'grade' => 2, 'order' => 20, 'type' => 'doctemplate', 'parent' => 5);

$docTester = new docModelTest();
r($docTester->addTemplateTypeTest($productModule))      && p('short|path', '|') && e('custom1|,1,');   // 添加产品模板类型
r($docTester->addTemplateTypeTest($productSubModule))   && p('short|path', '|') && e('custom2|,1,2,'); // 添加产品子模板类型
r($docTester->addTemplateTypeTest($projectModule))      && p('short|path', '|') && e('custom3|,3,');   // 添加项目模板类型
r($docTester->addTemplateTypeTest($projectSubModule))   && p('short|path', '|') && e('custom4|,3,4,'); // 添加项目子模板类型
r($docTester->addTemplateTypeTest($executionModule))    && p('short|path', '|') && e('custom5|,5,');   // 添加执行模板类型
r($docTester->addTemplateTypeTest($executionSubModule)) && p('short|path', '|') && e('custom6|,5,6,'); // 添加执行子模板类型

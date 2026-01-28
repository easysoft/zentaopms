#!/usr/bin/env php
<?php

/**

title=测试 docModel->checkIsTemplateUpgraded();
timeout=0
cid=16052

- 检查文档模板已升级 @1
- 检查文档模板已升级 @1
- 检查文档模板已升级 @1
- 检查文档模板未升级 @0
- 检查文档模板未升级 @0
- 检查文档模板未升级 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('module')->loadYaml('templatemodule')->gen(10);
zenData('user')->gen(5);
su('admin');

$docTester = new docModelTest();
r($docTester->checkIsTemplateUpgradedTest()) && p('') && e('1'); // 检查文档模板已升级
r($docTester->checkIsTemplateUpgradedTest()) && p('') && e('1'); // 检查文档模板已升级
r($docTester->checkIsTemplateUpgradedTest()) && p('') && e('1'); // 检查文档模板已升级

zenData('module')->gen(0);
r($docTester->checkIsTemplateUpgradedTest()) && p('') && e('0'); // 检查文档模板未升级
r($docTester->checkIsTemplateUpgradedTest()) && p('') && e('0'); // 检查文档模板未升级
r($docTester->checkIsTemplateUpgradedTest()) && p('') && e('0'); // 检查文档模板未升级

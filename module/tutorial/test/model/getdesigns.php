#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getDesigns();
timeout=0
cid=19422

- 执行tutorialTest模块的getDesignsTest方法  @1
- 执行tutorialTest模块的getDesignsTest方法 第1条的id属性 @1
- 执行tutorialTest模块的getDesignsTest方法 第1条的name属性 @Test Design
- 执行tutorialTest模块的getDesignsTest方法 第1条的type属性 @HLDS
- 执行tutorialTest模块的getDesignsTest方法 第1条的project属性 @2

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$tutorialTest = new tutorialModelTest();

// 4. 执行测试步骤
r(count($tutorialTest->getDesignsTest())) && p() && e('1');
r($tutorialTest->getDesignsTest()) && p('1:id') && e('1');
r($tutorialTest->getDesignsTest()) && p('1:name') && e('Test Design');
r($tutorialTest->getDesignsTest()) && p('1:type') && e('HLDS');
r($tutorialTest->getDesignsTest()) && p('1:project') && e('2');
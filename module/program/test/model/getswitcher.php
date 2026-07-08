#!/usr/bin/env php
<?php

/**

title=测试 programModel::getSwitcher();
timeout=0
cid=17699

- 步骤1：项目集1的下拉菜单包含项目集名称 @1
- 步骤2：项目集2的下拉菜单包含项目集名称 @1
- 步骤3：项目集3的下拉菜单包含项目集名称 @1
- 步骤4：项目集4的下拉菜单包含项目集名称 @1
- 步骤5：项目集5的下拉菜单包含项目集名称 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('program')->gen(10);
su('admin');

$programTester = new programModelTest();

r($programTester->getSwitcherTest(1)) && p() && e('1'); // 步骤1：项目集1的下拉菜单包含项目集名称
r($programTester->getSwitcherTest(2)) && p() && e('1'); // 步骤2：项目集2的下拉菜单包含项目集名称
r($programTester->getSwitcherTest(3)) && p() && e('1'); // 步骤3：项目集3的下拉菜单包含项目集名称
r($programTester->getSwitcherTest(4)) && p() && e('1'); // 步骤4：项目集4的下拉菜单包含项目集名称
r($programTester->getSwitcherTest(5)) && p() && e('1'); // 步骤5：项目集5的下拉菜单包含项目集名称

#!/usr/bin/env php
<?php

/**

title=测试 devModel::getModules();
timeout=0
cid=0

- 测试步骤1：验证返回结果是数组且包含分组
 - 属性hasGroups @1
 - 属性validStructure @1
- 测试步骤2：验证admin分组包含预期模块属性admin @action
- 测试步骤3：验证product分组包含预期模块属性product @branch
- 测试步骤4：验证排除模块功能正常
 - 属性common @0
 - 属性editor @0
 - 属性help @0
 - 属性setting @0
- 测试步骤5：验证扩展路径模块发现功能 @95
- 测试步骤6：验证模块分组分类正确性 @16
- 测试步骤7：验证模块结构完整性属性groupCount @16

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

su('admin');

$dev = new devTest();

r($dev->getModulesStructureTest()) && p('hasGroups,validStructure') && e('1,1'); // 测试步骤1：验证返回结果是数组且包含分组
r($dev->getModulesTest()) && p('admin') && e('action'); // 测试步骤2：验证admin分组包含预期模块
r($dev->getModulesTest()) && p('product') && e('branch'); // 测试步骤3：验证product分组包含预期模块
r($dev->getModulesExcludeTest()) && p('common,editor,help,setting') && e('0,0,0,0'); // 测试步骤4：验证排除模块功能正常
r($dev->getModulesWithExtensionTest()) && p() && e('95'); // 测试步骤5：验证扩展路径模块发现功能
r($dev->getModulesGroupCountTest()) && p() && e('16'); // 测试步骤6：验证模块分组分类正确性
r($dev->getModulesStructureTest()) && p('groupCount') && e('16'); // 测试步骤7：验证模块结构完整性
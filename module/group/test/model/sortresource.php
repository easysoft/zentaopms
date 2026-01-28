#!/usr/bin/env php
<?php

/**

title=测试 groupModel::sortResource();
timeout=0
cid=0

- 测试步骤1：验证program和personnel模块的排序
 - 属性2 @program
 - 属性3 @personnel
- 测试步骤2：验证my模块方法的排序
 - 属性4 @project
 - 属性12 @audit
- 测试步骤3：验证project模块方法的排序
 -  @index
 - 属性1 @browse
 - 属性2 @kanban
- 测试步骤4：验证排序后模块总数保持不变 @121
- 测试步骤5：验证product模块方法总数保持不变 @24

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$group = new groupModelTest();
$resource = $group->sortResourceTest();

r(array_keys((array)$resource)) && p('2,3') && e('program,personnel');                     // 测试步骤1：验证program和personnel模块的排序
r(array_keys((array)$resource->my)) && p('4,12') && e('project,audit');                   // 测试步骤2：验证my模块方法的排序
r(array_keys((array)$resource->project)) && p('0,1,2') && e('index,browse,kanban');       // 测试步骤3：验证project模块方法的排序
r(count((array)$resource)) && p() && e('121');                                             // 测试步骤4：验证排序后模块总数保持不变
r(count((array)$resource->product)) && p() && e('24');                                     // 测试步骤5：验证product模块方法总数保持不变
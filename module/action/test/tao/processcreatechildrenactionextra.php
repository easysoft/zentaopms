#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processCreateChildrenActionExtra();
timeout=0
cid=14961

- 步骤1：单个任务ID属性extra @#1 实现用户登录功能
- 步骤2：多个任务ID属性extra @#1 实现用户登录功能, #2 开发API接口, #3 编写单元测试
- 步骤3：不存在的任务ID属性extra @~~
- 步骤4：空字符串属性extra @~~
- 步骤5：混合存在和不存在的任务ID属性extra @#1 实现用户登录功能, #2 开发API接口

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->name->range('实现用户登录功能,开发API接口,编写单元测试,修复Bug问题,数据库设计,前端页面开发,系统集成测试,性能优化,代码审查,文档编写');
$taskTable->type->range('devel{5},test{3},design{2}');
$taskTable->status->range('wait{3},doing{4},done{2},cancel{1}');
$taskTable->project->range('1-3');
$taskTable->execution->range('1-3');
$taskTable->gen(10);

su('admin');

$actionTest = new actionTest();

r($actionTest->processCreateChildrenActionExtraTest('1')) && p('extra') && e('#1 实现用户登录功能');
r($actionTest->processCreateChildrenActionExtraTest('1,2,3')) && p('extra') && e('#1 实现用户登录功能, #2 开发API接口, #3 编写单元测试');
r($actionTest->processCreateChildrenActionExtraTest('999')) && p('extra') && e('');
r($actionTest->processCreateChildrenActionExtraTest('')) && p('extra') && e('');
r($actionTest->processCreateChildrenActionExtraTest('1,999,2')) && p('extra') && e('#1 实现用户登录功能, #2 开发API接口');
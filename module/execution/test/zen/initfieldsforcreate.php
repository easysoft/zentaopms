#!/usr/bin/env php
<?php

/**

title=测试 executionZen::initFieldsForCreate();
timeout=0
cid=0

- 步骤1：正常项目ID，默认参数
 - 属性project @1
 - 属性type @sprint
 - 属性name @
 - 属性code @
 - 属性team @private
 - 属性acl @
 - 属性whitelist @
- 步骤2：指定type为sprint
 - 属性project @2
 - 属性type @sprint
- 步骤3：指定type为stage
 - 属性project @3
 - 属性type @stage
- 步骤4：项目ID为0的边界值
 - 属性project @0
 - 属性type @sprint
 - 属性name @
 - 属性code @
 - 属性team @private
 - 属性acl @
 - 属性whitelist @
- 步骤5：负数项目ID
 - 属性project @-1
 - 属性type @sprint
 - 属性name @
 - 属性code @
 - 属性team @private
 - 属性acl @
 - 属性whitelist @

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目1,项目2,项目3,敏捷项目4,敏捷项目5');
$table->code->range('project1,project2,project3,agile4,agile5');
$table->type->range('project{3},sprint{2}');
$table->status->range('wait,doing,suspended,closed,wait');
$table->acl->range('open,private,custom,open,private');
$table->deleted->range('0');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$executionTest = new executionZenTest();

// 5. 执行测试步骤 - 必须包含至少5个测试步骤
r($executionTest->initFieldsForCreateTest(1)) && p('project,type,name,code,team,acl,whitelist') && e('1,sprint,,,private,'); // 步骤1：正常项目ID，默认参数
r($executionTest->initFieldsForCreateTest(2, array('type' => 'sprint'))) && p('project,type') && e('2,sprint'); // 步骤2：指定type为sprint
r($executionTest->initFieldsForCreateTest(3, array('type' => 'stage'))) && p('project,type') && e('3,stage'); // 步骤3：指定type为stage
r($executionTest->initFieldsForCreateTest(0)) && p('project,type,name,code,team,acl,whitelist') && e('0,sprint,,,private,'); // 步骤4：项目ID为0的边界值
r($executionTest->initFieldsForCreateTest(-1)) && p('project,type,name,code,team,acl,whitelist') && e('-1,sprint,,,private,'); // 步骤5：负数项目ID
#!/usr/bin/env php
<?php

/**

title=测试 actionTao::addObjectNameForAction();
timeout=0
cid=14938

- 步骤1：正常情况属性objectName @测试Bug
- 步骤2：任务对象属性objectName @测试任务
- 步骤3：需求对象属性objectName @测试需求
- 步骤4：用户对象属性objectName @管理员
- 步骤5：不存在的对象ID属性objectName @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$actionTable = zenData('action');
$actionTable->id->range('1-10');
$actionTable->objectType->range('bug,task,story,program,branch,user,kanbancard,module,ppm,pivot,aiassistant');
$actionTable->objectID->range('1-5');
$actionTable->action->range('opened,syncexecution,mergedbranch,login,importedtask,deleted');
$actionTable->extra->range('test branch,module1,module2');
$actionTable->gen(10);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->gen(5);

$branchTable = zenData('branch');
$branchTable->id->range('1-3');
$branchTable->product->range('1');
$branchTable->name->range('主干,分支1,分支2');
$branchTable->gen(3);

$moduleTable = zenData('module');
$moduleTable->id->range('1-5');
$moduleTable->name->range('模块1,模块2,模块3,模块4,模块5');
$moduleTable->type->range('story');
$moduleTable->gen(5);

$taskTable = zenData('task');
$taskTable->id->range('1-3');
$taskTable->name->range('任务1,任务2,任务3');
$taskTable->gen(3);

// 移除AI助手表，避免数据库字段问题

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($actionTest->addObjectNameForActionTest('bug', 1, array('bug' => array(1 => '测试Bug')))) && p('objectName') && e('测试Bug'); // 步骤1：正常情况
r($actionTest->addObjectNameForActionTest('task', 2, array('task' => array(2 => '测试任务')))) && p('objectName') && e('测试任务'); // 步骤2：任务对象
r($actionTest->addObjectNameForActionTest('story', 3, array('story' => array(3 => '测试需求')))) && p('objectName') && e('测试需求'); // 步骤3：需求对象
r($actionTest->addObjectNameForActionTest('user', 1, array())) && p('objectName') && e('管理员'); // 步骤4：用户对象
r($actionTest->addObjectNameForActionTest('bug', 999, array())) && p('objectName') && e('~~'); // 步骤5：不存在的对象ID

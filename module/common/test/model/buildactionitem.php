#!/usr/bin/env php
<?php

/**

title=测试 commonModel::buildActionItem();
timeout=0
cid=0

- 步骤1：正常情况属性url @/index.php?m=task&f=view&t=php&id=1
- 步骤2：无权限情况 @0
- 步骤3：带属性参数
 - 属性class @btn
 - 属性title @查看
- 步骤4：带对象参数属性url @/index.php?m=common&f=ajaxGetDropMenu&t=php&module=task
- 步骤5：空模块参数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. zendata数据准备
$table = zenData('user');
$table->id->range('1-10');
$table->account->range('admin,user{9}');
$table->password->range('123456{10}');
$table->realname->range('管理员,用户{9}');
$table->role->range('admin{1},user{9}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 5. 必须包含至少5个测试步骤
r($commonTest->buildActionItemTest('task', 'view', 'id=1')) && p('url') && e('/index.php?m=task&f=view&t=php&id=1'); // 步骤1：正常情况
r($commonTest->buildActionItemTest('admin', 'user', 'id=1')) && p() && e('0'); // 步骤2：无权限情况
r($commonTest->buildActionItemTest('task', 'view', 'id=1', null, array('class' => 'btn', 'title' => '查看'))) && p('class,title') && e('btn,查看'); // 步骤3：带属性参数
r($commonTest->buildActionItemTest('common', 'ajaxGetDropMenu', 'module=task')) && p('url') && e('/index.php?m=common&f=ajaxGetDropMenu&t=php&module=task'); // 步骤4：带对象参数
r($commonTest->buildActionItemTest('', 'view', 'id=1')) && p() && e('0'); // 步骤5：空模块参数
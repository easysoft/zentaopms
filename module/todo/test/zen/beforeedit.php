#!/usr/bin/env php
<?php

/**

title=测试 todoZen::beforeEdit();
timeout=0
cid=0

- 步骤1：正常编辑自定义待办
 - 属性name @更新的待办
 - 属性type @custom
 - 属性account @admin
- 步骤2：编辑任务类型待办，验证名称自动获取
 - 属性name @任务123
 - 属性type @task
 - 属性objectID @123
- 步骤3：无效时间范围（结束时间小于开始时间） @0
- 步骤4：模块类型缺少objectID @0
- 步骤5：正常处理待办信息属性name @正常待办

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（使用YAML文件配置）
zendata('todo')->loadYaml('todo_beforeedit', false, 2)->gen(10);
zendata('user')->loadYaml('user', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($todoTest->beforeEditTest(1, (object)array('data' => (object)array('name' => '更新的待办', 'type' => 'custom', 'date' => '2023-12-02', 'begin' => '0900', 'end' => '1900', 'pri' => 1, 'assignedTo' => 'admin')))) && p('name,type,account') && e('更新的待办,custom,admin'); // 步骤1：正常编辑自定义待办

r($todoTest->beforeEditTest(2, (object)array('data' => (object)array('name' => '', 'type' => 'task', 'objectID' => 123, 'date' => '2023-12-03', 'begin' => '1000', 'end' => '1800', 'pri' => 2, 'assignedTo' => 'user1')))) && p('name,type,objectID') && e('任务123,task,123'); // 步骤2：编辑任务类型待办，验证名称自动获取

r($todoTest->beforeEditTest(3, (object)array('data' => (object)array('name' => '测试待办', 'type' => 'custom', 'date' => '2023-12-04', 'begin' => '1800', 'end' => '1000', 'pri' => 3, 'assignedTo' => 'admin')))) && p() && e('0'); // 步骤3：无效时间范围（结束时间小于开始时间）

r($todoTest->beforeEditTest(4, (object)array('data' => (object)array('name' => '', 'type' => 'bug', 'objectID' => 0, 'date' => '2023-12-05', 'begin' => '0800', 'end' => '1700', 'pri' => 1, 'assignedTo' => 'user2')))) && p() && e('0'); // 步骤4：模块类型缺少objectID

r($todoTest->beforeEditTest(5, (object)array('data' => (object)array('name' => '正常待办', 'type' => 'custom', 'date' => '2023-12-06', 'begin' => '0930', 'end' => '1730', 'pri' => 2, 'assignedTo' => 'user1')))) && p('name') && e('正常待办'); // 步骤5：正常处理待办信息
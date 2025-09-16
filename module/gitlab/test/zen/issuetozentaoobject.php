#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::issueToZentaoObject();
timeout=0
cid=0

- 步骤1：Task对象类型正常转换
 - 属性name @Test Task
 - 属性desc @Task description<br><br><a href="http://gitlab.test/issue/123" target="_blank">http://gitlab.test/issue/123</a>
- 步骤2：Story对象类型正常转换
 - 属性title @Test Story
 - 属性spec @Story description<br><br><a href="http://gitlab.test/issue/456" target="_blank">http://gitlab.test/issue/456</a>
- 步骤3：Bug对象类型正常转换
 - 属性title @Test Bug
 - 属性steps @Bug description<br><br><a href="http://gitlab.test/issue/789" target="_blank">http://gitlab.test/issue/789</a>
- 步骤4：无效对象类型处理 @0
- 步骤5：带changes参数的转换
 - 属性name @Updated Task
 - 属性lastEditedDate @2023-10-01 15:00:00

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$gitlabTest = new gitlabTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($gitlabTest->issueToZentaoObjectTest((object)array('objectType' => 'task', 'objectID' => 123, 'title' => 'Test Task', 'description' => 'Task description', 'created_at' => '2023-10-01 10:00:00', 'assignee_id' => 1, 'state' => 'opened', 'weight' => 2, 'web_url' => 'http://gitlab.test/issue/123'), 1, null)) && p('name,desc') && e('Test Task,Task description<br><br><a href="http://gitlab.test/issue/123" target="_blank">http://gitlab.test/issue/123</a>'); // 步骤1：Task对象类型正常转换
r($gitlabTest->issueToZentaoObjectTest((object)array('objectType' => 'story', 'objectID' => 456, 'title' => 'Test Story', 'description' => 'Story description', 'created_at' => '2023-10-01 11:00:00', 'assignee_id' => 2, 'state' => 'opened', 'weight' => 1, 'web_url' => 'http://gitlab.test/issue/456'), 1, null)) && p('title,spec') && e('Test Story,Story description<br><br><a href="http://gitlab.test/issue/456" target="_blank">http://gitlab.test/issue/456</a>'); // 步骤2：Story对象类型正常转换
r($gitlabTest->issueToZentaoObjectTest((object)array('objectType' => 'bug', 'objectID' => 789, 'title' => 'Test Bug', 'description' => 'Bug description', 'created_at' => '2023-10-01 12:00:00', 'assignee_id' => 3, 'state' => 'opened', 'weight' => 3, 'web_url' => 'http://gitlab.test/issue/789'), 1, null)) && p('title,steps') && e('Test Bug,Bug description<br><br><a href="http://gitlab.test/issue/789" target="_blank">http://gitlab.test/issue/789</a>'); // 步骤3：Bug对象类型正常转换
r($gitlabTest->issueToZentaoObjectTest((object)array('objectType' => 'invalid', 'objectID' => 999, 'title' => 'Invalid Object'), 1, null)) && p() && e('0'); // 步骤4：无效对象类型处理
r($gitlabTest->issueToZentaoObjectTest((object)array('objectType' => 'task', 'objectID' => 100, 'title' => 'Updated Task', 'description' => 'Updated description', 'updated_at' => '2023-10-01 15:00:00'), 1, (object)array('title' => true, 'updated_at' => true))) && p('name,lastEditedDate') && e('Updated Task,2023-10-01 15:00:00'); // 步骤5：带changes参数的转换
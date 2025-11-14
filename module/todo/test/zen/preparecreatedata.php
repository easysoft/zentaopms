#!/usr/bin/env php
<?php

/**

title=测试 todoZen::prepareCreateData();
timeout=0
cid=19304

- 执行todoTest模块的prepareCreateDataTest方法，参数是$customTodo
 - 属性type @custom
 - 属性name @自定义待办
 - 属性pri @2
- 执行todoTest模块的prepareCreateDataTest方法，参数是$taskTodo
 - 属性type @task
 - 属性objectID @1
- 执行todoTest模块的prepareCreateDataTest方法，参数是$storyTodo
 - 属性type @story
 - 属性objectID @1
- 执行todoTest模块的prepareCreateDataTest方法，参数是$invalidTimeTodo 属性error @end time should be greater than begin time
- 执行todoTest模块的prepareCreateDataTest方法，参数是$missingObjectTodo 属性error @objectID required for module type
- 执行todoTest模块的prepareCreateDataTest方法，参数是$cycleTodo
 - 属性type @cycle
 - 属性cycle @1
- 执行todoTest模块的prepareCreateDataTest方法，参数是$privateTodo
 - 属性private @1
 - 属性assignedTo @admin
 - 属性assignedBy @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

zenData('todo')->loadYaml('todo_preparecreatedata', false, 4)->gen(10);
zenData('task')->loadYaml('task_preparecreatedata', false, 4)->gen(5);
zenData('story')->loadYaml('story_preparecreatedata', false, 4)->gen(5);
zenData('bug')->loadYaml('bug_preparecreatedata', false, 4)->gen(5);

su('admin');

$todoTest = new todoTest();

// 测试步骤1：正常自定义待办数据准备
$customTodo = new stdClass();
$customTodo->type = 'custom';
$customTodo->name = '自定义待办';
$customTodo->begin = 800;
$customTodo->end = 1000;
$customTodo->pri = 2;
$customTodo->private = 0;
r($todoTest->prepareCreateDataTest($customTodo)) && p('type,name,pri') && e('custom,自定义待办,2');

// 测试步骤2：测试任务类型待办优先级自动设置
$taskTodo = new stdClass();
$taskTodo->type = 'task';
$taskTodo->objectID = 1;
$taskTodo->begin = 800;
$taskTodo->end = 1000;
$taskTodo->private = 0;
r($todoTest->prepareCreateDataTest($taskTodo)) && p('type,objectID') && e('task,1');

// 测试步骤3：测试非自定义待办名称自动设置
$storyTodo = new stdClass();
$storyTodo->type = 'story';
$storyTodo->objectID = 1;
$storyTodo->begin = 800;
$storyTodo->end = 1000;
$storyTodo->private = 0;
r($todoTest->prepareCreateDataTest($storyTodo)) && p('type,objectID') && e('story,1');

// 测试步骤4：测试结束时间小于开始时间的验证
$invalidTimeTodo = new stdClass();
$invalidTimeTodo->type = 'custom';
$invalidTimeTodo->name = '无效时间待办';
$invalidTimeTodo->begin = 1000;
$invalidTimeTodo->end = 800;
$invalidTimeTodo->private = 0;
r($todoTest->prepareCreateDataTest($invalidTimeTodo)) && p('error') && e('end time should be greater than begin time');

// 测试步骤5：测试模块类型待办缺少objectID的验证
$missingObjectTodo = new stdClass();
$missingObjectTodo->type = 'bug';
$missingObjectTodo->objectID = 0;
$missingObjectTodo->begin = 800;
$missingObjectTodo->end = 1000;
$missingObjectTodo->private = 0;
r($todoTest->prepareCreateDataTest($missingObjectTodo)) && p('error') && e('objectID required for module type');

// 测试步骤6：测试周期待办配置处理
$cycleTodo = new stdClass();
$cycleTodo->type = 'custom';
$cycleTodo->name = '周期待办';
$cycleTodo->begin = 800;
$cycleTodo->end = 1000;
$cycleTodo->cycle = 1;
$cycleTodo->config = array('type' => 'day', 'day' => 1);
$cycleTodo->private = 0;
r($todoTest->prepareCreateDataTest($cycleTodo)) && p('type,cycle') && e('cycle,1');

// 测试步骤7：测试私有待办指派处理
$privateTodo = new stdClass();
$privateTodo->type = 'custom';
$privateTodo->name = '私有待办';
$privateTodo->begin = 800;
$privateTodo->end = 1000;
$privateTodo->private = 1;
$privateTodo->assignedTo = 'user1';
$privateTodo->assignedBy = 'user2';
r($todoTest->prepareCreateDataTest($privateTodo)) && p('private,assignedTo,assignedBy') && e('1,admin,admin');
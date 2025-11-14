#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getInitStoryByTodo();
timeout=0
cid=18691

- 步骤1：正常有效待办ID，验证所有字段映射
 - 属性source @todo
 - 属性title @测试待办1
 - 属性spec @这是一个测试待办的描述
 - 属性pri @1
- 步骤2：空待办ID，验证不修改原始对象
 - 属性source @~~
 - 属性title @~~
 - 属性spec @~~
 - 属性pri @3
- 步骤3：不存在的待办ID，验证不修改原始对象
 - 属性source @~~
 - 属性title @~~
 - 属性spec @~~
 - 属性pri @3
- 步骤4：验证不同优先级的正确映射
 - 属性source @todo
 - 属性pri @2
- 步骤5：验证完整信息映射的准确性
 - 属性source @todo
 - 属性title @重要任务3
 - 属性spec @重要任务的执行细节
 - 属性pri @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('todo');
$table->loadYaml('todo_getinitstorybytodo', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyZenTest();

// 5. 准备测试数据
$initStory = new stdclass();
$initStory->source = '';
$initStory->title = '';
$initStory->spec = '';
$initStory->pri = 3;

// 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->getInitStoryByTodoTest(1, clone $initStory)) && p('source,title,spec,pri') && e('todo,测试待办1,这是一个测试待办的描述,1'); // 步骤1：正常有效待办ID，验证所有字段映射
r($storyTest->getInitStoryByTodoTest(0, clone $initStory)) && p('source,title,spec,pri') && e('~~,~~,~~,3'); // 步骤2：空待办ID，验证不修改原始对象
r($storyTest->getInitStoryByTodoTest(999, clone $initStory)) && p('source,title,spec,pri') && e('~~,~~,~~,3'); // 步骤3：不存在的待办ID，验证不修改原始对象
r($storyTest->getInitStoryByTodoTest(2, clone $initStory)) && p('source,pri') && e('todo,2'); // 步骤4：验证不同优先级的正确映射
r($storyTest->getInitStoryByTodoTest(3, clone $initStory)) && p('source,title,spec,pri') && e('todo,重要任务3,重要任务的执行细节,3'); // 步骤5：验证完整信息映射的准确性
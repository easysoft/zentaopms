#!/usr/bin/env php
<?php

/**

title=测试 executionTao::buildStoryTree();
timeout=0
cid=16384

- 执行$result1 @6
- 执行$result2[0]->children ?? [] @3
- 执行$result3[0]->tasksCount ?? 0 @3
- 执行executionTest模块的buildStoryTreeTest方法，参数是array  @0
- 执行$result5[0]
 - 属性type @story
 - 属性title @需求标题1
 - 属性color @red
 - 属性pri @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 准备测试数据
$storyTable = zenData('story');
$storyTable->loadYaml('story_buildstorytree', false, 2)->gen(10);

$taskTable = zenData('task');
$taskTable->loadYaml('task_buildstorytree', false, 2)->gen(15);

$userTable = zenData('user');
$userTable->loadYaml('user_buildstorytree', false, 2)->gen(5);

su('admin');

$executionTest = new executionTaoTest();

// 准备测试需要的数据结构
$stories = array();
$storyIds = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
foreach($storyIds as $storyId)
{
    $story = new stdClass();
    $story->id = $storyId;
    $story->parent = in_array($storyId, [2, 3]) ? 1 : (in_array($storyId, [5, 6]) ? 4 : 0);
    $story->title = "需求标题{$storyId}";
    $story->type = $storyId <= 8 ? 'story' : 'requirement';
    $story->color = $storyId % 4 == 1 ? 'red' : ($storyId % 4 == 2 ? 'blue' : '');
    $story->pri = ($storyId % 4) + 1;
    $story->grade = $storyId <= 6 ? 1 : 2;
    $story->openedBy = 'admin';
    $story->assignedTo = $storyId % 3 == 1 ? 'admin' : ($storyId % 3 == 2 ? 'user' : 'dev');
    $story->status = 'active'; // 添加status属性
    $story->version = 1; // 添加version属性
    $story->taskVersion = 1; // 添加taskVersion属性
    $stories[$storyId] = $story;
}

$taskGroups = array(
    1821 => array(
        1 => array(
            1 => (object)array('id' => 1, 'name' => '需求1任务1', 'color' => '', 'pri' => 2, 'status' => 'wait', 'parent' => 0, 'isParent' => '0', 'assignedTo' => 'admin', 'openedBy' => 'admin', 'story' => 1, 'estStarted' => '2023-10-01', 'realStarted' => '2023-10-02', 'estimate' => 8, 'consumed' => 2, 'left' => 6),
            2 => (object)array('id' => 2, 'name' => '需求1任务2', 'color' => '', 'pri' => 3, 'status' => 'doing', 'parent' => 0, 'isParent' => '0', 'assignedTo' => 'user', 'openedBy' => 'admin', 'story' => 1, 'estStarted' => '2023-10-05', 'realStarted' => '2023-10-06', 'estimate' => 5, 'consumed' => 3, 'left' => 2),
            3 => (object)array('id' => 3, 'name' => '需求1任务3', 'color' => '', 'pri' => 1, 'status' => 'done', 'parent' => 0, 'isParent' => '0', 'assignedTo' => 'dev', 'openedBy' => 'user', 'story' => 1, 'estStarted' => '2023-10-10', 'realStarted' => '2023-10-11', 'estimate' => 3, 'consumed' => 3, 'left' => 0)
        ),
        2 => array(
            4 => (object)array('id' => 4, 'name' => '需求2任务1', 'color' => 'blue', 'pri' => 2, 'status' => 'wait', 'parent' => 0, 'isParent' => '0', 'assignedTo' => 'user', 'openedBy' => 'admin', 'story' => 2, 'estStarted' => '', 'realStarted' => '', 'estimate' => 6, 'consumed' => 0, 'left' => 6),
            5 => (object)array('id' => 5, 'name' => '需求2任务2', 'color' => '', 'pri' => 3, 'status' => 'doing', 'parent' => 0, 'isParent' => '0', 'assignedTo' => 'dev', 'openedBy' => 'user', 'story' => 2, 'estStarted' => '', 'realStarted' => '', 'estimate' => 4, 'consumed' => 1, 'left' => 3)
        ),
        4 => array(
            6 => (object)array('id' => 6, 'name' => '需求4任务1', 'color' => 'red', 'pri' => 1, 'status' => 'wait', 'parent' => 0, 'isParent' => '0', 'assignedTo' => 'admin', 'openedBy' => 'dev', 'story' => 4, 'estStarted' => '', 'realStarted' => '', 'estimate' => 10, 'consumed' => 0, 'left' => 10),
            7 => (object)array('id' => 7, 'name' => '需求4任务2', 'color' => '', 'pri' => 4, 'status' => 'done', 'parent' => 0, 'isParent' => '0', 'assignedTo' => 'user', 'openedBy' => 'admin', 'story' => 4, 'estStarted' => '', 'realStarted' => '', 'estimate' => 7, 'consumed' => 7, 'left' => 0),
            8 => (object)array('id' => 8, 'name' => '需求4任务3', 'color' => '', 'pri' => 2, 'status' => 'closed', 'parent' => 0, 'isParent' => '0', 'assignedTo' => 'dev', 'openedBy' => 'user', 'story' => 4, 'estStarted' => '', 'realStarted' => '', 'estimate' => 5, 'consumed' => 5, 'left' => 0)
        ),
        0 => array(
            9 => (object)array('id' => 9, 'name' => '无需求任务1', 'color' => '', 'pri' => 3, 'status' => 'wait', 'parent' => 0, 'isParent' => '0', 'assignedTo' => 'admin', 'openedBy' => 'admin', 'story' => 0, 'estStarted' => '', 'realStarted' => '', 'estimate' => 2, 'consumed' => 0, 'left' => 2),
            10 => (object)array('id' => 10, 'name' => '无需求任务2', 'color' => 'green', 'pri' => 1, 'status' => 'doing', 'parent' => 0, 'isParent' => '0', 'assignedTo' => 'user', 'openedBy' => 'dev', 'story' => 0, 'estStarted' => '', 'realStarted' => '', 'estimate' => 3, 'consumed' => 1, 'left' => 2)
        )
    )
);

$node = new stdClass();
$node->id = 1821;
$node->root = 1;

// 测试步骤1：使用基本数据构建需求树，检查返回结果数量（只有根节点需求）
$result1 = $executionTest->buildStoryTreeTest($stories, $taskGroups, 101, $node);
r(count($result1)) && p() && e('6');

// 测试步骤2：测试父子需求关系，检查父需求下是否包含子需求
$result2 = $executionTest->buildStoryTreeTest($stories, $taskGroups, 101, $node);
r(count($result2[0]->children ?? [])) && p() && e('3');

// 测试步骤3：测试需求关联任务的构建，检查需求1是否关联了任务
$result3 = $executionTest->buildStoryTreeTest($stories, $taskGroups, 101, $node);
r($result3[0]->tasksCount ?? 0) && p() && e('3');

// 测试步骤4：测试空需求数组的处理
r(count($executionTest->buildStoryTreeTest(array(), $taskGroups, 101, $node))) && p() && e('0');

// 测试步骤5：测试需求节点属性完整性，验证需求节点包含必要属性
$result5 = $executionTest->buildStoryTreeTest($stories, $taskGroups, 101, $node);
r($result5[0]) && p('type,title,color,pri') && e('story,需求标题1,red,2');
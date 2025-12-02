#!/usr/bin/env php
<?php

/**

title=测试 storyModel::syncGrade();
timeout=0
cid=18590

- 步骤1：父需求层级变更同步（子需求grade从2变为3）第3条的grade属性 @3
- 步骤2：非父需求不执行同步 @not_parent
- 步骤3：父需求2同步子需求5（子需求grade从3变为4）第5条的grade属性 @4
- 步骤4：多子需求同步（子需求grade从3变为4）
 - 第3条的grade属性 @4
 - 第4条的grade属性 @4
- 步骤5：层级降级同步（子需求grade从4变为2）第5条的grade属性 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. 清理并准备测试数据
global $tester;
$tester->dao->delete()->from(TABLE_STORY)->exec();
$tester->dao->delete()->from(TABLE_ACTION)->exec();

// 直接插入测试数据
$tester->dao->insert(TABLE_STORY)->data(array(
    'id' => 1,
    'vision' => 'rnd',
    'parent' => 0,
    'product' => 1,
    'title' => '父需求1',
    'type' => 'story',
    'category' => 'feature',
    'pri' => 3,
    'estimate' => 0,
    'status' => 'active',
    'stage' => 'wait',
    'openedBy' => 'admin',
    'openedDate' => '2023-01-01 10:00:00',
    'assignedTo' => 'admin',
    'assignedDate' => '2023-01-01 10:00:00',
    'lastEditedBy' => 'admin',
    'lastEditedDate' => '2023-01-01 10:00:00',
    'version' => 1,
    'deleted' => 0,
    'isParent' => '1',
    'root' => 1,
    'path' => ',1,',
    'grade' => 1
))->exec();

$tester->dao->insert(TABLE_STORY)->data(array(
    'id' => 2,
    'vision' => 'rnd',
    'parent' => 0,
    'product' => 1,
    'title' => '父需求2',
    'type' => 'story',
    'category' => 'feature',
    'pri' => 3,
    'estimate' => 0,
    'status' => 'active',
    'stage' => 'wait',
    'openedBy' => 'admin',
    'openedDate' => '2023-01-01 10:00:00',
    'assignedTo' => 'admin',
    'assignedDate' => '2023-01-01 10:00:00',
    'lastEditedBy' => 'admin',
    'lastEditedDate' => '2023-01-01 10:00:00',
    'version' => 1,
    'deleted' => 0,
    'isParent' => '1',
    'root' => 2,
    'path' => ',2,',
    'grade' => 2
))->exec();

$tester->dao->insert(TABLE_STORY)->data(array(
    'id' => 3,
    'vision' => 'rnd',
    'parent' => 1,
    'product' => 1,
    'title' => '子需求1',
    'type' => 'story',
    'category' => 'feature',
    'pri' => 3,
    'estimate' => 0,
    'status' => 'active',
    'stage' => 'wait',
    'openedBy' => 'admin',
    'openedDate' => '2023-01-01 10:00:00',
    'assignedTo' => 'admin',
    'assignedDate' => '2023-01-01 10:00:00',
    'lastEditedBy' => 'admin',
    'lastEditedDate' => '2023-01-01 10:00:00',
    'version' => 1,
    'deleted' => 0,
    'isParent' => '0',
    'root' => 1,
    'path' => ',1,3,',
    'grade' => 2
))->exec();

$tester->dao->insert(TABLE_STORY)->data(array(
    'id' => 4,
    'vision' => 'rnd',
    'parent' => 1,
    'product' => 1,
    'title' => '子需求2',
    'type' => 'story',
    'category' => 'feature',
    'pri' => 3,
    'estimate' => 0,
    'status' => 'active',
    'stage' => 'wait',
    'openedBy' => 'admin',
    'openedDate' => '2023-01-01 10:00:00',
    'assignedTo' => 'admin',
    'assignedDate' => '2023-01-01 10:00:00',
    'lastEditedBy' => 'admin',
    'lastEditedDate' => '2023-01-01 10:00:00',
    'version' => 1,
    'deleted' => 0,
    'isParent' => '0',
    'root' => 1,
    'path' => ',1,4,',
    'grade' => 2
))->exec();

$tester->dao->insert(TABLE_STORY)->data(array(
    'id' => 5,
    'vision' => 'rnd',
    'parent' => 2,
    'product' => 1,
    'title' => '子需求3',
    'type' => 'story',
    'category' => 'feature',
    'pri' => 3,
    'estimate' => 0,
    'status' => 'active',
    'stage' => 'wait',
    'openedBy' => 'admin',
    'openedDate' => '2023-01-01 10:00:00',
    'assignedTo' => 'admin',
    'assignedDate' => '2023-01-01 10:00:00',
    'lastEditedBy' => 'admin',
    'lastEditedDate' => '2023-01-01 10:00:00',
    'version' => 1,
    'deleted' => 0,
    'isParent' => '0',
    'root' => 2,
    'path' => ',2,5,',
    'grade' => 3
))->exec();

$tester->dao->insert(TABLE_STORY)->data(array(
    'id' => 6,
    'vision' => 'rnd',
    'parent' => 0,
    'product' => 1,
    'title' => '普通需求1',
    'type' => 'story',
    'category' => 'feature',
    'pri' => 3,
    'estimate' => 0,
    'status' => 'active',
    'stage' => 'wait',
    'openedBy' => 'admin',
    'openedDate' => '2023-01-01 10:00:00',
    'assignedTo' => 'admin',
    'assignedDate' => '2023-01-01 10:00:00',
    'lastEditedBy' => 'admin',
    'lastEditedDate' => '2023-01-01 10:00:00',
    'version' => 1,
    'deleted' => 0,
    'isParent' => '0',
    'root' => 6,
    'path' => ',6,',
    'grade' => 1
))->exec();

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：测试父需求层级变更时同步子需求层级 (父需求1有子需求3,4)
$oldStory = new stdclass();
$oldStory->id = 1;
$oldStory->isParent = '1';
$oldStory->grade = 1;
$oldStory->type = 'story';

$story = new stdclass();
$story->id = 1;
$story->isParent = '1';
$story->grade = 2;
$story->type = 'story';

r($storyTest->syncGradeTest($oldStory, $story)) && p('3:grade') && e('3'); // 步骤1：父需求层级变更同步（子需求grade从2变为3）

// 步骤2：测试非父需求调用syncGrade方法
$oldStory = new stdclass();
$oldStory->id = 6;
$oldStory->isParent = '0';
$oldStory->grade = 1;
$oldStory->type = 'story';

$story = new stdclass();
$story->id = 6;
$story->isParent = '0';
$story->grade = 2;
$story->type = 'story';

r($storyTest->syncGradeTest($oldStory, $story)) && p() && e('not_parent'); // 步骤2：非父需求不执行同步

// 步骤3：测试父需求有子需求时的同步 (父需求2有子需求5)
$oldStory = new stdclass();
$oldStory->id = 2;
$oldStory->isParent = '1';
$oldStory->grade = 2;
$oldStory->type = 'story';

$story = new stdclass();
$story->id = 2;
$story->isParent = '1';
$story->grade = 3;
$story->type = 'story';

r($storyTest->syncGradeTest($oldStory, $story)) && p('5:grade') && e('4'); // 步骤3：父需求2同步子需求5（子需求grade从3变为4）

// 步骤4：测试多个子需求的同步情况 (父需求1有子需求3,4)
$oldStory = new stdclass();
$oldStory->id = 1;
$oldStory->isParent = '1';
$oldStory->grade = 1;
$oldStory->type = 'story';

$story = new stdclass();
$story->id = 1;
$story->isParent = '1';
$story->grade = 2;
$story->type = 'story';

r($storyTest->syncGradeTest($oldStory, $story)) && p('3:grade;4:grade') && e('4;4'); // 步骤4：多子需求同步（子需求grade从3变为4）

// 步骤5：测试层级变更为更低等级的情况
$oldStory = new stdclass();
$oldStory->id = 2;
$oldStory->isParent = '1';
$oldStory->grade = 3;
$oldStory->type = 'story';

$story = new stdclass();
$story->id = 2;
$story->isParent = '1';
$story->grade = 1;
$story->type = 'story';

r($storyTest->syncGradeTest($oldStory, $story)) && p('5:grade') && e('2'); // 步骤5：层级降级同步（子需求grade从4变为2）
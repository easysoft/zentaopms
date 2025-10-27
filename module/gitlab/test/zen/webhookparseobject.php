#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::webhookParseObject();
timeout=0
cid=0

- 步骤1：解析有效story标签
 - 属性type @story
 - 属性id @123
- 步骤2：解析有效task标签
 - 属性type @task
 - 属性id @456
- 步骤3：解析有效bug标签
 - 属性type @bug
 - 属性id @789
- 步骤4：解析空标签数组 @0
- 步骤5：解析无效标签格式 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$gitlabTest = new gitlabTest();

// 4. 准备测试数据
// 有效的story标签
$storyLabels = array();
$storyLabel = new stdclass();
$storyLabel->title = 'zentao_story/123';
$storyLabels[] = $storyLabel;

// 有效的task标签
$taskLabels = array();
$taskLabel = new stdclass();
$taskLabel->title = 'zentao_task/456';
$taskLabels[] = $taskLabel;

// 有效的bug标签
$bugLabels = array();
$bugLabel = new stdclass();
$bugLabel->title = 'zentao_bug/789';
$bugLabels[] = $bugLabel;

// 空标签数组
$emptyLabels = array();

// 无效标签格式
$invalidLabels = array();
$invalidLabel1 = new stdclass();
$invalidLabel1->title = 'invalid-format';
$invalidLabel2 = new stdclass();
$invalidLabel2->title = 'zentao_invalid/123';
$invalidLabel3 = new stdclass();
$invalidLabel3->title = 'zentao_story/abc';
$invalidLabels[] = $invalidLabel1;
$invalidLabels[] = $invalidLabel2;
$invalidLabels[] = $invalidLabel3;

// 5. 强制要求：必须包含至少5个测试步骤
r($gitlabTest->webhookParseObjectTest($storyLabels)) && p('type,id') && e('story,123'); // 步骤1：解析有效story标签
r($gitlabTest->webhookParseObjectTest($taskLabels)) && p('type,id') && e('task,456');   // 步骤2：解析有效task标签
r($gitlabTest->webhookParseObjectTest($bugLabels)) && p('type,id') && e('bug,789');     // 步骤3：解析有效bug标签
r($gitlabTest->webhookParseObjectTest($emptyLabels)) && p() && e('0');                   // 步骤4：解析空标签数组
r($gitlabTest->webhookParseObjectTest($invalidLabels)) && p() && e('0');                 // 步骤5：解析无效标签格式
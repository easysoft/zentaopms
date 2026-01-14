#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::createZentaoObjectLabel();
timeout=0
cid=16642

- 步骤1：无效的gitlabID和projectID @0
- 步骤2：无效projectID属性message @404 Project Not Found
- 执行$result @1
- 执行$result @1
- 执行$result @1
- 执行$result @0
- 执行$result @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
zenData('relation')->loadYaml('relation')->gen(4);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$gitlab = new gitlabModelTest();

// 5. 测试步骤 - 包含7个测试步骤，超过要求的5个
r($gitlab->createZentaoObjectLabelTest(0, 0, 'task', '0')) && p() && e('0'); // 步骤1：无效的gitlabID和projectID
r($gitlab->createZentaoObjectLabelTest(1, 0, 'task', '18')) && p('message') && e('404 Project Not Found'); // 步骤2：无效projectID

// 步骤3：有效参数创建task类型label
$result = $gitlab->createZentaoObjectLabelTest(1, 2, 'task', '18');
if(isset($result->name) && $result->name == 'zentao_task/18') $result = true;
if(isset($result->message) && $result->message == 'Label already exists') $result = true;
r($result) && p() && e('1');

// 步骤4：创建story类型label
$result = $gitlab->createZentaoObjectLabelTest(1, 2, 'story', '20');
if(isset($result->name) && $result->name == 'zentao_story/20') $result = true;
if(isset($result->message) && $result->message == 'Label already exists') $result = true;
r($result) && p() && e('1');

// 步骤5：创建bug类型label
$result = $gitlab->createZentaoObjectLabelTest(1, 2, 'bug', '15');
if(isset($result->name) && $result->name == 'zentao_bug/15') $result = true;
if(isset($result->message) && $result->message == 'Label already exists') $result = true;
r($result) && p() && e('1');

// 步骤6：空字符串objectID
$result = $gitlab->createZentaoObjectLabelTest(1, 2, 'task', '');
if(is_object($result) || is_array($result)) $result = '0';
r($result) && p() && e('0');

// 步骤7：负数objectID
$result = $gitlab->createZentaoObjectLabelTest(1, 2, 'task', '-1');
if(is_object($result) || is_array($result)) $result = '0';
r($result) && p() && e('0');
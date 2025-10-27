#!/usr/bin/env php
<?php

/**

title=测试 jobZen::reponseAfterCreateEdit();
timeout=0
cid=0

- 步骤1：无错误时返回成功响应属性result @success
- 步骤2：gitlab引擎server错误转换为repo错误属性result @fail
- 步骤3：jenkins引擎server错误转换为jkServer属性result @fail
- 步骤4：jenkins引擎pipeline错误转换为jkTask属性result @fail
- 步骤5：传入repoID时成功响应包含正确的load参数
 - 属性result @success
 - 属性load @browse?repoID=5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$jobTest = new jobTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($jobTest->reponseAfterCreateEditTest(0, '', array())) && p('result') && e('success'); // 步骤1：无错误时返回成功响应
r($jobTest->reponseAfterCreateEditTest(0, 'gitlab', array('server' => 'Server error'))) && p('result') && e('fail'); // 步骤2：gitlab引擎server错误转换为repo错误
r($jobTest->reponseAfterCreateEditTest(0, 'jenkins', array('server' => 'Jenkins server error'))) && p('result') && e('fail'); // 步骤3：jenkins引擎server错误转换为jkServer
r($jobTest->reponseAfterCreateEditTest(0, 'jenkins', array('pipeline' => 'Pipeline error'))) && p('result') && e('fail'); // 步骤4：jenkins引擎pipeline错误转换为jkTask
r($jobTest->reponseAfterCreateEditTest(5, '', array())) && p('result,load') && e('success,browse?repoID=5'); // 步骤5：传入repoID时成功响应包含正确的load参数
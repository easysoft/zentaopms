#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::queryDownloadImageStatus();
timeout=0
cid=19755

- 步骤1：正常创建状态镜像属性id @1
- 步骤2：进行中状态镜像属性id @2
- 步骤3：已完成状态镜像属性status @completed
- 步骤4：未下载状态镜像属性taskID @0
- 步骤5：无效镜像对象属性id @999

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（这里不生成实际数据，在测试方法中模拟）

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$zahostTest = new zahostModelTest();

// 5. 测试步骤：必须包含至少5个测试步骤
r($zahostTest->queryDownloadImageStatusTest((object)array('id' => 1, 'host' => 1, 'name' => 'ubuntu18.04', 'status' => 'creating'))) && p('id') && e('1'); // 步骤1：正常创建状态镜像
r($zahostTest->queryDownloadImageStatusTest((object)array('id' => 2, 'host' => 1, 'name' => 'centos7', 'status' => 'inprogress'))) && p('id') && e('2'); // 步骤2：进行中状态镜像
r($zahostTest->queryDownloadImageStatusTest((object)array('id' => 3, 'host' => 2, 'name' => 'debian10', 'status' => 'completed'))) && p('status') && e('completed'); // 步骤3：已完成状态镜像
r($zahostTest->queryDownloadImageStatusTest((object)array('id' => 4, 'host' => 2, 'name' => 'mysql8.0', 'status' => 'notDownloaded'))) && p('taskID') && e('0'); // 步骤4：未下载状态镜像
r($zahostTest->queryDownloadImageStatusTest((object)array('id' => 999, 'host' => 999, 'name' => 'invalid', 'status' => 'unknown'))) && p('id') && e('999'); // 步骤5：无效镜像对象
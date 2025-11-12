#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getExportFields();
timeout=0
cid=0

- 步骤1:使用默认字段列表
 - 属性id @编号
 - 属性name @任务名称
 - 属性type @任务类型
- 步骤2:POST数据提供的字段
 - 属性id @编号
 - 属性name @任务名称
 - 属性status @任务状态
- 步骤3:逗号分隔字符串格式
 - 属性id @编号
 - 属性name @任务名称
 - 属性status @任务状态
- 步骤4:字段包含空格
 - 属性id @编号
 - 属性name @任务名称
 - 属性type @任务类型
- 步骤5:混合场景
 - 属性pri @优先级
 - 属性estimate @最初预计
- 步骤6:空字段列表返回数组长度 @1
- 步骤7:单个字段属性status @任务状态

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$taskTest = new taskZenTest();

// 4. 执行测试步骤
r($taskTest->getExportFieldsTest('id,name,type,pri,estimate')) && p('id,name,type') && e('编号,任务名称,任务类型'); // 步骤1:使用默认字段列表
r($taskTest->getExportFieldsTest('id,name,type,pri,estimate', array('exportFields' => array('id', 'name', 'status')))) && p('id,name,status') && e('编号,任务名称,任务状态'); // 步骤2:POST数据提供的字段
r($taskTest->getExportFieldsTest('id,name,type', array('exportFields' => array('id,name,status')))) && p('id,name,status') && e('编号,任务名称,任务状态'); // 步骤3:逗号分隔字符串格式
r($taskTest->getExportFieldsTest('id, name , type', array())) && p('id,name,type') && e('编号,任务名称,任务类型'); // 步骤4:字段包含空格
r($taskTest->getExportFieldsTest('id,name', array('exportFields' => array(' pri , estimate ')))) && p('pri,estimate') && e('优先级,最初预计'); // 步骤5:混合场景
r(count($taskTest->getExportFieldsTest('', array()))) && p() && e('1'); // 步骤6:空字段列表返回数组长度
r($taskTest->getExportFieldsTest('status', array())) && p('status') && e('任务状态'); // 步骤7:单个字段
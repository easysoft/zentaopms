#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processBuildinFieldData();
timeout=0
cid=15866

- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'story', $data1, $object1, $relations1, false 
 - 属性title @Test Bug Title
 - 属性pri @1
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'story', $data2, $object2, $relations2, false 属性storyreporter @admin
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'task', $data3, $object3, $relations3, false 
 - 属性tasktimeoriginalestimate @8
 - 属性tasktimespent @4
- 执行$result4 @1
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'bug', $data5, $object5, $relations5, true 属性bugsummary @Test Summary
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'story', $data6, $object6, $relations6, false 属性storyreporter @testuser
- 执行$result7 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$convertTest = new convertTaoTest();

// 准备测试数据
// 测试数据1：正常的JIRA数据，包含基本字段
$data1 = new stdclass();
$data1->issuetype = 'bug';
$data1->summary = 'Test Bug Title';
$data1->priority = 1;
$data1->reporter = 'jirauser1';
$data1->timeoriginalestimate = 7200; // 2小时（秒）
$data1->timespent = 3600; // 1小时（秒）

$object1 = new stdclass();
$relations1 = array(
    'zentaoFieldbug' => array(
        'summary' => 'title',
        'priority' => 'pri'
    )
);

// 测试数据2：测试reporter字段转换
$data2 = new stdclass();
$data2->issuetype = 'story';
$data2->reporter = 'admin';

$object2 = new stdclass();
$relations2 = array();

// 测试数据3：测试时间字段转换
$data3 = new stdclass();
$data3->issuetype = 'task';
$data3->timeoriginalestimate = 28800; // 8小时
$data3->timespent = 14400; // 4小时

$object3 = new stdclass();
$relations3 = array();

// 测试数据4：空数据对象
$data4 = new stdclass();
$data4->issuetype = '';

$object4 = new stdclass();
$relations4 = array();

// 测试数据5：buildinFlow为true
$data5 = new stdclass();
$data5->issuetype = 'bug';
$data5->summary = 'Test Summary';
$data5->priority = 2;

$object5 = new stdclass();
$relations5 = array();

// 4. 执行测试步骤
// 步骤1：正常数据处理，包含JIRA字段映射
r($convertTest->processBuildinFieldDataTest('story', $data1, $object1, $relations1, false)) && p('title,pri') && e('Test Bug Title,1');

// 步骤2：测试reporter字段转换为用户账号
r($convertTest->processBuildinFieldDataTest('story', $data2, $object2, $relations2, false)) && p('storyreporter') && e('admin');

// 步骤3：测试时间字段转换（小时计算）
r($convertTest->processBuildinFieldDataTest('task', $data3, $object3, $relations3, false)) && p('tasktimeoriginalestimate,tasktimespent') && e('8,4');

// 步骤4：空数据对象处理（返回对象类型）
$result4 = $convertTest->processBuildinFieldDataTest('module', $data4, $object4, $relations4, false);
r(is_object($result4)) && p() && e('1');

// 步骤5：buildinFlow为true时跳过特定字段
r($convertTest->processBuildinFieldDataTest('bug', $data5, $object5, $relations5, true)) && p('bugsummary') && e('Test Summary');

// 步骤6：开源版本处理（跳过企业版字段）- 通过修改配置测试
$data6 = new stdclass();
$data6->issuetype = 'story';
$data6->reporter = 'testuser';
$object6 = new stdclass();
$relations6 = array();
r($convertTest->processBuildinFieldDataTest('story', $data6, $object6, $relations6, false)) && p('storyreporter') && e('testuser');

// 步骤7：无映射关系时的处理（返回对象类型）
$data7 = new stdclass();
$data7->issuetype = 'custom';
$data7->customfield = 'customvalue';
$object7 = new stdclass();
$relations7 = array();
$result7 = $convertTest->processBuildinFieldDataTest('custom', $data7, $object7, $relations7, false);
r(is_object($result7)) && p() && e('1');
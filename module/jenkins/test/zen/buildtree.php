#!/usr/bin/env php
<?php

/**

title=测试 jenkinsZen::buildTree();
timeout=0
cid=16834

- 步骤1: 测试空数组 @0
- 步骤2: 测试单个任务
 - 第0条的id属性 @job1
 - 第0条的text属性 @test-job
- 步骤3: 测试多个任务
 - 第0条的id属性 @job1
 - 第1条的id属性 @job2
 - 第2条的id属性 @job3
- 步骤4: 测试文件夹结构
 - 第0条的text属性 @folder1
 - 第0条的type属性 @folder
- 步骤5: 测试嵌套结构-顶层类型第0条的type属性 @folder
- 步骤6: 测试嵌套结构-顶层文本第0条的text属性 @folder1
- 步骤7: 测试URL编码
 - 第0条的text属性 @test job
 - 第1条的text属性 @hello+world
- 步骤8: 测试中文名称
 - 第0条的id属性 @测试任务
 - 第1条的id属性 @开发任务
- 步骤9: 测试空文件夹被忽略 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$jenkinsTest = new jenkinsZenTest();

r($jenkinsTest->buildTreeTest(array())) && p() && e('0'); // 步骤1: 测试空数组
r($jenkinsTest->buildTreeTest(array('job1' => 'test-job'))) && p('0:id;0:text') && e('job1,test-job'); // 步骤2: 测试单个任务
r($jenkinsTest->buildTreeTest(array('job1' => 'task1', 'job2' => 'task2', 'job3' => 'task3'))) && p('0:id;1:id;2:id') && e('job1,job2,job3'); // 步骤3: 测试多个任务
r($jenkinsTest->buildTreeTest(array('folder1' => array('sub1' => 'task1', 'sub2' => 'task2')))) && p('0:text;0:type') && e('folder1,folder'); // 步骤4: 测试文件夹结构
r($jenkinsTest->buildTreeTest(array('folder1' => array('folder2' => array('sub1' => 'task1'))))) && p('0:type') && e('folder'); // 步骤5: 测试嵌套结构-顶层类型
r($jenkinsTest->buildTreeTest(array('folder1' => array('folder2' => array('sub1' => 'task1'))))) && p('0:text') && e('folder1'); // 步骤6: 测试嵌套结构-顶层文本
r($jenkinsTest->buildTreeTest(array('job1' => 'test%20job', 'job2' => 'hello%2Bworld'))) && p('0:text;1:text') && e('test job,hello+world'); // 步骤7: 测试URL编码
r($jenkinsTest->buildTreeTest(array('测试任务' => 'job1', '开发任务' => 'job2'))) && p('0:id;1:id') && e('测试任务,开发任务'); // 步骤8: 测试中文名称
r($jenkinsTest->buildTreeTest(array('empty_folder' => array()))) && p('0') && e('0'); // 步骤9: 测试空文件夹被忽略
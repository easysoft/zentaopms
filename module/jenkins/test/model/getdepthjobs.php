#!/usr/bin/env php
<?php

/**

title=测试 jenkinsModel::getDepthJobs();
timeout=0
cid=16831

- 测试正常情况：深度为1时解析Jenkins作业结构 @{"\/job\/simpleJob\/":"Simple Job","\/job\/paramJob\/":"Param Job","folder1":{"\/job\/folder1\/job\/subJob1\/":"Sub Job 1","\/job\/folder1\/job\/subJob2\/":"Sub Job 2"}}

- 测试空数据：传入空的作业数组 @[]
- 测试递归深度：超过最大深度限制（depth > 4） @[]
- 测试异常输入：传入无效的作业数据结构 @[]
- 测试作业类型识别：区分文件夹和可执行作业 @{"\/job\/buildableJob\/":"Buildable Job","\/job\/regularJob\/":"Regular Job","multibranchPipeline":[]}

- 测试URL解析：处理包含特殊字符的作业URL @{"\/job\/hello%20world\/":"Hello World","\/job\/test-job\/":"Test Job"}

- 测试文件夹处理：识别文件夹类型并处理为空数组 @{"folder":[]}

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen('1');

su('admin');

$jenkinsTest = new jenkinsModelTest();

r($jenkinsTest->getDepthJobsDirectTest()) && p() && e('{"\/job\/simpleJob\/":"Simple Job","\/job\/paramJob\/":"Param Job","folder1":{"\/job\/folder1\/job\/subJob1\/":"Sub Job 1","\/job\/folder1\/job\/subJob2\/":"Sub Job 2"}}'); // 测试正常情况：深度为1时解析Jenkins作业结构
r($jenkinsTest->getDepthJobsEmptyTest()) && p() && e('[]'); // 测试空数据：传入空的作业数组
r($jenkinsTest->getDepthJobsMaxDepthTest()) && p() && e('[]'); // 测试递归深度：超过最大深度限制（depth > 4）
r($jenkinsTest->getDepthJobsInvalidDataTest()) && p() && e('[]'); // 测试异常输入：传入无效的作业数据结构
r($jenkinsTest->getDepthJobsJobTypeTest()) && p() && e('{"\/job\/buildableJob\/":"Buildable Job","\/job\/regularJob\/":"Regular Job","multibranchPipeline":[]}'); // 测试作业类型识别：区分文件夹和可执行作业
r($jenkinsTest->getDepthJobsUrlEncodingTest()) && p() && e('{"\/job\/hello%20world\/":"Hello World","\/job\/test-job\/":"Test Job"}'); // 测试URL解析：处理包含特殊字符的作业URL
r($jenkinsTest->getDepthJobsFolderTest()) && p() && e('{"folder":[]}'); // 测试文件夹处理：识别文件夹类型并处理为空数组
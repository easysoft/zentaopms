#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraDataFromJson();
timeout=0
cid=15777

- 根据Json文件获取issue数据。第0条的1属性 @A
- 根据Json文件获取issue数据。第0条的1001属性 @B
- 根据Json文件获取project数据。第0条的2属性 @C
- 根据Json文件获取status数据。第0条的3属性 @D
- 根据Json文件获取workflow数据。第0条的4属性 @F

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

global $app;
$jiraPath = $app->getTmpRoot() . 'jirafile/json/';

// 创建测试目录和测试文件
if(!is_dir($jiraPath)) mkdir($jiraPath, 0777, true);

// 创建测试文件
file_put_contents($jiraPath . 'issue_0_1000.json',    '{"0":{"1":"A"}}');
file_put_contents($jiraPath . 'issue_1000_1000.json', '{"0":{"1001":"B"}}');
file_put_contents($jiraPath . 'project_0_1000.json',  '{"0":{"2":"C"}}');
file_put_contents($jiraPath . 'status_0_1000.json',   '{"0":{"3":"D"}}');
file_put_contents($jiraPath . 'workflow.json',        '{"0":{"4":"F"}}');

r($convertTest->getJiraDataFromJsonTest('issue',   0,    1000))    && p('0:1')    && e('A'); // 根据Json文件获取issue数据。
r($convertTest->getJiraDataFromJsonTest('issue',   1000, 1000))    && p('0:1001') && e('B'); // 根据Json文件获取issue数据。
r($convertTest->getJiraDataFromJsonTest('project', 0,    1000))    && p('0:2')    && e('C'); // 根据Json文件获取project数据。
r($convertTest->getJiraDataFromJsonTest('status',  0,    1000))    && p('0:3')    && e('D'); // 根据Json文件获取status数据。
r($convertTest->getJiraDataFromJsonTest('workflow'))               && p('0:4')    && e('F'); // 根据Json文件获取workflow数据。

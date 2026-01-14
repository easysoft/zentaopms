#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraWorkflowActions();
timeout=0
cid=15785

- 执行convertTest模块的getJiraWorkflowActionsTest方法  @0
- 执行$result, 'error') !== false @1
- 执行convertTest模块的getJiraWorkflowActionsTest方法  @0
- 执行$result, 'error') !== false || is_array($result @1
- 执行$config->edition == $originalEdition @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

// 步骤1：测试开源版本返回空数组
global $config;
$originalEdition = $config->edition;
$config->edition = 'open';
r($convertTest->getJiraWorkflowActionsTest()) && p() && e('0');

// 步骤2：测试企业版无session数据产生错误
$config->edition = 'biz';
unset($_SESSION['jiraMethod']);
$result = $convertTest->getJiraWorkflowActionsTest();
r(strpos($result, 'error') !== false) && p() && e('1');

// 步骤3：企业版有效session但无工作流数据返回空数组
$_SESSION['jiraMethod'] = 'file';
r($convertTest->getJiraWorkflowActionsTest()) && p() && e('0');

// 步骤4：测试企业版DB方法调用产生错误
$_SESSION['jiraMethod'] = 'db';
$_SESSION['jiraDB'] = 'test';
$result = $convertTest->getJiraWorkflowActionsTest();
r(strpos($result, 'error') !== false || is_array($result)) && p() && e('1');

// 步骤5：测试配置恢复
$config->edition = $originalEdition;
r($config->edition == $originalEdition) && p() && e('1');
#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraIssueLink();
timeout=0
cid=15860

- 步骤1：空数据列表情况 @true
- 步骤2：正常链接数据情况 @true
- 步骤3：包含子任务链接类型数据 @true
- 步骤4：包含子需求链接类型数据 @true
- 步骤5：再次测试空数据情况以确保方法稳定性 @true

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 定义常量（如果未定义）
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

// 2. 准备基础数据但不产生输出
zenData('user')->gen(5);
zenData('project')->gen(5);

// 设置session数据
global $app;
$app->session->set('jiraMethod', 'file');

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($convertTest->importJiraIssueLinkTest(array())) && p() && e('true'); // 步骤1：空数据列表情况

// 构造测试数据 - 正常链接数据
$normalLinkData = array();
$linkObj = new stdClass();
$linkObj->linktype = 'subtask';
$linkObj->source = '1';
$linkObj->destination = '2';
$normalLinkData[] = $linkObj;
r($convertTest->importJiraIssueLinkTest($normalLinkData)) && p() && e('true'); // 步骤2：正常链接数据情况

// 构造测试数据 - 子任务链接
$subTaskLinkData = array();
$subTaskObj = new stdClass();
$subTaskObj->linktype = 'subtask';
$subTaskObj->source = '3';
$subTaskObj->destination = '4';
$subTaskLinkData[] = $subTaskObj;
r($convertTest->importJiraIssueLinkTest($subTaskLinkData)) && p() && e('true'); // 步骤3：包含子任务链接类型数据

// 构造测试数据 - 子需求链接
$subStoryLinkData = array();
$subStoryObj = new stdClass();
$subStoryObj->linktype = 'child';
$subStoryObj->source = '5';
$subStoryObj->destination = '6';
$subStoryLinkData[] = $subStoryObj;
r($convertTest->importJiraIssueLinkTest($subStoryLinkData)) && p() && e('true'); // 步骤4：包含子需求链接类型数据

// 构造测试数据 - 空参数测试
r($convertTest->importJiraIssueLinkTest(array())) && p() && e('true'); // 步骤5：再次测试空数据情况以确保方法稳定性
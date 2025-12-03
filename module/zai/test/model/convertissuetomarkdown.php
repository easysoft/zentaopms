#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertIssueToMarkdown();
timeout=0
cid=0

- 生成标题包含问题编号 @1
- Markdown 包含 basic 章节 @1
- Markdown 字段包含状态信息 @1
- 追加描述章节内容 @1
- 追加解决方案章节内容 @1
- 属性包含 issueType 字段 @bug

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

$langData = array(
    'common'   => '问题',
    'sections' => array(
        'basic'    => '基本信息',
        'desc'     => '详情',
        'solution' => '解决方案'
    ),
    'fields' => array(
        'status'     => '状态',
        'pri'        => '优先级',
        'severity'   => '严重程度',
        'project'    => '项目',
        'execution'  => '执行',
        'assignedTo' => '指派人',
        'issueType'  => '类型'
    )
);

$issue = new stdClass();
$issue->id          = 21;
$issue->title       = '接口异常';
$issue->status      = 'active';
$issue->pri         = 'high';
$issue->severity    = 'critical';
$issue->project     = 'proj1';
$issue->execution   = 'exec1';
$issue->assignedTo  = 'dev';
$issue->issueType   = 'bug';
$issue->desc        = '<p>接口请求失败</p>';
$issue->resolutionComment = '<p>回滚版本</p>';

$result = $zai->convertIssueToMarkdownTest($issue, $langData);

/* 生成标题包含问题编号 */
$titleMatch = isset($result['title']) && $result['title'] === '问题 #21 接口异常' ? '1' : '0';
r($titleMatch) && p() && e('1'); // 生成标题包含问题编号

/* Markdown 包含 basic 章节 */
$hasBasicSection = strpos($result['content'], '## 基本信息') !== false ? '1' : '0';
r($hasBasicSection) && p() && e('1'); // Markdown 包含 basic 章节

/* Markdown 字段包含状态信息 */
$hasStatusLine = strpos($result['content'], '* 状态: active') !== false ? '1' : '0';
r($hasStatusLine) && p() && e('1'); // Markdown 字段包含状态信息

/* 追加描述章节内容 */
$hasDescSection = (strpos($result['content'], '## 详情') !== false && strpos($result['content'], '接口请求失败') !== false) ? '1' : '0';
r($hasDescSection) && p() && e('1'); // 追加描述章节内容

/* 追加解决方案章节内容 */
$hasSolutionSection = (strpos($result['content'], '## 解决方案') !== false && strpos($result['content'], '回滚版本') !== false) ? '1' : '0';
r($hasSolutionSection) && p() && e('1'); // 追加解决方案章节内容

/* 属性包含 issueType 字段 */
$issueType = isset($result['attrs']['issueType']) ? $result['attrs']['issueType'] : '';
$issueTypeMatch = $issueType === 'bug' ? '1' : '0';
r($issueTypeMatch) && p() && e('1'); // 属性包含 issueType 字段

/* 属性包含状态字段 */
$statusAttr = isset($result['attrs']['status']) ? $result['attrs']['status'] : '';
$statusMatch = $statusAttr === 'active' ? '1' : '0';
r($statusMatch) && p() && e('1'); // 属性包含状态字段

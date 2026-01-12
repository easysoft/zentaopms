#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertOpportunityToMarkdown();
timeout=0
cid=0

- 生成标题包含机会编号 @1
- Markdown 包含基本信息章节 @1
- 字段列表包含类型信息 @1
- 属性包含 project 字段 @proj1
- 追加描述章节内容 @1
- 属性包含 opportunityType 字段 @growth

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

$langData = array(
    'common'   => '机会',
    'sections' => array(
        'basic' => '基本信息',
        'desc'  => '机会描述'
    ),
    'fields' => array(
        'status'        => '状态',
        'opportunityType' => '类型',
        'owner'         => '负责人',
        'assignedTo'    => '指派人',
        'project'       => '项目',
        'product'       => '产品',
        'pri'           => '优先级'
    )
);

$opportunity = new stdClass();
$opportunity->id              = 12;
$opportunity->title           = '渠道拓展';
$opportunity->name            = '渠道拓展';
$opportunity->status          = 'open';
$opportunity->opportunityType = 'growth';
$opportunity->type            = 'growth';
$opportunity->owner           = 'biz';
$opportunity->assignedTo      = 'sales';
$opportunity->project         = 'proj1';
$opportunity->product         = 'prod1';
$opportunity->pri             = 'high';
$opportunity->desc            = '<p>拓展合作伙伴</p>';

$result = $zai->convertOpportunityToMarkdownTest($opportunity, $langData);

/* 生成标题包含机会编号 */
$titleMatch = isset($result['title']) && $result['title'] === '机会 #12 渠道拓展' ? '1' : '0';
r($titleMatch) && p() && e('1'); // 生成标题包含机会编号

/* Markdown 包含基本信息章节 */
$hasBasicSection = strpos($result['content'], '## 基本信息') !== false ? '1' : '0';
r($hasBasicSection) && p() && e('1'); // Markdown 包含基本信息章节

/* 字段列表包含类型信息 */
$hasTypeLine = strpos($result['content'], '* 类型: growth') !== false ? '1' : '0';
r($hasTypeLine) && p() && e('1'); // 字段列表包含类型信息

/* 属性包含 project 字段 */
$projectAttr = isset($result['attrs']['project']) ? $result['attrs']['project'] : '';
$projectMatch = $projectAttr === 'proj1' ? '1' : '0';
r($projectMatch) && p() && e('1'); // 属性包含 project 字段

/* 追加描述章节内容 */
$hasDescSection = (strpos($result['content'], '## 机会描述') !== false && strpos($result['content'], '拓展合作伙伴') !== false) ? '1' : '0';
r($hasDescSection) && p() && e('1'); // 追加描述章节内容

/* 属性包含 type 字段 */
$typeAttr = isset($result['attrs']['type']) ? $result['attrs']['type'] : '';
$typeMatch = $typeAttr === 'growth' ? '1' : '0';
r($typeMatch) && p() && e('1'); // 属性包含 type 字段

#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertPlanToMarkdown();
timeout=0
cid=0

- 生成标题包含计划编号 @1
- Markdown 包含基本信息章节 @1
- 字段列表包含负责人 @1
- 属性包含项目字段 @projectA
- 追加描述章节内容 @1
- 追加里程碑章节内容 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

$langData = array(
    'common'   => '计划',
    'sections' => array(
        'basic'     => '基本信息',
        'desc'      => '计划描述',
        'milestone' => '里程碑'
    ),
    'fields' => array(
        'status' => '状态',
        'owner'  => '负责人',
        'begin'  => '开始',
        'end'    => '结束',
        'pri'    => '优先级',
        'product'=> '产品',
        'project'=> '项目'
    )
);

$plan = new stdClass();
$plan->id       = 5;
$plan->title    = '版本规划';
$plan->status   = 'doing';
$plan->owner    = 'pm';
$plan->begin    = '2025-01-01';
$plan->end      = '2025-03-31';
$plan->pri      = '1';
$plan->product  = 'prod1';
$plan->project  = 'projectA';
$plan->desc     = '<p>里程碑说明</p>';
$plan->milestones = array(
    array('name' => '冻结需求', 'date' => '2025-02-01')
);

$result = $zai->convertPlanToMarkdownTest($plan, $langData);

/* 生成标题包含计划编号 */
$titleMatch = isset($result['title']) && $result['title'] === '计划 #5 版本规划' ? '1' : '0';
r($titleMatch) && p() && e('1'); // 生成标题包含计划编号

/* Markdown 包含基本信息章节 */
$hasBasicSection = strpos($result['content'], '## 基本信息') !== false ? '1' : '0';
r($hasBasicSection) && p() && e('1'); // Markdown 包含基本信息章节

/* 字段列表包含负责人 */
$hasOwnerLine = strpos($result['content'], '* 负责人: pm') !== false ? '1' : '0';
r($hasOwnerLine) && p() && e('1'); // 字段列表包含负责人

/* 属性包含项目字段 */
$projectAttr = isset($result['attrs']['project']) ? $result['attrs']['project'] : '';
$projectMatch = $projectAttr === 'projectA' ? '1' : '0';
r($projectMatch) && p() && e('1'); // 属性包含项目字段

/* 追加描述章节内容 */
$hasDescSection = (strpos($result['content'], '## 计划描述') !== false && strpos($result['content'], '里程碑说明') !== false) ? '1' : '0';
r($hasDescSection) && p() && e('1'); // 追加描述章节内容

/* 追加里程碑章节内容 */
$hasMilestone = (strpos($result['content'], '## 里程碑') !== false && strpos($result['content'], '冻结需求') !== false) ? '1' : '0';
r($hasMilestone) && p() && e('1'); // 追加里程碑章节内容

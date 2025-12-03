#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertRiskToMarkdown();
timeout=0
cid=0

- 生成标题包含风险编号 @1
- Markdown 包含基本信息章节 @1
- 字段列表包含概率项 @1
- 追加预防措施章节 @1
- 追加补救措施章节 @1
- 属性包含概率字段 @high

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

$langData = array(
    'common'   => '风险',
    'sections' => array(
        'basic'      => '基本信息',
        'desc'       => '风险描述',
        'prevention' => '预防措施',
        'remedy'     => '补救措施'
    ),
    'fields' => array(
        'status'      => '状态',
        'probability' => '概率',
        'impact'      => '影响',
        'assignedTo'  => '负责人',
        'strategy'    => '策略'
    )
);

$risk = new stdClass();
$risk->id          = 8;
$risk->name        = '数据泄露';
$risk->status      = 'open';
$risk->probability = 'high';
$risk->impact      = 'major';
$risk->strategy    = 'mitigate';
$risk->assignedTo  = 'security';
$risk->desc        = '<p>存在泄露风险</p>';
$risk->prevention  = '<p>加强审计</p>';
$risk->remedy      = '<p>应急响应</p>';

$result = $zai->convertRiskToMarkdownTest($risk, $langData);

/* 生成标题包含风险编号 */
$titleMatch = isset($result['title']) && $result['title'] === '风险 #8 数据泄露' ? '1' : '0';
r($titleMatch) && p() && e('1'); // 生成标题包含风险编号

/* Markdown 包含基本信息章节 */
$hasBasicSection = strpos($result['content'], '## 基本信息') !== false ? '1' : '0';
r($hasBasicSection) && p() && e('1'); // Markdown 包含基本信息章节

/* 字段列表包含概率项 */
$hasProbabilityLine = strpos($result['content'], '* 概率: high') !== false ? '1' : '0';
r($hasProbabilityLine) && p() && e('1'); // 字段列表包含概率项

/* 追加预防措施章节 */
$hasPrevention = (strpos($result['content'], '## 预防措施') !== false && strpos($result['content'], '加强审计') !== false) ? '1' : '0';
r($hasPrevention) && p() && e('1'); // 追加预防措施章节

/* 追加补救措施章节 */
$hasRemedy = (strpos($result['content'], '## 补救措施') !== false && strpos($result['content'], '应急响应') !== false) ? '1' : '0';
r($hasRemedy) && p() && e('1'); // 追加补救措施章节

/* 属性包含概率字段 */
$probabilityAttr = isset($result['attrs']['probability']) ? $result['attrs']['probability'] : '';
$probabilityMatch = $probabilityAttr === 'high' ? '1' : '0';
r($probabilityMatch) && p() && e('1'); // 属性包含概率字段

/* 属性包含状态字段 */
$statusAttr = isset($result['attrs']['status']) ? $result['attrs']['status'] : '';
$statusMatch = $statusAttr === 'open' ? '1' : '0';
r($statusMatch) && p() && e('1'); // 属性包含状态字段

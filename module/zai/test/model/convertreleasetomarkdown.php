#!/usr/bin/env php
<?php

/**
title=测试 zaiModel::convertReleaseToMarkdown();
timeout=0
cid=0

- 生成标题包含发布编号 @1
- Markdown 包含基本信息章节 @1
- 字段列表包含系统字段 @1
- 属性包含构建字段 @buildA
- 追加描述章节内容 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$zai = new zaiModelTest();

$langData = array(
    'common'   => '发布',
    'sections' => array(
        'basic' => '基本信息',
        'desc'  => '发布描述'
    ),
    'fields' => array(
        'status'  => '状态',
        'system'  => '系统',
        'project' => '项目',
        'build'   => '构建'
    )
);

$release = new stdClass();
$release->id      = 8;
$release->name    = 'V1.0.0';
$release->status  = 'normal';
$release->system  = 'zentao';
$release->project = 'proj1';
$release->build   = 'buildA';
$release->desc    = '<p>正式发布版本</p>';

$result = $zai->convertReleaseToMarkdownTest($release, $langData);

/* 生成标题包含发布编号 */
$titleMatch = isset($result['title']) && $result['title'] === '发布 #8 V1.0.0' ? '1' : '0';
r($titleMatch) && p() && e('1'); // 生成标题包含发布编号

/* Markdown 包含基本信息章节 */
$hasBasicSection = strpos($result['content'], '## 基本信息') !== false ? '1' : '0';
r($hasBasicSection) && p() && e('1'); // Markdown 包含基本信息章节

/* 字段列表包含系统字段 */
$hasSystemLine = strpos($result['content'], '* 系统: zentao') !== false ? '1' : '0';
r($hasSystemLine) && p() && e('1'); // 字段列表包含系统字段

/* 属性包含构建字段 */
$buildAttr = isset($result['attrs']['build']) ? $result['attrs']['build'] : '';
$buildMatch = $buildAttr === 'buildA' ? '1' : '0';
r($buildMatch) && p() && e('1'); // 属性包含构建字段

/* 追加描述章节内容 */
$hasDescSection = (strpos($result['content'], '## 发布描述') !== false && strpos($result['content'], '正式发布版本') !== false) ? '1' : '0';
r($hasDescSection) && p() && e('1'); // 追加描述章节内容

#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertCaseToMarkdown();
timeout=0
cid=0

- 生成标题包含编号 @1
- Markdown 包含基本信息章节 @1
- 字段列表包含状态项 @1
- 属性包含状态字段 @normal
- 追加前置条件章节 @1
- 追加执行步骤章节 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$zai = new zaiModelTest();

$langData = array(
    'common'   => '用例',
    'sections' => array(
        'basic'        => '基本信息',
        'precondition' => '前置条件',
        'steps'        => '执行步骤'
    ),
    'fields' => array(
        'status'      => '状态',
        'pri'         => '优先级',
        'module'      => '模块',
        'story'       => '关联需求',
        'assignedTo'  => '指派人',
        'expect'      => '期望',
        'execution'   => '执行',
        'type'        => '类型'
    )
);

$case = new stdClass();
$case->id          = 10;
$case->title       = '登录校验';
$case->status      = 'normal';
$case->pri         = '2';
$case->module      = 'login';
$case->story       = 'story1';
$case->assignedTo  = 'tester';
$case->execution   = 'exec1';
$case->type        = 'functional';
$case->precondition = '用户已注册';
$case->steps = array(
    array('desc' => '打开登录页', 'expect' => '页面展示'),
    array('desc' => '输入错误密码', 'expect' => '提示错误')
);

$result = $zai->convertCaseToMarkdownTest($case, $langData);

/* 生成标题包含编号 */
$titleMatch = isset($result['title']) && $result['title'] === '用例 #10 登录校验' ? '1' : '0';
r($titleMatch) && p() && e('1'); // 生成标题包含编号

/* Markdown 包含基本信息章节 */
$hasBasicSection = strpos($result['content'], '## 基本信息') !== false ? '1' : '0';
r($hasBasicSection) && p() && e('1'); // Markdown 包含基本信息章节

/* 字段列表包含状态项 */
$hasStatusLine = strpos($result['content'], '* 状态: normal') !== false ? '1' : '0';
r($hasStatusLine) && p() && e('1'); // 字段列表包含状态项

/* 属性包含状态字段 */
$statusAttr = isset($result['attrs']['status']) ? $result['attrs']['status'] : '';
r($statusAttr) && p() && e('normal'); // 属性包含状态字段

/* 追加前置条件章节 */
$hasPrecondition = strpos($result['content'], '## 前置条件') !== false ? '1' : '0';
r($hasPrecondition) && p() && e('1'); // 追加前置条件章节

/* 追加执行步骤章节 */
$hasStepSection = strpos($result['content'], '## 执行步骤') !== false ? '1' : '0';
r($hasStepSection) && p() && e('1'); // 追加执行步骤章节

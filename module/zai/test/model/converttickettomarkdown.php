#!/usr/bin/env php
<?php

/**
title=测试 zaiModel::convertTicketToMarkdown();
timeout=0
cid=0

- 生成标题包含工单编号 @1
- Markdown 包含基本信息章节 @1
- 字段列表包含优先级字段 @1
- 属性包含客户字段 @customerA
- 追加描述章节内容 @1
- 追加解决方案章节内容 @1
- 追加历史记录章节内容 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

$langData = array(
    'common'   => '工单',
    'sections' => array(
        'basic'      => '基本信息',
        'desc'       => '工单描述',
        'resolution' => '解决方案',
        'history'    => '历史记录'
    ),
    'fields' => array(
        'status'     => '状态',
        'type'       => '类型',
        'pri'        => '优先级',
        'assignedTo' => '负责人',
        'product'    => '产品',
        'project'    => '项目',
        'customer'   => '客户'
    )
);

$ticket = new stdClass();
$ticket->id         = 12;
$ticket->title      = '登录问题';
$ticket->status     = 'active';
$ticket->type       = 'bug';
$ticket->pri        = '2';
$ticket->assignedTo = 'dev1';
$ticket->product    = 'prod1';
$ticket->project    = 'proj1';
$ticket->customer   = 'customerA';
$ticket->desc       = '<p>用户无法登录系统</p>';
$ticket->resolution = '<p>修复密码验证逻辑</p>';
$ticket->history    = '<p>2025-01-01 创建工单</p>';

$result = $zai->convertTicketToMarkdownTest($ticket, $langData);

/* 生成标题包含工单编号 */
$titleMatch = isset($result['title']) && $result['title'] === '工单 #12 登录问题' ? '1' : '0';
r($titleMatch) && p() && e('1'); // 生成标题包含工单编号

/* Markdown 包含基本信息章节 */
$hasBasicSection = strpos($result['content'], '## 基本信息') !== false ? '1' : '0';
r($hasBasicSection) && p() && e('1'); // Markdown 包含基本信息章节

/* 字段列表包含优先级字段 */
$hasPriLine = strpos($result['content'], '* 优先级: 2') !== false ? '1' : '0';
r($hasPriLine) && p() && e('1'); // 字段列表包含优先级字段

/* 属性包含客户字段 */
$customerAttr = isset($result['attrs']['customer']) ? $result['attrs']['customer'] : '';
$customerMatch = $customerAttr === 'customerA' ? '1' : '0';
r($customerMatch) && p() && e('1'); // 属性包含客户字段

/* 追加描述章节内容 */
$hasDescSection = (strpos($result['content'], '## 工单描述') !== false && strpos($result['content'], '用户无法登录系统') !== false) ? '1' : '0';
r($hasDescSection) && p() && e('1'); // 追加描述章节内容

/* 追加解决方案章节内容 */
$hasResolutionSection = (strpos($result['content'], '## 解决方案') !== false && strpos($result['content'], '修复密码验证逻辑') !== false) ? '1' : '0';
r($hasResolutionSection) && p() && e('1'); // 追加解决方案章节内容

/* 追加历史记录章节内容 */
$hasHistorySection = (strpos($result['content'], '## 历史记录') !== false && strpos($result['content'], '2025-01-01 创建工单') !== false) ? '1' : '0';
r($hasHistorySection) && p() && e('1'); // 追加历史记录章节内容

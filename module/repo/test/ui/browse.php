#!/usr/bin/env php
<?php

/**

title=镜像代码库同步按钮 UI 测试（sync-code-btn 走 ajax-submit 范式）
timeout=0
cid=1

- 校验 sync-code-btn 渲染并带 ajax-submit class @sync-code-btn 已交由 ajax-submit 接管
- 校验 sync-code-btn 的 href 指向 ajaxMirrorSync @sync-code-btn href 指向 ajaxMirrorSync
- 校验点击 sync-code-btn 由 ajax-submit 接管无 JS 报错 @sync-code-btn 点击触发 ajax-submit 范式
- 校验 mirror 工具栏按钮存在性（syncing 与正常态二选一） @mirror 工具栏按钮存在性正确
- 校验 syncFailed 提示与详情链接结构一致 @syncFailed 提示与详情链接结构一致

*/

chdir(__DIR__);
include '../lib/ui/browse.ui.class.php';

/* 准备一条 mirror=1 的代码库测试数据；status 由 GitFox 接口实时回填，本地造数据仅保证 mirror 字段可达。 */
$repo = zenData('repo');
$repo->loadYaml('repo', false, 2);
$repo->id->range('1');
$repo->mirror->range('1');
$repo->gen(1);

$tester = new browseTester();

r($tester->checkSyncCodeBtnExists())          && p('status,message') && e('SUCCESS,sync-code-btn 已交由 ajax-submit 接管');
r($tester->checkSyncCodeBtnUrl())             && p('status,message') && e('SUCCESS,sync-code-btn href 指向 ajaxMirrorSync');
r($tester->checkSyncCodeBtnClick())           && p('status,message') && e('SUCCESS,sync-code-btn 点击触发 ajax-submit 范式');
r($tester->checkMirrorToolbarBtnPresent())    && p('status,message') && e('SUCCESS,mirror 工具栏按钮存在性正确');
r($tester->checkSyncFailureAlertStructure())  && p('status,message') && e('SUCCESS,syncFailed 提示与详情链接结构一致');

$tester->closeBrowser();

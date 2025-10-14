#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocDynamicBlock();
timeout=0
cid=0

- 执行$result->actions @1
- 执行$result->users @1
- 执行$result->actions) >= 0 @1
- 执行$result->users) >= 0 @1
- 执行actions) && isset($result模块的users方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

zendata('action')->loadYaml('action_printdocdynamicblock', false, 2)->gen(20);
zendata('user')->loadYaml('user_printdocdynamicblock', false, 2)->gen(10);

$docTable = zenData('doc');
$docTable->id->range('1-20');
$docTable->title->range('文档标题{20}');
$docTable->status->range('normal{18},deleted{2}');
$docTable->acl->range('open{10},private{5},custom{5}');
$docTable->gen(20);

$doclibTable = zenData('doclib');
$doclibTable->id->range('1-10');
$doclibTable->name->range('文档库{10}');
$doclibTable->acl->range('open{5},private{3},custom{2}');
$doclibTable->deleted->range('0{8},1{2}');
$doclibTable->gen(10);

$apiTable = zenData('api');
$apiTable->id->range('1-15');
$apiTable->title->range('API接口{15}');
$apiTable->status->range('normal{12},deleted{3}');
$apiTable->gen(15);

su('admin');

$blockTest = new blockTest();

$result = $blockTest->printDocDynamicBlockTest();
r(is_array($result->actions)) && p() && e('1');
r(is_array($result->users)) && p() && e('1');
r(count($result->actions) >= 0) && p() && e('1');
r(count($result->users) >= 0) && p() && e('1');
r(isset($result->actions) && isset($result->users)) && p() && e('1');
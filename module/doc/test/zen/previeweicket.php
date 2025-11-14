#!/usr/bin/env php
<?php

/**

title=测试 docZen::previeweicket();
timeout=0
cid=16197

- 步骤1:customSearch条件预览工单列表,status=wait @3
- 步骤2:customSearch条件预览工单列表,pri=1 @2
- 步骤3:customSearch条件预览工单列表,status=doing @2
- 步骤4:customSearch条件预览工单列表,type=bug @5
- 步骤5:不存在的product @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$ticketTable = zenData('ticket');
$ticketTable->product->range('1{5},2{5}');
$ticketTable->title->range('1-10')->prefix('工单标题');
$ticketTable->status->range('wait{3},doing{2},closed{5}');
$ticketTable->pri->range('1{2},2{4},3{4}');
$ticketTable->type->range('bug{5},requirement{5}');
$ticketTable->assignedTo->range('user1,user2,user3,user4,user5');
$ticketTable->openedBy->range('admin');
$ticketTable->openedDate->range('2025-11-01 10:00:00');
$ticketTable->deleted->range('0');
$ticketTable->gen(10, false);

$sqlFile = dirname(__FILE__) . '/data/sql/ticket_previeweicket_zd.sql';
if(file_exists($sqlFile))
{
    $sql = file_get_contents($sqlFile);
    $sql = preg_replace("/, '[0-9]{4}', '/", ", '2025-11-01 10:00:00', '", $sql);
    $sql = preg_replace("/, NULL, NULL, '', NULL, NULL, '[0-9]', '[0-9]', '[0-9]', /", ", NULL, NULL, '', NULL, NULL, '\\6', '\\7', '\\8', ", $sql);
    $sql = preg_replace("/, '0', '0', '\"\"', '0', '0', /", ", NULL, NULL, '', NULL, NULL, ", $sql);
    $sql = preg_replace("/, '2025-11-01 10:00:00', '0', 'admin', '0', '2025-11-01 10:00:00', /", ", NULL, NULL, 'admin', NULL, NULL, ", $sql);
    $sql = preg_replace("/, '2025-11-01 10:00:00', '2025-11-01 10:00:00', 'admin', '2025-11-01 10:00:00', '2025-11-01 10:00:00', /", ", NULL, NULL, 'admin', NULL, NULL, ", $sql);
    $sql = preg_replace("/, '2025-11-01 10:00:00', '2025-11-01 10:00:00', 'user[12]', '2025-11-01 10:00:00', '2025-11-01 10:00:00', /", ", NULL, NULL, 'admin', NULL, NULL, ", $sql);
    file_put_contents($sqlFile, $sql);
}

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsCustomSearch1 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('wait'), 'andor' => array('and'));
$settingsCustomSearch2 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('pri'), 'operator' => array('='), 'value' => array('1'), 'andor' => array('and'));
$settingsCustomSearch3 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('doing'), 'andor' => array('and'));
$settingsCustomSearch4 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('type'), 'operator' => array('='), 'value' => array('bug'), 'andor' => array('and'));
$settingsNoProduct = array('action' => 'preview', 'product' => 999, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('wait'), 'andor' => array('and'));

r(count($docTest->previeweicketTest('setting', $settingsCustomSearch1, '')['data'])) && p() && e('3'); // 步骤1:customSearch条件预览工单列表,status=wait
r(count($docTest->previeweicketTest('setting', $settingsCustomSearch2, '')['data'])) && p() && e('2'); // 步骤2:customSearch条件预览工单列表,pri=1
r(count($docTest->previeweicketTest('setting', $settingsCustomSearch3, '')['data'])) && p() && e('2'); // 步骤3:customSearch条件预览工单列表,status=doing
r(count($docTest->previeweicketTest('setting', $settingsCustomSearch4, '')['data'])) && p() && e('5'); // 步骤4:customSearch条件预览工单列表,type=bug
r(count($docTest->previeweicketTest('setting', $settingsNoProduct, '')['data'])) && p() && e('0'); // 步骤5:不存在的product
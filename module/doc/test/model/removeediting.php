#!/usr/bin/env php
<?php
/**

title=测试 docModel->removeEditing();
cid=1

- 测试空数据 @0
- 测试移除当前用户的正在编辑信息属性editing @~~
- 测试当前用户不在正在编辑列表中 @0
- 测试不存在的文档信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$timestamp = time();
$docTable = zdTable('doc')->config('doc');
$docTable->editingDate->range("`{\"admin\": $timestamp}`, `{\"user1\": $timestamp}`");
$docTable->gen(2);

zdTable('user')->gen(5);

$docIds = array(0, 1, 2, 3);

$docTester = new docTest();
r($docTester->removeEditingTest($docIds[0])) && p()          && e('0');  // 测试空数据
r($docTester->removeEditingTest($docIds[1])) && p('editing') && e('~~'); // 测试移除当前用户的正在编辑信息
r($docTester->removeEditingTest($docIds[2])) && p()          && e('0');  // 测试当前用户不在正在编辑列表中
r($docTester->removeEditingTest($docIds[3])) && p()          && e('0');  // 测试不存在的文档信息

#!/usr/bin/env php
<?php
/**

title=测试 docModel->checkOtherEditing();
cid=1

- 测试空数据 @0
- 测试文档1是否有在编辑的人 @1
- 测试文档2是否有在编辑的人 @0
- 测试不存在的文档是否有在编辑的人 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$timestamp = time();
$docTable = zdTable('doc')->config('doc');
$docTable->editingDate->range("`{\"user1\": $timestamp}`,[]");
$docTable->gen(2);
zdTable('user')->gen(5);

$docIds = array(0, 1, 2, 3);

$docTester = new docTest();
r($docTester->checkOtherEditingTest($docIds[0])) && p() && e('0'); // 测试空数据
r($docTester->checkOtherEditingTest($docIds[1])) && p() && e('1'); // 测试文档1是否有在编辑的人
r($docTester->checkOtherEditingTest($docIds[2])) && p() && e('0'); // 测试文档2是否有在编辑的人
r($docTester->checkOtherEditingTest($docIds[3])) && p() && e('0'); // 测试不存在的文档是否有在编辑的人

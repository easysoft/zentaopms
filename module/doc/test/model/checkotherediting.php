#!/usr/bin/env php
<?php
/**

title=测试 docModel->checkOtherEditing();
timeout=0
cid=16053

- 测试空数据 @0
- 测试文档1是否有在编辑的人 @1
- 测试文档2是否有在编辑的人 @0
- 测试文档3是否有在编辑的人 @1
- 测试不存在的文档是否有在编辑的人 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$timestamp = time();
$docTable = zenData('doc')->loadYaml('doc');
$docTable->editingDate->range("`{\"user1\": $timestamp}`,[]");
$docTable->gen(3);
zenData('user')->gen(5);

$docIds = array(0, 1, 2, 3, 100);

$docTester = new docModelTest();
r($docTester->checkOtherEditingTest($docIds[0])) && p() && e('0'); // 测试空数据
r($docTester->checkOtherEditingTest($docIds[1])) && p() && e('1'); // 测试文档1是否有在编辑的人
r($docTester->checkOtherEditingTest($docIds[2])) && p() && e('0'); // 测试文档2是否有在编辑的人
r($docTester->checkOtherEditingTest($docIds[3])) && p() && e('1'); // 测试文档3是否有在编辑的人
r($docTester->checkOtherEditingTest($docIds[4])) && p() && e('0'); // 测试不存在的文档是否有在编辑的人

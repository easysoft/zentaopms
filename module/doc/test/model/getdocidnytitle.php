#!/usr/bin/env php
<?php

/**

title=测试 docModel::getDocIdByTitle();
timeout=0
cid=16077

- 步骤1：空标题测试 @0
- 步骤2：无效originPageID（负数） @0
- 步骤3：无效originPageID（0） @0
- 步骤4：无效originPageID（过大值） @0
- 步骤5：有效参数但表不存在的情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$docTest = new docModelTest();

// 测试步骤（必须包含至少5个测试步骤）
r($docTest->getDocIdByTitleTest(1001, '')) && p() && e('0'); // 步骤1：空标题测试
r($docTest->getDocIdByTitleTest(-1, '用户手册')) && p() && e('0'); // 步骤2：无效originPageID（负数）
r($docTest->getDocIdByTitleTest(0, '用户手册')) && p() && e('0'); // 步骤3：无效originPageID（0）
r($docTest->getDocIdByTitleTest(999999, '用户手册')) && p() && e('0'); // 步骤4：无效originPageID（过大值）
r($docTest->getDocIdByTitleTest(1001, '用户手册')) && p() && e('0'); // 步骤5：有效参数但表不存在的情况
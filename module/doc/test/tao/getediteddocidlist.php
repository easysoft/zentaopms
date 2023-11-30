#!/usr/bin/env php
<?php
/**

title=测试 docModel->getEditedDocIdList();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('action')->config('action')->gen(30);
zdTable('user')->gen(5);
su('admin');

$docTester = new docTest();
r($docTester->getEditedDocIdListTest()) && p() && e('1;4;7;10;13;16;19;2;5;8'); // 获取编辑过的文档ID列表

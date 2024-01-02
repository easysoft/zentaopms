#!/usr/bin/env php
<?php
/**

title=测试 docModel->getEditedDocIdList();
cid=1

- 获取编辑过的文档ID列表 @1
- 获取编辑过的文档ID列表 @2
- 获取编辑过的文档ID列表 @4
- 获取编辑过的文档ID列表 @5
- 获取编辑过的文档ID列表 @7
- 获取编辑过的文档ID列表 @8
- 获取编辑过的文档ID列表 @10
- 获取编辑过的文档ID列表 @13
- 获取编辑过的文档ID列表 @16
- 获取编辑过的文档ID列表 @19

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('action')->config('action')->gen(30);
zdTable('user')->gen(5);
su('admin');

$docTester = new docTest();
$docIdList = $docTester->getEditedDocIdListTest();

r($docIdList[1])  && p() && e('1');  // 获取编辑过的文档ID列表
r($docIdList[2])  && p() && e('2');  // 获取编辑过的文档ID列表
r($docIdList[4])  && p() && e('4');  // 获取编辑过的文档ID列表
r($docIdList[5])  && p() && e('5');  // 获取编辑过的文档ID列表
r($docIdList[7])  && p() && e('7');  // 获取编辑过的文档ID列表
r($docIdList[8])  && p() && e('8');  // 获取编辑过的文档ID列表
r($docIdList[10]) && p() && e('10'); // 获取编辑过的文档ID列表
r($docIdList[13]) && p() && e('13'); // 获取编辑过的文档ID列表
r($docIdList[16]) && p() && e('16'); // 获取编辑过的文档ID列表
r($docIdList[19]) && p() && e('19'); // 获取编辑过的文档ID列表

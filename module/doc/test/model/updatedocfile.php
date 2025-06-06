#!/usr/bin/env php
<?php

/**

title=测试 docModel->updateDocFile();
cid=1

- 测试将文件ID为1的文件从文档ID为1的文档中移除属性files @2
- 测试将文件ID为4的文件从文档ID为1的文档中移除属性files @2
- 测试将文件ID为7的文件从文档ID为1的文档中移除属性files @2
- 测试将文件ID为8的文件从文档ID为1的文档中移除属性files @2
- 测试将文件ID为9的文件从文档ID为1的文档中移除属性files @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('file')->gen(6);

$docTable = zenData('doc');
$docTable->version->range('1');
$docTable->gen(5);

$doccontentTable = zenData('doccontent');
$doccontentTable->doc->range('1-5');
$doccontentTable->version->range('1');
$doccontentTable->files->range('`1,2`,`3,4`,`5,6`,`7,8`,`9,10`');
$doccontentTable->gen(5);

$docID      = 1;
$fileIdList = array(1, 4, 7, 8, 9);

$docTester = new docTest();
r($docTester->updateDocFileTest($docID, $fileIdList[0])) && p('files') && e('2'); // 测试将文件ID为1的文件从文档ID为1的文档中移除
r($docTester->updateDocFileTest($docID, $fileIdList[1])) && p('files') && e('2'); // 测试将文件ID为4的文件从文档ID为1的文档中移除
r($docTester->updateDocFileTest($docID, $fileIdList[2])) && p('files') && e('2'); // 测试将文件ID为7的文件从文档ID为1的文档中移除
r($docTester->updateDocFileTest($docID, $fileIdList[3])) && p('files') && e('2'); // 测试将文件ID为8的文件从文档ID为1的文档中移除
r($docTester->updateDocFileTest($docID, $fileIdList[4])) && p('files') && e('2'); // 测试将文件ID为9的文件从文档ID为1的文档中移除

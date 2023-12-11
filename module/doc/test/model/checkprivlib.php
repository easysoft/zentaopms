#!/usr/bin/env php
<?php

/**

title=测试 docModel->checkPrivLib();
cid=1

- 测试ID=1的文档库的权限 @1
- 测试ID=5的文档库的权限 @1
- 测试ID=6的文档库的权限 @1
- 测试ID=11的文档库的权限 @1
- 测试ID=20的文档库的权限 @1
- 测试ID=1的文档的权限 @1
- 测试ID=18的文档的权限 @1
- 测试ID=21的文档的权限 @1
- 测试ID=23的文档的权限 @1
- 测试ID=41的文档的权限 @1
- 测试ID=1的文档的权限 @1
- 测试ID=18的文档的权限 @1
- 测试ID=21的文档的权限 @1
- 测试ID=23的文档的权限 @1
- 测试ID=41的文档的权限 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$doclibTable = zdTable('doclib')->config('doclib');
$doclibTable->acl->range('open, default');
$doclibTable->gen(20);

zdTable('doc')->config('doc')->gen(45);
zdTable('project')->config('execution')->gen(10);
zdTable('product')->config('product')->gen(5);
zdTable('user')->gen(5);
su('admin');

$libIdList = array(1, 5, 6, 11, 20);
$docIdList = array(1, 18, 21, 23, 41);

$docTester = new docTest();
r($docTester->checkPrivLibTest('lib', $libIdList[0])) && p() && e('1'); // 测试ID=1的文档库的权限
r($docTester->checkPrivLibTest('lib', $libIdList[1])) && p() && e('1'); // 测试ID=5的文档库的权限
r($docTester->checkPrivLibTest('lib', $libIdList[2])) && p() && e('1'); // 测试ID=6的文档库的权限
r($docTester->checkPrivLibTest('lib', $libIdList[3])) && p() && e('1'); // 测试ID=11的文档库的权限
r($docTester->checkPrivLibTest('lib', $libIdList[4])) && p() && e('1'); // 测试ID=20的文档库的权限

r($docTester->checkPrivLibTest('doc', $docIdList[0])) && p() && e('1'); // 测试ID=1的文档的权限
r($docTester->checkPrivLibTest('doc', $docIdList[1])) && p() && e('1'); // 测试ID=18的文档的权限
r($docTester->checkPrivLibTest('doc', $docIdList[2])) && p() && e('1'); // 测试ID=21的文档的权限
r($docTester->checkPrivLibTest('doc', $docIdList[3])) && p() && e('1'); // 测试ID=23的文档的权限
r($docTester->checkPrivLibTest('doc', $docIdList[4])) && p() && e('1'); // 测试ID=41的文档的权限

r($docTester->checkPrivLibTest('doc', $docIdList[0], 'notdoc')) && p() && e('1'); // 测试ID=1的文档的权限
r($docTester->checkPrivLibTest('doc', $docIdList[1], 'notdoc')) && p() && e('1'); // 测试ID=18的文档的权限
r($docTester->checkPrivLibTest('doc', $docIdList[2], 'notdoc')) && p() && e('1'); // 测试ID=21的文档的权限
r($docTester->checkPrivLibTest('doc', $docIdList[3], 'notdoc')) && p() && e('1'); // 测试ID=23的文档的权限
r($docTester->checkPrivLibTest('doc', $docIdList[4], 'notdoc')) && p() && e('1'); // 测试ID=41的文档的权限

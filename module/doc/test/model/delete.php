#!/usr/bin/env php
<?php
/**

title=测试 docModel->delete();
cid=16062

- 测试删除ID为0的文档 @0
- 测试删除ID为1的文档 @1
- 测试删除ID不存在的文档 @0
- 测试删除表不是文档表且ID为0的文档 @0
- 测试删除表不是文档表且ID不存在的文档 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doc')->loadYaml('doc')->gen(10);
zenData('user')->gen(5);
su('admin');

$tableList = array(TABLE_DOC, TABLE_DOCLIB);
$docIdList = array(0, 1, 11);

$docTester = new docModelTest();
r($docTester->deleteTest($tableList[0], $docIdList[0])) && p() && e('0'); // 测试删除ID为0的文档
r($docTester->deleteTest($tableList[0], $docIdList[1])) && p() && e('1'); // 测试删除ID为1的文档
r($docTester->deleteTest($tableList[0], $docIdList[2])) && p() && e('0'); // 测试删除ID不存在的文档
r($docTester->deleteTest($tableList[1], $docIdList[0])) && p() && e('0'); // 测试删除表不是文档表且ID为0的文档
r($docTester->deleteTest($tableList[1], $docIdList[2])) && p() && e('0'); // 测试删除表不是文档表且ID不存在的文档

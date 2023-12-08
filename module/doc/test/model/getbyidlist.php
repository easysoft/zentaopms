#!/usr/bin/env php
<?php
/**

title=测试 docModel->getByIdList();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doc')->config('doc')->gen(50);
zdTable('doccontent')->gen(50);
zdTable('user')->gen(5);
su('admin');

$docIdList[0] = array();
$docIdList[1] = range(1, 30);
$docIdList[2] = range(1, 60);
$docIdList[3] = range(61, 80);

$docTester       = new docTest();
$emptyData       = $docTester->getByIdListTest($docIdList[0]);
$docData         = $docTester->getByIdListTest($docIdList[1]);
$hasNotExistData = $docTester->getByIdListTest($docIdList[2]);
$notExistData    = $docTester->getByIdListTest($docIdList[3]);

r($emptyData)       && p()                                                                        && e('0');                                               // 测试传入空的文档ID数组时，获取文档数据
r($docData)         && p('30:id,title,project,product,execution,lib,module,status,keywords,type') && e('30,文档标题30,11,0,101,20,3,draft,关键词30,html'); // 测试传入文档ID数组时，获取文档数据
r($hasNotExistData) && p('1:id,title,project,product,execution,lib,module,status,keywords,type')  && e('1,文档标题1,0,0,0,11,0,normal,关键词1,text');      // 测试传入文档ID数组且有不存在的数据时，获取文档数据
r($notExistData)    && p()                                                                        && e('0');                                               // 测试传入文档ID数组且数据不存在时，获取文档数据

r(count($emptyData))       && p() && e('0');  // 测试传入空的文档ID数组时，获取文档数据的数量
r(count($docData))         && p() && e('30'); // 测试传入文档ID数组时，获取文档数据的数量
r(count($hasNotExistData)) && p() && e('50'); // 测试传入文档ID数组且有不存在数据时，获取文档数据的数量
r(count($notExistData))    && p() && e('0');  // 测试传入文档ID数组且数据存在时，获取文档数据的数量

#!/usr/bin/env php
<?php

/**

title=测试 docModel->getContent();
timeout=0
cid=16073

- 获取docID=1、版本为1的文档内容
 - 属性doc @1
 - 属性version @1
 - 属性title @文档标题1
- 获取docID=2、版本为1的文档内容
 - 属性doc @2
 - 属性version @1
 - 属性title @文档标题2
- 获取docID=1、版本为2的文档内容为空 @0
- 获取docID=2、版本为2的文档内容为空 @0
- 获取不存在的文档内容 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doc')->loadYaml('doc')->gen(5);
zenData('doccontent')->loadYaml('doccontent')->gen(5);

$docIDList   = array('1', '2', '6');
$versionList = array('1', '2');

$docTester = new docModelTest();
r($docTester->getContentTest($docIDList[0], $versionList[0])) && p('doc,version,title') && e('1,1,文档标题1'); // 获取docID=1、版本为1的文档内容
r($docTester->getContentTest($docIDList[1], $versionList[0])) && p('doc,version,title') && e('2,1,文档标题2'); // 获取docID=2、版本为1的文档内容
r($docTester->getContentTest($docIDList[0], $versionList[1])) && p() && e(0);                                  // 获取docID=1、版本为2的文档内容为空
r($docTester->getContentTest($docIDList[1], $versionList[1])) && p() && e(0);                                  // 获取docID=2、版本为2的文档内容为空
r($docTester->getContentTest($docIDList[2], $versionList[0])) && p() && e(0);                                  // 获取不存在的文档内容
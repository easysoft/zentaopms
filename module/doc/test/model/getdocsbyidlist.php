#!/usr/bin/env php
<?php

/**

title=测试 docModel->getDocsByIdList();
cid=16081

- idList为空时获取空 @0
- 获取我的文档1第1条的title属性 @我的文档1
- 获取我的文档2第2条的title属性 @我的文档2
- 获取我的文档3第3条的title属性 @我的文档3
- 获取没有ID的文档 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->loadYaml('doc')->gen(50);
zenData('user')->gen(5);
su('admin');

$docIdList = array(array(), array(1), array(2), array(3), array(100));

global $tester;
$docTester = $tester->loadModel('doc');
r($docTester->getDocsByIdList($docIdList[0])) && p() && e('0'); // idList为空时获取空
r($docTester->getDocsByIdList($docIdList[1])) && p('1:title') && e('我的文档1'); // 获取我的文档1
r($docTester->getDocsByIdList($docIdList[2])) && p('2:title') && e('我的文档2'); // 获取我的文档2
r($docTester->getDocsByIdList($docIdList[3])) && p('3:title') && e('我的文档3'); // 获取我的文档3
r($docTester->getDocsByIdList($docIdList[4])) && p() && e('0'); // 获取没有ID的文档

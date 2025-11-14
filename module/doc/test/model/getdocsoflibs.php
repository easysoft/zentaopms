#!/usr/bin/env php
<?php

/**

title=测试 docModel->getDocsOfLibs();
timeout=0
cid=16084

- 获取产品库26下的文档列表
 - 第41条的lib属性 @26
 - 第41条的title属性 @产品文档41
- 获取项目库18下的文档列表
 - 第31条的lib属性 @18
 - 第31条的title属性 @项目文档31
- 获取我的文档库11下的文档列表
 - 第1条的lib属性 @11
 - 第1条的title属性 @我的文档1
- 获取产品库26下排除文档41的文档列表
 - 第42条的lib属性 @26
 - 第42条的title属性 @产品文档42
- 获取项目库18下排除文档31的文档列表
 - 第32条的lib属性 @18
 - 第32条的title属性 @项目文档32
- 获取我的文档库11下排除文档1的文档列表
 - 第2条的lib属性 @11
 - 第2条的title属性 @我的文档2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->loadYaml('doc')->gen(50);
zenData('user')->gen(5);
su('admin');

$libs      = array(array(26), array(18), array(11));
$spaceType = array('product', 'project', 'mine');
$excludeID = array(0, 41, 31, 1);

$docTester = new docTest();
r($docTester->getDocsOfLibsTest($libs[0], $spaceType[0], $excludeID[0])) && p('41:lib,title') && e('26,产品文档41'); // 获取产品库26下的文档列表
r($docTester->getDocsOfLibsTest($libs[1], $spaceType[1], $excludeID[0])) && p('31:lib,title') && e('18,项目文档31'); // 获取项目库18下的文档列表
r($docTester->getDocsOfLibsTest($libs[2], $spaceType[2], $excludeID[0])) && p('1:lib,title')  && e('11,我的文档1');  // 获取我的文档库11下的文档列表
r($docTester->getDocsOfLibsTest($libs[0], $spaceType[0], $excludeID[1])) && p('42:lib,title') && e('26,产品文档42'); // 获取产品库26下排除文档41的文档列表
r($docTester->getDocsOfLibsTest($libs[1], $spaceType[1], $excludeID[2])) && p('32:lib,title') && e('18,项目文档32'); // 获取项目库18下排除文档31的文档列表
r($docTester->getDocsOfLibsTest($libs[2], $spaceType[2], $excludeID[3])) && p('2:lib,title')  && e('11,我的文档2');  // 获取我的文档库11下排除文档1的文档列表

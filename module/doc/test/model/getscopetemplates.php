#!/usr/bin/env php
<?php

/**

title=测试 docModel->getScopeTemplates();
timeout=0
cid=1

- 获取产品范围下的文档模板
 - 第1条的id属性 @1
 - 第1条的title属性 @产品模板1
- 获取项目范围下的文档模板
 - 第6条的id属性 @6
 - 第6条的title属性 @项目模板6
- 获取执行范围下的文档模板
 - 第11条的id属性 @11
 - 第11条的title属性 @执行模板11
- 获取个人范围下的文档模板
 - 第16条的id属性 @16
 - 第16条的title属性 @个人模板16
- 获取产品范围下的文档模板
 - 第2条的id属性 @2
 - 第2条的title属性 @产品模板2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->loadYaml('template')->gen(20);
zenData('user')->gen(5);
su('admin');

$docTester = new docTest();
r($docTester->getScopeTemplatesTest()[1]) && p('1:id,title')  && e('1,产品模板1');   // 获取产品范围下的文档模板
r($docTester->getScopeTemplatesTest()[2]) && p('6:id,title')  && e('6,项目模板6');   // 获取项目范围下的文档模板
r($docTester->getScopeTemplatesTest()[3]) && p('11:id,title') && e('11,执行模板11'); // 获取执行范围下的文档模板
r($docTester->getScopeTemplatesTest()[4]) && p('16:id,title') && e('16,个人模板16'); // 获取个人范围下的文档模板
r($docTester->getScopeTemplatesTest()[1]) && p('2:id,title')  && e('2,产品模板2');   // 获取产品范围下的文档模板

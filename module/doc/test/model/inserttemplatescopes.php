#!/usr/bin/env php
<?php

/**

title=测试 docModel->insertTemplateScopes();
timeout=0
cid=16141

- 测试插入产品范围第1条的name属性 @产品范围
- 测试插入项目范围第2条的name属性 @项目范围
- 测试插入执行范围第3条的name属性 @执行范围
- 测试插入个人范围第4条的name属性 @个人范围
- 测试插入自定义范围第5条的name属性 @自定义范围

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doclib')->gen(0);

$scopeList = array('产品范围', '项目范围', '执行范围', '个人范围', '自定义范围');

$docTester = new docTest();
r($docTester->insertTemplateScopesTest($scopeList)) && p('1:name') && e('产品范围');   // 测试插入产品范围
r($docTester->insertTemplateScopesTest($scopeList)) && p('2:name') && e('项目范围');   // 测试插入项目范围
r($docTester->insertTemplateScopesTest($scopeList)) && p('3:name') && e('执行范围');   // 测试插入执行范围
r($docTester->insertTemplateScopesTest($scopeList)) && p('4:name') && e('个人范围');   // 测试插入个人范围
r($docTester->insertTemplateScopesTest($scopeList)) && p('5:name') && e('自定义范围'); // 测试插入自定义范围

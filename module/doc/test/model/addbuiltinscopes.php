#!/usr/bin/env php
<?php

/**

title=测试 docModel->addBuiltInScopes();
timeout=0
cid=16040

- 测试添加研发界面内置产品范围
 - 第1条的name属性 @产品
 - 第1条的type属性 @template
 - 第1条的main属性 @1
 - 第1条的vision属性 @rnd
- 测试添加研发界面内置项目范围
 - 第2条的name属性 @项目
 - 第2条的type属性 @template
 - 第2条的main属性 @1
 - 第2条的vision属性 @rnd
- 测试添加OR界面内置市场范围
 - 第5条的name属性 @市场
 - 第5条的type属性 @template
 - 第5条的main属性 @1
 - 第5条的vision属性 @or
- 测试添加OR界面内置产品范围
 - 第6条的name属性 @产品
 - 第6条的type属性 @template
 - 第6条的main属性 @1
 - 第6条的vision属性 @or
- 测试添加运营界面内置项目范围
 - 第8条的name属性 @项目
 - 第8条的type属性 @template
 - 第8条的main属性 @1
 - 第8条的vision属性 @lite

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('user')->gen(5);
su('admin');

zenData('doclib')->gen(0);
$docTester = new docTest();
r($docTester->addBuiltInScopesTest()) && p('1:name,type,main,vision') && e('产品,template,1,rnd');  // 测试添加研发界面内置产品范围
r($docTester->addBuiltInScopesTest()) && p('2:name,type,main,vision') && e('项目,template,1,rnd');  // 测试添加研发界面内置项目范围
r($docTester->addBuiltInScopesTest()) && p('5:name,type,main,vision') && e('市场,template,1,or');   // 测试添加OR界面内置市场范围
r($docTester->addBuiltInScopesTest()) && p('6:name,type,main,vision') && e('产品,template,1,or');   // 测试添加OR界面内置产品范围
r($docTester->addBuiltInScopesTest()) && p('8:name,type,main,vision') && e('项目,template,1,lite'); // 测试添加运营界面内置项目范围

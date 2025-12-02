#!/usr/bin/env php
<?php

/**

title=测试 docModel->getTemplatesModules();
timeout=0
cid=16131

- 获取所有模板类型模块
 - 第0条的id属性 @1
 - 第0条的name属性 @模板类型1
 - 第0条的type属性 @docTemplate
 - 第0条的root属性 @1
 - 第0条的grade属性 @1
- 获取root为1, grade为1的模板类型的模块
 - 第1条的id属性 @3
 - 第1条的name属性 @模板类型3
 - 第1条的type属性 @docTemplate
 - 第1条的root属性 @1
 - 第1条的grade属性 @1
- 获取root为1, grade为2的模板类型的模块
 - 第0条的id属性 @2
 - 第0条的name属性 @模板类型2
 - 第0条的type属性 @docTemplate
 - 第0条的root属性 @1
 - 第0条的grade属性 @2
- 获取root为2, grade为1的模板类型的模块
 - 第0条的id属性 @6
 - 第0条的name属性 @模板类型6
 - 第0条的type属性 @docTemplate
 - 第0条的root属性 @2
 - 第0条的grade属性 @1
- 获取root为2, grade为2的模板类型的模块
 - 第0条的id属性 @7
 - 第0条的name属性 @模板类型7
 - 第0条的type属性 @docTemplate
 - 第0条的root属性 @2
 - 第0条的grade属性 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('module')->loadYaml('templatemodule')->gen(10);
su('admin');

$roots  = array('all', 1, 2);
$grades = array('all', 1, 2);

$docTester = new docTest();
r($docTester->getTemplateModulesTest($roots[0], $grades[0])) && p('0:id,name,type,root,grade') && e('1,模板类型1,docTemplate,1,1');  // 获取所有模板类型模块
r($docTester->getTemplateModulesTest($roots[1], $grades[1])) && p('1:id,name,type,root,grade') && e('3,模板类型3,docTemplate,1,1');  // 获取root为1, grade为1的模板类型的模块
r($docTester->getTemplateModulesTest($roots[1], $grades[2])) && p('0:id,name,type,root,grade') && e('2,模板类型2,docTemplate,1,2');  // 获取root为1, grade为2的模板类型的模块
r($docTester->getTemplateModulesTest($roots[2], $grades[1])) && p('0:id,name,type,root,grade') && e('6,模板类型6,docTemplate,2,1');  // 获取root为2, grade为1的模板类型的模块
r($docTester->getTemplateModulesTest($roots[2], $grades[2])) && p('0:id,name,type,root,grade') && e('7,模板类型7,docTemplate,2,2');  // 获取root为2, grade为2的模板类型的模块
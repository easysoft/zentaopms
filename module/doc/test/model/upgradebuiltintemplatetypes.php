#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

/**

title=测试 docModel->upgradeBuiltinTemplateTypes();
timeout=0
cid=1

- 产品范围计划类型存储信息
 - 第1条的id属性 @1
 - 第1条的root属性 @1
 - 第1条的name属性 @计划
 - 第1条的parent属性 @0
 - 第1条的short属性 @Product plan
- 软件产品计划类型存储信息
 - 第2条的id属性 @2
 - 第2条的root属性 @1
 - 第2条的name属性 @软件产品计划
 - 第2条的parent属性 @1
 - 第2条的short属性 @Software product plan
- 项目范围计划类型存储信息
 - 第14条的id属性 @14
 - 第14条的root属性 @2
 - 第14条的name属性 @计划
 - 第14条的parent属性 @0
 - 第14条的short属性 @Project plan
- 项目计划类型存储信息
 - 第15条的id属性 @15
 - 第15条的root属性 @2
 - 第15条的name属性 @项目计划
 - 第15条的parent属性 @14
 - 第15条的short属性 @Project plan
- 执行范围计划类型存储信息
 - 第41条的id属性 @41
 - 第41条的root属性 @3
 - 第41条的name属性 @计划
 - 第41条的parent属性 @0
 - 第41条的short属性 @Execution plan
- 执行开发计划类型存储信息
 - 第42条的id属性 @42
 - 第42条的root属性 @3
 - 第42条的name属性 @开发计划
 - 第42条的parent属性 @41
 - 第42条的short属性 @Execution development plan
- 个人范围计划类型存储信息
 - 第62条的id属性 @62
 - 第62条的root属性 @4
 - 第62条的name属性 @计划
 - 第62条的parent属性 @0
 - 第62条的short属性 @Personal plan
- 个人工作计划类型存储信息
 - 第63条的id属性 @63
 - 第63条的root属性 @4
 - 第63条的name属性 @工作计划
 - 第63条的parent属性 @62
 - 第63条的short属性 @Personal work plan

*/

zenData('module')->gen(0);
zenData('user')->gen(5);
su('admin');

$docTester = new docTest();
r($docTester->upgradeBuiltinTemplateTypesTest())  && p('1:id,root,name,parent,short')  && e('1,1,计划,0,Product plan');                     // 产品范围计划类型存储信息
r($docTester->upgradeBuiltinTemplateTypesTest())  && p('2:id,root,name,parent,short')  && e('2,1,软件产品计划,1,Software product plan');    // 软件产品计划类型存储信息
r($docTester->upgradeBuiltinTemplateTypesTest())  && p('14:id,root,name,parent,short') && e('14,2,计划,0,Project plan');                    // 项目范围计划类型存储信息
r($docTester->upgradeBuiltinTemplateTypesTest())  && p('15:id,root,name,parent,short') && e('15,2,项目计划,14,Project plan');               // 项目计划类型存储信息
r($docTester->upgradeBuiltinTemplateTypesTest())  && p('41:id,root,name,parent,short') && e('41,3,计划,0,Execution plan');                  // 执行范围计划类型存储信息
r($docTester->upgradeBuiltinTemplateTypesTest())  && p('42:id,root,name,parent,short') && e('42,3,开发计划,41,Execution development plan'); // 执行开发计划类型存储信息
r($docTester->upgradeBuiltinTemplateTypesTest())  && p('62:id,root,name,parent,short') && e('62,4,计划,0,Personal plan');                   // 个人范围计划类型存储信息
r($docTester->upgradeBuiltinTemplateTypesTest())  && p('63:id,root,name,parent,short') && e('63,4,工作计划,62,Personal work plan');         // 个人工作计划类型存储信息

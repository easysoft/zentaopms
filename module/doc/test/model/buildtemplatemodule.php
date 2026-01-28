#!/usr/bin/env php
<?php

/**

title=测试 docModel->buildTemplateModule();
timeout=0
cid=16050

- 创建模板类型对象
 - 属性root @1
 - 属性parent @1
 - 属性name @模板名称
 - 属性short @ShortName
 - 属性grade @1
- 创建模板类型对象
 - 属性root @2
 - 属性parent @1
 - 属性name @模板名称
 - 属性short @ShortName
 - 属性grade @1
- 创建模板类型对象
 - 属性root @2
 - 属性parent @2
 - 属性name @模板名称
 - 属性short @ShortName
 - 属性grade @1
- 创建模板类型对象
 - 属性root @1
 - 属性parent @2
 - 属性name @模板名称
 - 属性short @ShortName
 - 属性grade @1
- 创建模板类型对象
 - 属性root @2
 - 属性parent @2
 - 属性name @模板名称
 - 属性short @ShortName
 - 属性grade @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$scopeList  = array(1, 2);
$parentList = array(1, 2);
$nameList   = array('模板名称');
$codeList   = array('ShortName');
$gradeList  = array(1, 2);

$docTester = new docModelTest();
r($docTester->buildTemplateModuleTest($scopeList[0], $parentList[0], $nameList[0], $codeList[0], $gradeList[0])) && p('root,parent,name,short,grade') && e('1,1,模板名称,ShortName,1'); // 创建模板类型对象
r($docTester->buildTemplateModuleTest($scopeList[1], $parentList[0], $nameList[0], $codeList[0], $gradeList[0])) && p('root,parent,name,short,grade') && e('2,1,模板名称,ShortName,1'); // 创建模板类型对象
r($docTester->buildTemplateModuleTest($scopeList[1], $parentList[1], $nameList[0], $codeList[0], $gradeList[0])) && p('root,parent,name,short,grade') && e('2,2,模板名称,ShortName,1'); // 创建模板类型对象
r($docTester->buildTemplateModuleTest($scopeList[0], $parentList[1], $nameList[0], $codeList[0], $gradeList[0])) && p('root,parent,name,short,grade') && e('1,2,模板名称,ShortName,1'); // 创建模板类型对象
r($docTester->buildTemplateModuleTest($scopeList[1], $parentList[1], $nameList[0], $codeList[0], $gradeList[1])) && p('root,parent,name,short,grade') && e('2,2,模板名称,ShortName,2'); // 创建模板类型对象

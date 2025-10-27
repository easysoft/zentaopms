#!/usr/bin/env php
<?php

/**

title=测试 personnelZen::setSelectObjectTips();
timeout=0
cid=0

- 执行personnelTest模块的setSelectObjectTipsTest方法，参数是1, 'project', 'project' 属性tips @请选择一个项目白名单
- 执行personnelTest模块的setSelectObjectTipsTest方法，参数是1, 'product', 'product' 属性tips @请选择一个产品白名单
- 执行personnelTest模块的setSelectObjectTipsTest方法，参数是1, 'program', 'program' 属性tips @请选择一个项目集白名单
- 执行personnelTest模块的setSelectObjectTipsTest方法，参数是1, 'sprint', 'execution' 属性tips @请选择一个项目或执行白名单
- 执行personnelTest模块的setSelectObjectTipsTest方法，参数是1, 'sprint', 'project' 属性tips @请选择一个项目或执行白名单

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/personnel.unittest.class.php';

su('admin');

$personnelTest = new personnelTest();

r($personnelTest->setSelectObjectTipsTest(1, 'project', 'project')) && p('tips') && e('请选择一个项目白名单');
r($personnelTest->setSelectObjectTipsTest(1, 'product', 'product')) && p('tips') && e('请选择一个产品白名单');
r($personnelTest->setSelectObjectTipsTest(1, 'program', 'program')) && p('tips') && e('请选择一个项目集白名单');
r($personnelTest->setSelectObjectTipsTest(1, 'sprint', 'execution')) && p('tips') && e('请选择一个项目或执行白名单');
r($personnelTest->setSelectObjectTipsTest(1, 'sprint', 'project')) && p('tips') && e('请选择一个项目或执行白名单');
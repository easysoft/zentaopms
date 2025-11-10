#!/usr/bin/env php
<?php

/**

title=测试 personnelZen::setSelectObjectTips();
timeout=0
cid=0

- 测试objectType为program时的objectName属性属性objectName @项目集
- 测试objectType为product时的objectName属性属性objectName @产品
- 测试objectType为project时的objectName属性属性objectName @项目
- 测试objectType为sprint时的objectName属性属性objectName @项目或执行
- 测试objectType为空时的objectName属性属性objectName @项目或执行
- 测试objectType为program时的tips属性属性tips @请选择一个项目集白名单
- 测试objectType为product时的tips属性属性tips @请选择一个产品白名单

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(5);
zenData('project')->gen(10);

su('admin');

$personnelTest = new personnelZenTest();

r($personnelTest->setSelectObjectTipsTest(1, 'program', 'personnel')) && p('objectName') && e('项目集'); // 测试objectType为program时的objectName属性
r($personnelTest->setSelectObjectTipsTest(1, 'product', 'personnel')) && p('objectName') && e('产品'); // 测试objectType为product时的objectName属性
r($personnelTest->setSelectObjectTipsTest(1, 'project', 'personnel')) && p('objectName') && e('项目'); // 测试objectType为project时的objectName属性
r($personnelTest->setSelectObjectTipsTest(1, 'sprint', 'personnel')) && p('objectName') && e('项目或执行'); // 测试objectType为sprint时的objectName属性
r($personnelTest->setSelectObjectTipsTest(1, '', 'personnel')) && p('objectName') && e('项目或执行'); // 测试objectType为空时的objectName属性
r($personnelTest->setSelectObjectTipsTest(1, 'program', 'personnel')) && p('tips') && e('请选择一个项目集白名单'); // 测试objectType为program时的tips属性
r($personnelTest->setSelectObjectTipsTest(1, 'product', 'personnel')) && p('tips') && e('请选择一个产品白名单'); // 测试objectType为product时的tips属性
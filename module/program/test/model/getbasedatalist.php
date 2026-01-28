#!/usr/bin/env php
<?php
/**
title=测试programTao::getBaseDataList();
timeout=0
cid=17680

- 获取id为3的path第1条的path属性 @,1,
- 获取id为2的path第2条的path属性 @,1,2,
- 获取id为3的path第3条的path属性 @,3,
- 获取id为4的path第4条的path属性 @,4,
- 获取id为5的path第5条的path属性 @,5,

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('team')->gen(5);
$programTable = zenData('project')->loadYaml('program');
$programTable->type->range('program');
$programTable->gen(40)->fixPath();

su('admin');

$tester = new programModelTest();
$programList = $tester->program->getBaseDataList(array(1, 2, 3, 4, 5));

r($programList) && p('1:path', ';') && e(',1,');   // 获取id为3的path
r($programList) && p('2:path', ';') && e(',1,2,'); // 获取id为2的path
r($programList) && p('3:path', ';') && e(',3,');   // 获取id为3的path
r($programList) && p('4:path', ';') && e(',4,');   // 获取id为4的path
r($programList) && p('5:path', ';') && e(',5,');   // 获取id为5的path

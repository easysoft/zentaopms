#!/usr/bin/env php
<?php

/**

title=测试 programModel::createDefaultProgram();
timeout=0
cid=17679

- 查询项目集数量 @1
- 查询默认项目集数据
 - 属性id @1
 - 属性type @program
- 查询默认项目集数据
 - 属性budgetUnit @CNY
 - 属性name @默认项目集
- 查询默认项目集数据属性hasProduct @1
- 查询默认项目集数据
 - 属性status @doing
 - 属性acl @open

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

zenData('project')->gen(0);
$programTester = new programTest();
$programTester->createDefaultProgramTest();

global $tester;
$programs = $tester->loadModel('program')->dao->select('*')->from(TABLE_PROGRAM)->fetchAll();
r(count($programs)) && p()                  && e('1');              // 查询项目集数量
r($programs[0])     && p('id,type')         && e('1,program');      // 查询默认项目集数据
r($programs[0])     && p('budgetUnit,name') && e('CNY,默认项目集'); // 查询默认项目集数据
r($programs[0])     && p('hasProduct')      && e('1');              // 查询默认项目集数据
r($programs[0])     && p('status,acl')      && e('doing,open');     // 查询默认项目集数据

#!/usr/bin/env php
<?php

/**

title=测试 programModel::getById();
timeout=0
cid=17682

- 通过id字段获取id=1的项目集。
 - 属性id @1
 - 属性name @项目集1
 - 属性budget @900000
 - 属性type @program
 - 属性status @wait
- 通过id字段获取id=2的项目集。
 - 属性id @2
 - 属性name @项目集2
 - 属性budget @899900
 - 属性type @program
 - 属性status @wait
- 通过id字段获取id=3的项目集。
 - 属性id @3
 - 属性name @项目集3
 - 属性budget @899800
 - 属性type @program
 - 属性status @doing
- 通过id字段获取id=1000的项目集，返回空 @0
- 通过id字段获取id=0的项目集，返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1-10');
$program->type->range('program');
$program->name->setFields(array(
    array('field' => 'name1', 'range' => '项目集'),
    array('field' => 'name2', 'range' => '1-10')
));
$program->gen(5);

$programTester = new programTest();

r($programTester->getByIDTest(1))    && p('id,name,budget,type,status') && e('1,项目集1,900000,program,wait');  // 通过id字段获取id=1的项目集。
r($programTester->getByIDTest(2))    && p('id,name,budget,type,status') && e('2,项目集2,899900,program,wait');  // 通过id字段获取id=2的项目集。
r($programTester->getByIDTest(3))    && p('id,name,budget,type,status') && e('3,项目集3,899800,program,doing'); // 通过id字段获取id=3的项目集。
r($programTester->getByIDTest(1000)) && p()                             && e('0');                              // 通过id字段获取id=1000的项目集，返回空
r($programTester->getByIDTest(0))    && p()                             && e('0');                              // 通过id字段获取id=0的项目集，返回空

#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1,2');
$program->name->range('项目集1,项目集2');
$program->type->range('program');
$program->status->range('wait');
$program->parent->range('0');
$program->path->range('1,2')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(2);

/**

title=测试 programModel::createStakeholder();
cid=1
pid=1

传入项目集1，动作为closed，判断是否可点击   >> 1
传入项目集1，动作为activate，判断是否可点击 >> 0
传入项目集1，动作为suspend，判断是否可点击  >> 1
传入项目集2，动作为closed，判断是否可点击   >> 1
传入项目集2，动作为activate，判断是否可点击 >> 0
传入项目集2，动作为activate，判断是否可点击 >> 1

*/

$programTester = new programTest();

$program1 = $programTester->getByIDTest(1);
$program2 = $programTester->getByIDTest(2);

r($programTester->isClickableTest($program1, 'close'))    && p('') && e('1'); // 传入项目集1，动作为closed，判断是否可点击
r($programTester->isClickableTest($program1, 'activate')) && p('') && e('0'); // 传入项目集1，动作为activate，判断是否可点击
r($programTester->isClickableTest($program1, 'suspend'))  && p('') && e('1'); // 传入项目集1，动作为suspend，判断是否可点击
r($programTester->isClickableTest($program2, 'close'))    && p('') && e('1'); // 传入项目集2，动作为closed，判断是否可点击
r($programTester->isClickableTest($program2, 'activate')) && p('') && e('0'); // 传入项目集2，动作为activate，判断是否可点击
r($programTester->isClickableTest($program2, 'suspend'))  && p('') && e('1'); // 传入项目集2，动作为activate，判断是否可点击

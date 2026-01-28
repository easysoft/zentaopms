#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1,2');
$program->name->range('项目集1,项目集2');
$program->type->range('program');
$program->status->range('wait');
$program->parent->range('0');
$program->path->range('1,2')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(2);

/**

title=测试 programModel::isClickable();
timeout=0
cid=17705

*/

$programTester = new programModelTest();

$program1 = $programTester->getByIDTest(1);
$program2 = $programTester->getByIDTest(2);

r($programTester->isClickableTest($program1, 'close'))    && p('') && e('1'); // 传入项目集1，动作为closed，判断是否可点击
r($programTester->isClickableTest($program1, 'activate')) && p('') && e('0'); // 传入项目集1，动作为activate，判断是否可点击
r($programTester->isClickableTest($program1, 'suspend'))  && p('') && e('1'); // 传入项目集1，动作为suspend，判断是否可点击
r($programTester->isClickableTest($program2, 'close'))    && p('') && e('1'); // 传入项目集2，动作为closed，判断是否可点击
r($programTester->isClickableTest($program2, 'activate')) && p('') && e('0'); // 传入项目集2，动作为activate，判断是否可点击
r($programTester->isClickableTest($program2, 'suspend'))  && p('') && e('1'); // 传入项目集2，动作为activate，判断是否可点击

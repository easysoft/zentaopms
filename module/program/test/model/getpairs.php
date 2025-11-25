#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1-20');
$program->name->range('1-20')->prefix('项目集');
$program->type->range('program');
$program->path->range('1-20')->prefix(',')->postfix(',');
$program->grade->range('1');
$program->status->range('wait,doing,suspended,closed');
$program->openedBy->range('admin,test1');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->deleted->range('0{15},1{5}');
$program->gen(20);

/**

title=测试 programModel::getPairs();
timeout=0
cid=17688

- 获取项目集个数 @15
- 获取所有项目集个数 @15
- 获取按照id正序的第一个项目集名称属性1 @项目集1
- 获取按照status正序的第一个项目集名称属性8 @项目集8
- 获取按照status正序的第一个项目集名称属性1 @项目集1

*/

$programTester = new programTest();

$programs1 = $programTester->getPairsTest();
$programs2 = $programTester->getPairsTest('true');
$programs3 = $programTester->getPairsTest('false', 'id_asc');
$programs4 = $programTester->getPairsTest('true',  'status_asc');
$programs5 = $programTester->getPairsTest('false', 'status_desc');

r(count($programs1))   && p()    && e('15');      // 获取项目集个数
r(count($programs2))   && p()    && e('15');      // 获取所有项目集个数
r(current($programs3)) && p('1') && e('项目集1'); // 获取按照id正序的第一个项目集名称
r(current($programs4)) && p('8') && e('项目集8'); // 获取按照status正序的第一个项目集名称
r(current($programs5)) && p('1') && e('项目集1'); // 获取按照status正序的第一个项目集名称

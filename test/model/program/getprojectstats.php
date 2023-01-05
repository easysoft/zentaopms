#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1-5');
$program->name->range('项目集1,项目集2,项目1,项目2,项目3');
$program->type->range('program{2},project{3}');
$program->status->range('doing{3},closed,doing');
$program->parent->range('0,0,1,1,2');
$program->grade->range('1{2},2{3}');
$program->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(5);

/**

title=测试 programModel::getProjectStats();
cid=1
pid=1

查看当前项目集下所有未完成的项目的个数                         >> 1
查看当前项目集下所有未完成的项目的个数                         >> 1
查看当前项目集下所有未完成的项目按照id倒序排的第一个项目集信息 >> 3,项目1
查看当前项目集下所有未完成的项目按照id正序排的第一个项目集信息 >> 5,项目3

*/

$programTester = new programTest();

$stats1 = $programTester->getProjectStatsTest(1);
$stats2 = $programTester->getProjectStatsTest(2, 'undone', 0, 'id_asc', null);

r(count($stats1))   && p()           && e('1');       // 查看当前项目集下所有未完成的项目的个数
r(count($stats2))   && p()           && e('1');       // 查看当前项目集下所有未完成的项目的个数
r(current($stats1)) && p('id,name')  && e('3,项目1'); // 查看当前项目集下所有未完成的项目按照id倒序排的第一个项目集信息
r(current($stats2)) && p('id,name')  && e('5,项目3'); // 查看当前项目集下所有未完成的项目按照id正序排的第一个项目集信息

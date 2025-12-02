#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

$program = zenData('project');
$program->id->range('1-8');
$program->name->range('项目集1,项目集2,项目1,项目2,项目3,项目4,项目5,项目6');
$program->type->range('program{2},project{6}');
$program->status->range('doing{3},closed,doing,closed,suspended,wait');
$program->parent->range('0,0,1,1,2,1{3}');
$program->grade->range('1{2},2{6}');
$program->path->range('1,2,`1,3`,`1,4`,`2,5`,`1,6`,`1,7`,`1,8`')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(8);

$team = zenData('team');
$team->id->range('1-5');
$team->root->range('3-5');
$team->type->range('project');
$team->account->range('admin');
$team->account->setFields(array(
    array('field' => 'account1', 'range' => 'admin,user{4}'),
    array('field' => 'account2', 'range' => '[],1-4'),
));
$team->gen(5);

$userquery = zenData('userquery');
$userquery->id->range(1);
$userquery->account->range('admin');
$userquery->module->range('project');
$userquery->title->range('搜索进行中的项目');
$userquery->sql->range("`(( 1  AND `status` = 'doing' ))`");
$userquery->form->range('``');
$userquery->gen(1);

zenData('user')->gen(5);
su('admin');

/**

title=测试 programModel::getProjectList();
timeout=0
cid=17696

- 获取所有项目列表数量 @6
- 获取未完成项目列表数量 @3
- 获取按照开始日期倒序排列的项目列表数量 @3
- 带项目集名称的项目列表数量 @3
- 带项目集名称的项目列表数量 @3
- 获取数据库所有项目列表数量 @3
- 获取项目集1下全部项目的数量 @5
- 获取项目集1下未开始项目的数量 @1
- 获取项目集1下未关闭项目的数量 @2
- 获取项目集1下进行中项目的数量 @1
- 获取项目集1下已暂停项目的数量 @1
- 获取项目集1下已关闭项目的数量 @2
- 获取项目集1下进行中项目的数量 @1
- 不带项目集名称的项目名称第4条的name属性 @项目2
- 获取未完成项目名称第8条的name属性 @项目6
- 获取按照开始日期倒序排列的项目名称第8条的name属性 @项目6
- 带项目集名称的项目名称第5条的name属性 @项目集2/项目3
- 带项目集名称的项目名称第5条的name属性 @项目集2/项目3
- 获取数据库所有项目的名称第3条的name属性 @项目集1/项目1
- 获取项目集1下全部项目的名称第3条的name属性 @项目1
- 获取项目集1下未开始项目的名称第8条的name属性 @项目6
- 获取项目集1下未关闭项目的名称第3条的name属性 @项目1
- 获取项目集1下进行中项目的名称第3条的name属性 @项目1
- 获取项目集1下已暂停项目的名称第7条的name属性 @项目5
- 获取项目集1下已关闭项目的名称第4条的name属性 @项目2
- 获取项目集1下进行中项目的名称第3条的name属性 @项目1

*/

global $tester;
$programTester = $tester->loadModel('program');

$allProjects              = $programTester->getProjectList(0);
$undoneProjects           = $programTester->getProjectList(0, 'undone');
$sortProjects             = $programTester->getProjectList(0, 'undone');
$withProgramProjects      = $programTester->getProjectList(0, 'undone', 0, 'begin_desc', 'end');
$withBaseProgramProjects  = $programTester->getProjectList(0, 'undone', 0, 'begin_desc', 'base');
$queryAllProjects         = $programTester->getProjectList(0, 'undone', 0, 'id_desc', 'end', true);
$programAllProjects       = $programTester->getProjectList(1, 'all');
$programWaitProjects      = $programTester->getProjectList(1, 'wait');
$programUndoneProjects    = $programTester->getProjectList(1, 'undone');
$programDoingProjects     = $programTester->getProjectList(1, 'doing');
$programSuspendedProjects = $programTester->getProjectList(1, 'suspended');
$programClosedProjects    = $programTester->getProjectList(1, 'closed');
$programSearchProjects    = $programTester->getProjectList(1, 'bysearch', 1);

r(count($allProjects))              && p() && e('6'); // 获取所有项目列表数量
r(count($undoneProjects))           && p() && e('3'); // 获取未完成项目列表数量
r(count($sortProjects))             && p() && e('3'); // 获取按照开始日期倒序排列的项目列表数量
r(count($withProgramProjects))      && p() && e('3'); // 带项目集名称的项目列表数量
r(count($withBaseProgramProjects))  && p() && e('3'); // 带项目集名称的项目列表数量
r(count($queryAllProjects))         && p() && e('3'); // 获取数据库所有项目列表数量
r(count($programAllProjects))       && p() && e('5'); // 获取项目集1下全部项目的数量
r(count($programWaitProjects))      && p() && e('1'); // 获取项目集1下未开始项目的数量
r(count($programUndoneProjects))    && p() && e('2'); // 获取项目集1下未关闭项目的数量
r(count($programDoingProjects))     && p() && e('1'); // 获取项目集1下进行中项目的数量
r(count($programSuspendedProjects)) && p() && e('1'); // 获取项目集1下已暂停项目的数量
r(count($programClosedProjects))    && p() && e('2'); // 获取项目集1下已关闭项目的数量
r(count($programSearchProjects))    && p() && e('1'); // 获取项目集1下进行中项目的数量

r($allProjects)              && p('4:name') && e('项目2');         // 不带项目集名称的项目名称
r($undoneProjects)           && p('8:name') && e('项目6');         // 获取未完成项目名称
r($sortProjects)             && p('8:name') && e('项目6');         // 获取按照开始日期倒序排列的项目名称
r($withProgramProjects)      && p('5:name') && e('项目集2/项目3'); // 带项目集名称的项目名称
r($withBaseProgramProjects)  && p('5:name') && e('项目集2/项目3'); // 带项目集名称的项目名称
r($queryAllProjects)         && p('3:name') && e('项目集1/项目1'); // 获取数据库所有项目的名称
r($programAllProjects)       && p('3:name') && e('项目1');         // 获取项目集1下全部项目的名称
r($programWaitProjects)      && p('8:name') && e('项目6');         // 获取项目集1下未开始项目的名称
r($programUndoneProjects)    && p('3:name') && e('项目1');         // 获取项目集1下未关闭项目的名称
r($programDoingProjects)     && p('3:name') && e('项目1');         // 获取项目集1下进行中项目的名称
r($programSuspendedProjects) && p('7:name') && e('项目5');         // 获取项目集1下已暂停项目的名称
r($programClosedProjects)    && p('4:name') && e('项目2');         // 获取项目集1下已关闭项目的名称
r($programSearchProjects)    && p('3:name') && e('项目1');         // 获取项目集1下进行中项目的名称

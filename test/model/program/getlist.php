#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1-20');
$program->name->range('1-20')->prefix('项目集');
$program->type->range('program');
$program->path->range('1-20')->prefix(',')->postfix(',');
$program->grade->range('1');
$program->status->range('wait,doing,suspended,closed');
$program->openedBy->range('admin,test1');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(20);

/**

title=测试 programModel::getList();
cid=1
pid=1

查看所有项目和项目集的个数 >> 20
查看分页后项目集和项目的个数 >> 10
查看所有'closed'的项目和项目集的个数 >> 5
查看所有'suspended'的项目和项目集的个数 >> 5
按照项目和项目集名称倒序获取第一个ID >> 9
按照ID正序获取项目和项目集列表第一个ID >> 1
获取顶级项目集个数 >> 20
获取分页后顶级项目集个数 >> 10
获取所有项目集个数 >> 20

*/

global $tester;
$programTester = new programTest();
$tester->app->loadClass('pager', $static = true);
$pager = new pager(0, 10, 1);

$allPorgrams       = $programTester->getListTest('all');
$pagerPrograms     = $programTester->getListTest('all', 'id_asc', $pager);
$closedPrograms    = $programTester->getListTest('closed');
$suspendedPrograms = $programTester->getListTest('closed');
$namedescPrograms  = $programTester->getListTest('all', 'name_desc');
$idascPrograms     = $programTester->getListTest('all', 'id_asc');
$topPrograms       = $programTester->getListTest('all', 'id_asc', null, 'top');
$topPagePrograms   = $programTester->getListTest('all', 'id_asc', $pager, 'top');
$childPrograms     = $programTester->getListTest('all', 'id_asc', null, 'child', $topPrograms);

r(count($allPorgrams))       && p() && e('20'); // 查看所有项目和项目集的个数
r(count($pagerPrograms))     && p() && e('10'); // 查看分页后项目集和项目的个数
r(count($closedPrograms))    && p() && e('5');  // 查看所有'closed'的项目和项目集的个数
r(count($suspendedPrograms)) && p() && e('5');  // 查看所有'suspended'的项目和项目集的个数
r(key($namedescPrograms))    && p() && e('9');  // 按照项目和项目集名称倒序获取第一个ID
r(key($idascPrograms))       && p() && e('1');  // 按照ID正序获取项目和项目集列表第一个ID
r(count($topPrograms))       && p() && e('20'); // 获取顶级项目集个数
r(count($topPagePrograms))   && p() && e('10'); // 获取分页后顶级项目集个数
r(count($childPrograms))     && p() && e('20'); // 获取所有项目集个数

#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

/**

title=测试 programModel::getList();
cid=1
pid=1

查看所有项目和项目集的个数              >> 120
查看分页后项目集和项目的个数            >> 10
查看所有'closed'的项目和项目集的个数    >> 11
查看所有'suspended'的项目和项目集的个数 >> 11
按照项目和项目集名称倒序获取第一个ID    >> 9
按照ID正序获取项目和项目集列表第一个ID  >> 1
获取顶级项目集个数                      >> 30
获取分页后顶级项目集个数                >> 10
获取type=child 项目集个数               >> 120

*/

global $tester;
$program = new programTest();
$tester->app->loadClass('pager', $static = true);
$pager = new pager(0, 10, 1);

$allPorgrams       = $program->getList('all');
$pagerPrograms     = $program->getList('all', 'id_asc', $pager);
$closedPrograms    = $program->getList('closed');
$suspendedPrograms = $program->getList('closed');
$namedescPrograms  = $program->getList('all', 'name_desc');
$idascPrograms     = $program->getList('all', 'id_asc');
$topPrograms       = $program->getList('all', 'id_asc', null, 'top');
$topPagePrograms   = $program->getList('all', 'id_asc', $pager, 'top');
$childPrograms     = $program->getList('all', 'id_asc', null, 'child', $topPrograms);

r(count($allPorgrams))       && p() && e('120'); // 查看所有项目和项目集的个数
r(count($pagerPrograms))     && p() && e('10');  // 查看分页后项目集和项目的个数
r(count($closedPrograms))    && p() && e('11');  // 查看所有'closed'的项目和项目集的个数
r(count($suspendedPrograms)) && p() && e('11');  // 查看所有'suspended'的项目和项目集的个数
r(key($namedescPrograms))    && p() && e('9');   // 按照项目和项目集名称倒序获取第一个ID
r(key($idascPrograms))       && p() && e('1');   // 按照ID正序获取项目和项目集列表第一个ID
r(count($topPrograms))       && p() && e('30');  // 获取顶级项目集个数
r(count($topPagePrograms))   && p() && e('10');  // 获取分页后顶级项目集个数
r(count($childPrograms))     && p() && e('120'); // 获取分页后顶级项目集个数

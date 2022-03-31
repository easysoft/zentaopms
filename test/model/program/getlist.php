#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getList();
cid=1
pid=1

查看所有项目和项目集的个数 >> 100
查看所有'wait'的项目和项目集的个数 >> 34
查看所有'doing'的项目和项目集的个数 >> 44
查看所有'suspended'的项目和项目集的个数 >> 11
查看所有'closed'的项目和项目集的个数 >> 11

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

r(count($allPorgrams))       && p() && e('120'); // 查看所有项目和项目集的个数
r(count($pagerPrograms))     && p() && e('10');  // 查看分页后项目集和项目的个数
r(count($closedPrograms))    && p() && e('11');  // 查看所有'closed'的项目和项目集的个数
r(count($suspendedPrograms)) && p() && e('11');  // 查看所有'suspended'的项目和项目集的个数
r(key($namedescPrograms))    && p() && e('9');   // 按照项目和项目集名称倒序获取第一个ID
r(key($idascPrograms))       && p() && e('1');   // 按照ID正序获取项目和项目集列表第一个ID

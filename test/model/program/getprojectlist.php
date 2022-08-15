#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getProjectList();
cid=1
pid=1

获取所有项目列表数量 >> 110
获取未完成项目列表数量 >> 88
带项目集名称的项目列表数量 >> 88
获取我参与的项目列表数量 >> 1
不带项目集名称的项目名称 >> 项目2
带项目集名称的项目名称 >> 项目集2/项目2
我参与的项目的名称 >> 项目集1/项目1

*/

global $tester;
$tester->loadModel('program');

$allProjects         = $tester->program->getProjectList(0);
$undoneProjects      = $tester->program->getProjectList(0, 'undone');
$withProgramProjects = $tester->program->getProjectList(0, 'undone', 0, 'id_desc', null, 1);
$involvedProjects    = $tester->program->getProjectList(0, 'undone', 0, 'id_desc', null, 1, true);

r(count($allProjects))         && p() && e('110');                     // 获取所有项目列表数量
r(count($undoneProjects))      && p() && e('88');                      // 获取未完成项目列表数量
r(count($withProgramProjects)) && p() && e('88');                      // 带项目集名称的项目列表数量
r(count($involvedProjects))    && p() && e('1');                       // 获取我参与的项目列表数量
r($allProjects)                && p('12:name') && e('项目2');          // 不带项目集名称的项目名称 
r($withProgramProjects)        && p('12:name') && e('项目集2/项目2');  // 带项目集名称的项目名称
r($involvedProjects)           && p('11:name') && e('项目集1/项目1');  // 我参与的项目的名称
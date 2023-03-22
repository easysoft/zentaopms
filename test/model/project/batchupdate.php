#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';
su('admin');

/**

title=测试taskModel->batchUpdate();
cid=1
pid=1

查看被编辑了的项目数量 >> 3
查看被编辑了的项目11详情 >> 批量修改项目11,1,admin,2022-02-08,open
查看被编辑了的项目12详情 >> 批量修改项目12,2,,2022-03-05,private

*/

$project = new Project();
$projectIdList = array(11, 12, 13);

$data['names']         = array(11 => '批量修改项目11', 12 => '批量修改项目12', 13 => '批量修改项目13');
$data['parents']       = array(11 => 1, 12 => 2, 13 => 3);
$data['projectIdList'] = $projectIdList;
$data['PMs']           = array(11 => 'admin', 12 => '', 13 => '');
$data['begins']        = array(11 => '2022-02-08', 12 => '2022-03-05', 13 => '2022-02-19');
$data['ends']          = array(11 => '2022-04-13', 12 => '2022-04-13', 13 => '2022-04-13');
$data['dayses']        = array(11 => 10, 12 => 10, 13 => 14);
$data['acls']          = array(11 => 'open', 12 => 'private', 13 => 'program');

$projects = $project->batchUpdate($data);

r(count($projects)) && p()                              && e('3');                                      // 查看被编辑了的项目数量
r($projects)        && p('11:name,parent,PM,begin,acl') && e('批量修改项目11,1,admin,2022-02-08,open'); // 查看被编辑了的项目11详情
r($projects)        && p('12:name,parent,PM,begin,acl') && e('批量修改项目12,2,,2022-03-05,private');   // 查看被编辑了的项目12详情

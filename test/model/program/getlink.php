#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getLink();
cid=1
pid=1

项目集页面跳转项目集项目列表 >> project
非项目集页面跳转项目集列表   >> all

*/

global $tester;
$tester->loadModel('program');
$link1 = $tester->program->getLink('program', 'project', 1);
$result = '';
if(strpos($link1, 'project')) $result = 'project';
r($result) && p() && e('project'); // 项目集页面跳转项目集项目列表

$link2 = $tester->program->getLink('program', 'project', 1, '', 'product');
if(strpos($link2, 'all')) $result = 'all';
r($result) && p() && e('all'); // 非项目集页面跳转项目集列表

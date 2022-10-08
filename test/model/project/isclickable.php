#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel::isClickable();
cid=1
pid=1

检查未开始项目的开始按钮 >> 1
检查未开始项目的完成按钮 >> 1
检查未开始项目的关闭按钮 >> 1
检查未开始项目的暂停按钮 >> 1
检查未开始项目的激活按钮 >> 0
检查进行中项目的开始按钮 >> 0
检查进行中项目的完成按钮 >> 1
检查进行中项目的关闭按钮 >> 1
检查进行中项目的暂停按钮 >> 1
检查进行中项目的激活按钮 >> 0
检查已暂停项目的开始按钮 >> 1
检查已暂停项目的完成按钮 >> 0
检查已暂停项目的关闭按钮 >> 1
检查已暂停项目的暂停按钮 >> 0
检查已暂停项目的激活按钮 >> 0
检查已关闭项目的开始按钮 >> 0
检查已关闭项目的完成按钮 >> 0
检查已关闭项目的关闭按钮 >> 0
检查已关闭项目的暂停按钮 >> 0
检查已关闭项目的激活按钮 >> 1

*/

$projectModel = $tester->loadModel('project');

$waitProject    = $projectModel->getByID(11);
$doingProject   = $projectModel->getByID(13);
$suspendProject = $projectModel->getByID(17);
$closedProject  = $projectModel->getByID(18);
$status         = array('start', 'finish', 'close', 'suspend', 'activate', );

r($projectModel->isClickable($waitProject, $status[0]))     && p() && e('1'); // 检查未开始项目的开始按钮
r($projectModel->isClickable($waitProject, $status[1]))     && p() && e('1'); // 检查未开始项目的完成按钮
r($projectModel->isClickable($waitProject, $status[2]))     && p() && e('1'); // 检查未开始项目的关闭按钮
r($projectModel->isClickable($waitProject, $status[3]))     && p() && e('1'); // 检查未开始项目的暂停按钮
r($projectModel->isClickable($waitProject, $status[4]))     && p() && e('0'); // 检查未开始项目的激活按钮
r($projectModel->isClickable($doingProject, $status[0]))    && p() && e('0'); // 检查进行中项目的开始按钮
r($projectModel->isClickable($doingProject, $status[1]))    && p() && e('1'); // 检查进行中项目的完成按钮
r($projectModel->isClickable($doingProject, $status[2]))    && p() && e('1'); // 检查进行中项目的关闭按钮
r($projectModel->isClickable($doingProject, $status[3]))    && p() && e('1'); // 检查进行中项目的暂停按钮
r($projectModel->isClickable($doingProject, $status[4]))    && p() && e('0'); // 检查进行中项目的激活按钮
r($projectModel->isClickable($suspendProject, $status[0]))  && p() && e('1'); // 检查已暂停项目的开始按钮
r($projectModel->isClickable($suspendProject, $status[1]))  && p() && e('0'); // 检查已暂停项目的完成按钮
r($projectModel->isClickable($suspendProject, $status[2]))  && p() && e('1'); // 检查已暂停项目的关闭按钮
r($projectModel->isClickable($suspendProject, $status[3]))  && p() && e('0'); // 检查已暂停项目的暂停按钮
r($projectModel->isClickable($suspendProject, $status[4]))  && p() && e('0'); // 检查已暂停项目的激活按钮
r($projectModel->isClickable($closedProject, $status[0]))   && p() && e('0'); // 检查已关闭项目的开始按钮
r($projectModel->isClickable($closedProject, $status[1]))   && p() && e('0'); // 检查已关闭项目的完成按钮
r($projectModel->isClickable($closedProject, $status[2]))   && p() && e('0'); // 检查已关闭项目的关闭按钮
r($projectModel->isClickable($closedProject, $status[3]))   && p() && e('0'); // 检查已关闭项目的暂停按钮
r($projectModel->isClickable($closedProject, $status[4]))   && p() && e('1'); // 检查已关闭项目的激活按钮
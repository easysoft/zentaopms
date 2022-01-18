#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::isClickable();
cid=1
pid=1

*/

$projectModel = $tester->loadModel('project');

$waitProject    = $projectModel->getByID(11);
$doingProject   = $projectModel->getByID(13);
$suspendProject = $projectModel->getByID(17);
$closedProject  = $projectModel->getByID(18);

/* Check wait project. */
r($projectModel->isClickable($waitProject, 'start'))    && p() && e('1'); // 检查未开始项目的开始按钮
r($projectModel->isClickable($waitProject, 'finish'))   && p() && e('1'); // 检查未开始项目的完成按钮
r($projectModel->isClickable($waitProject, 'close'))    && p() && e('1'); // 检查未开始项目的关闭按钮
r($projectModel->isClickable($waitProject, 'suspend'))  && p() && e('1'); // 检查未开始项目的暂停按钮
r($projectModel->isClickable($waitProject, 'activate')) && p() && e('0'); // 检查未开始项目的激活按钮

/* Check doing project. */
r($projectModel->isClickable($doingProject, 'start'))    && p() && e('0'); //检查进行中项目的开始按钮
r($projectModel->isClickable($doingProject, 'finish'))   && p() && e('1'); //检查进行中项目的完成按钮
r($projectModel->isClickable($doingProject, 'close'))    && p() && e('1'); //检查进行中项目的关闭按钮
r($projectModel->isClickable($doingProject, 'suspend'))  && p() && e('1'); //检查进行中项目的暂停按钮
r($projectModel->isClickable($doingProject, 'activate')) && p() && e('0'); //检查进行中项目的激活按钮

/* Check suspend project. */
r($projectModel->isClickable($suspendProject, 'start'))    && p() && e('1'); //检查已暂停项目的开始按钮
r($projectModel->isClickable($suspendProject, 'finish'))   && p() && e('0'); //检查已暂停项目的完成按钮
r($projectModel->isClickable($suspendProject, 'close'))    && p() && e('1'); //检查已暂停项目的关闭按钮
r($projectModel->isClickable($suspendProject, 'suspend'))  && p() && e('0'); //检查已暂停项目的暂停按钮
r($projectModel->isClickable($suspendProject, 'activate')) && p() && e('0'); //检查已暂停项目的激活按钮

/* Check closed project. */
r($projectModel->isClickable($closedProject, 'start'))    && p() && e('0'); //检查已关闭项目的开始按钮
r($projectModel->isClickable($closedProject, 'finish'))   && p() && e('0'); //检查已关闭项目的完成按钮
r($projectModel->isClickable($closedProject, 'close'))    && p() && e('0'); //检查已关闭项目的关闭按钮
r($projectModel->isClickable($closedProject, 'suspend'))  && p() && e('0'); //检查已关闭项目的暂停按钮
r($projectModel->isClickable($closedProject, 'activate')) && p() && e('1'); //检查已关闭项目的激活按钮

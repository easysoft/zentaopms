#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('project')->gen(20);
su('admin');

/**

title=测试 projectModel::isClickable();
timeout=0
cid=17861

- 检查未开始项目的开始按钮 @1
- 检查未开始项目的完成按钮 @1
- 检查未开始项目的关闭按钮 @1
- 检查未开始项目的暂停按钮 @1
- 检查未开始项目的激活按钮 @0
- 检查进行中项目的开始按钮 @0
- 检查进行中项目的完成按钮 @1
- 检查进行中项目的关闭按钮 @1
- 检查进行中项目的暂停按钮 @1
- 检查进行中项目的激活按钮 @0
- 检查已暂停项目的开始按钮 @1
- 检查已暂停项目的完成按钮 @0
- 检查已暂停项目的关闭按钮 @1
- 检查已暂停项目的暂停按钮 @0
- 检查已暂停项目的激活按钮 @0
- 检查已关闭项目的开始按钮 @0
- 检查已关闭项目的完成按钮 @0
- 检查已关闭项目的关闭按钮 @0
- 检查已关闭项目的暂停按钮 @0
- 检查已关闭项目的激活按钮 @1

*/

$projectModel = $tester->loadModel('project');

$doingProject   = $projectModel->getByID(11);
$suspendProject = $projectModel->getByID(15);
$closedProject  = $projectModel->getByID(16);
$waitProject    = $projectModel->getByID(17);
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
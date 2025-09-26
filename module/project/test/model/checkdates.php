#!/usr/bin/env php
<?php

/**

title=测试 projectModel::checkDates();
timeout=0
cid=0

- 执行projectModel模块的checkDates方法，参数是999, $project1  @1
- 执行projectModel模块的checkDates方法，参数是1, $project2  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('project')->loadYaml('project')->gen(3)->fixPath();

su('admin');

global $tester;
$projectModel = $tester->loadModel('project');

// 手动插入测试数据
$tester->dao->exec("TRUNCATE TABLE " . TABLE_PROJECT);
$tester->dao->exec("INSERT INTO " . TABLE_PROJECT . " (id, project, type, name, begin, end, status, deleted) VALUES
    (1, 0, 'project', '主项目1', '2024-01-01', '2024-12-31', 'doing', '0'),
    (2, 0, 'project', '主项目2', '2024-01-01', '2024-12-31', 'doing', '0'),
    (11, 1, 'sprint', '子执行1', '2024-01-01', '2024-06-30', 'doing', '0'),
    (12, 1, 'sprint', '子执行2', '2024-02-01', '2024-12-31', 'doing', '0'),
    (13, 1, 'sprint', '子执行3', '2024-06-01', '2024-08-31', 'doing', '0')");

// 创建测试项目对象
$project1 = new stdClass();
$project1->begin = '2024-01-01';
$project1->end = '2024-12-31';

$project2 = new stdClass();
$project2->begin = '2024-01-01';
$project2->end = '2024-12-31';

$project3 = new stdClass();
$project3->begin = '2024-01-01';
$project3->end = '2024-05-01';

$project4 = new stdClass();
$project4->begin = '2024-03-01';
$project4->end = '2024-12-31';

$project5 = new stdClass();
$project5->begin = '2024-03-01';
$project5->end = '2024-05-01';

r($projectModel->checkDates(999, $project1)) && p() && e('1');
r($projectModel->checkDates(1, $project2)) && p() && e('1');
$projectModel->checkDates(1, $project3); r(dao::getError()) && p('end') && e('项目的完成日期应大于等于执行的最大完成日期：2024-12-31');
$projectModel->checkDates(1, $project4); r(dao::getError()) && p('begin') && e('项目的开始日期应小于等于执行的最小开始日期：2024-01-01');
$projectModel->checkDates(1, $project5); r(dao::getError()) && p('end') && e('项目的完成日期应大于等于执行的最大完成日期：2024-12-31');
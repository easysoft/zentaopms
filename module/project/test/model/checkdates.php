#!/usr/bin/env php
<?php

/**

title=测试 projectModel::checkDates();
timeout=0
cid=17807

- 步骤1：不存在的项目ID @1
- 步骤2：正常项目日期范围 @1
- 步骤3：结束日期冲突属性end @项目的完成日期应大于等于执行的最大完成日期：2024-12-31
- 步骤4：开始日期冲突属性begin @项目的开始日期应小于等于执行的最小开始日期：2024-01-01
- 步骤5：多重日期冲突属性end @项目的完成日期应大于等于执行的最大完成日期：2024-12-31

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 准备测试数据：手动插入项目数据避免zendata依赖问题
global $tester;
$tester->dao->exec("DELETE FROM " . TABLE_PROJECT . " WHERE id BETWEEN 1 AND 100");
$tester->dao->exec("INSERT INTO " . TABLE_PROJECT . " (id, project, type, name, begin, end, status, deleted) VALUES
    (1, 0, 'project', '主项目1', '2024-01-01', '2024-12-31', 'doing', '0'),
    (2, 0, 'project', '主项目2', '2024-01-01', '2024-12-31', 'doing', '0'),
    (11, 1, 'sprint', '子执行1', '2024-01-01', '2024-06-30', 'doing', '0'),
    (12, 1, 'sprint', '子执行2', '2024-02-01', '2024-12-31', 'doing', '0'),
    (13, 1, 'sprint', '子执行3', '2024-06-01', '2024-08-31', 'doing', '0')");

su('admin');

$projectModel = $tester->loadModel('project');

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

r($projectModel->checkDates(999, $project1)) && p() && e('1'); // 步骤1：不存在的项目ID
r($projectModel->checkDates(1, $project2)) && p() && e('1'); // 步骤2：正常项目日期范围

$projectModel->checkDates(1, $project3);
r(dao::getError()) && p('end') && e('项目的完成日期应大于等于执行的最大完成日期：2024-12-31'); // 步骤3：结束日期冲突

$projectModel->checkDates(1, $project4);
r(dao::getError()) && p('begin') && e('项目的开始日期应小于等于执行的最小开始日期：2024-01-01'); // 步骤4：开始日期冲突

$projectModel->checkDates(1, $project5);
r(dao::getError()) && p('end') && e('项目的完成日期应大于等于执行的最大完成日期：2024-12-31'); // 步骤5：多重日期冲突
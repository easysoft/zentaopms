#!/usr/bin/env php
<?php

/**

title=测试 biModel::getEchartOptions();
timeout=0
cid=15572

- 查询获取到的所有属性第0条的name属性 @5
- 查询结果中的gird
 - 第grid条的left属性 @3%
 - 第grid条的right属性 @4%
- 查询结果中的xAxis第xAxis条的type属性 @category
- 查询结果中的yAxis第yAxis条的type属性 @value
- 查询结果中的tooltip第tooltip条的trigger属性 @axis

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zenData('action')->gen(500);
zenData('project')->gen(100);

global $tester;
$tester->loadModel('chart');

$chart = new stdclass();
$chart->id = 1080;
$chart->name = '年度新增-项目年度新增和完成趋势图';
$chart->code = 'annualCreated_projectTendency';
$chart->driver = 'mysql';
$chart->mode = 'text';
$chart->dimension = 1;
$chart->type = 'line';
$chart->group = 38;
$chart->dataset = 0;
$chart->desc = '';
$chart->acl = 'open';
$chart->whitelist = '';
$chart->settings = array(0 => array('type' => 'line', 'xaxis' => array( 0 => array ( 'field' => 'YEARMONTH', 'name' => 'YEARMONTH', 'group' => '', ), ), 'yaxis' => array ( 0 => array ( 'field' => 'newProject', 'name' => 'newProject', 'valOrAgg' => 'sum', ), 1 => array ( 'field' => 'closedProject', 'name' => '已关闭的项目', 'valOrAgg' => 'sum'))));
$chart->filters = array();
$chart->fields = array();
$chart->langs = array();
$chart->sql = 'SELECT YEARMONTH, t1.year, CONCAT(t1.month, "月") AS `month`, IFNULL(t2.project, 0) AS newProject, IFNULL(t3.project, 0) AS closedProject FROM (SELECT DISTINCT DATE_FORMAT(date, \'%Y-%m\') YEARMONTH, Year(date) AS `year`, MONTH(date) AS `month` FROM zt_action) AS t1 LEFT JOIN (SELECT YEAR(openedDate) AS `year`, MONTH(openedDate) AS `month`, COUNT(1) AS project FROM zt_project WHERE deleted = \'0\' AND type = \'project\' GROUP BY `year`, `month`) AS t2 ON t1.year = t2.year AND t1.month = t2.month LEFT JOIN (SELECT YEAR(closedDate) AS `year`, MONTH(closedDate) AS `month`, COUNT(1) AS project FROM zt_project WHERE deleted = \'0\' AND type = \'project\' AND status = \'closed\' GROUP BY `year`, `month`) AS t3 ON t1.year = t3.year AND t1.month = t3.month ORDER BY `year`, t1.month';
$chart->version = '1';
$chart->stage = 'published';
$chart->builtin = 0;
$chart->objects = '';
$chart->createdBy = 'system';
$chart->createdDate = '2025-06-16 11:25:41';
$chart->editedBy = '';
$chart->editedDate = '';
$chart->deleted = 0;
$chart->fieldSettings = array('YEARMONTH' => array ( 'name' => 'YEARMONTH', 'object' => 'project', 'field' => 'YEARMONTH', 'type' => 'string', ), 'year' => array ( 'name' => 'year', 'object' => 'project', 'field' => 'year', 'type' => 'number', ), 'month' => array ( 'name' => 'month', 'object' => 'project', 'field' => 'month', 'type' => 'string', ), 'newProject' => array ( 'name' => 'newProject', 'object' => 'project', 'field' => 'newProject', 'type' => 'string', ), 'closedProject' => array ( 'name' => '已关闭的项目', 'object' => 'project', 'field' => 'closedProject', 'type' => 'string'));
$chart->currentGroup = 38;

$result = $tester->chart->getEchartOptions($chart);
r(count($result)) && p('0:name')           && e('5');        // 查询获取到的所有属性
r($result)        && p('grid:left,right')  && e('3%,4%');    // 查询结果中的gird
r($result)        && p('xAxis:type')       && e('category'); // 查询结果中的xAxis
r($result)        && p('yAxis:type')       && e('value');    // 查询结果中的yAxis
r($result)        && p('tooltip:trigger')  && e('axis');     // 查询结果中的tooltip
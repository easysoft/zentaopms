#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getSwitcher(, , );
cid=1
pid=1

获取项目下拉菜单，项目列表页面没有项目下拉菜单 >> 0
获取项目ID为11的迭代列表页面的项目下拉菜单，查看项目1下拉框出现的位置 >> 137

*/

$project = $tester->loadModel('project');

r($project->getSwitcher(11, 'project', 'browse'))                                               && p() && e('0');   // 获取项目下拉菜单，项目列表页面没有项目下拉菜单
r(strpos($project->getSwitcher(11, 'project', 'execution'), "<span class='text'>项目1</span>")) && p() && e('137'); // 获取项目ID为11的迭代列表页面的项目下拉菜单，查看项目1下拉框出现的位置
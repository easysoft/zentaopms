#!/usr/bin/env php
<?php

/**

title=项目列表页tab标签检查
timeout=0
cid=73

- 检查all标签数量
 - 测试结果 @all标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查undone标签数量
 - 测试结果 @undone标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查wait标签数量
 - 测试结果 @wait标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查doing标签数量
 - 测试结果 @doing标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查suspended标签数量
 - 测试结果 @suspended标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查closed标签数量
 - 测试结果 @closed标签下条数显示正确
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/browsetab.ui.class.php';

$project = zenData('project');
$project->id->range('1-8');
$project->project->range('0');
$project->model->range('scrum{3}, waterfall{3}, kanban{2}');
$project->type->range('project');
$project->attribute->range('[]');
$project->auth->range('[]');
$project->parent->range('0');

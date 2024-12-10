#!/usr/bin/env php
<?php

/**
title=检查产品-业务需求/用户需求/研发需求各tab数据
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/browse.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->PO->range('admin');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);

$story = zenData('story');
$story->id->range('1-33');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-33');
$story->path->range('1-29');
$story->grade->range('1');
$story->product->range('1');
$story->plan->range('0');
$story->version->range('1');
$story->title->range('研需1,研需2,研需3,研需4,研需5,研需6,研需7,研需8,研需9,研需10,用需1,用需2,用需3,用需4,用需5,用需6,用需7,用需8,用需9,用需10,用需11,业需1,业需2,业需3,业需4,业需5,业需6,业需7,业需8,业需9,业需10,业需11,业需12');
$story->type->range('story{10},requirement{11},epic{12}');
$story->status->range('active{3},closed{3},reviewing{2},draft,changing,active{3},closed{2},draft,changing{2},active,reviewing{6},closed{2},reviewing{2},changing,active,draft{2}');
$story->openedBy->range('admin');
$story->assignedTo->range('admin{3},closed{3},admin{2},{5},closed{2},admin{2},{3},admin{11},{2}');
$story->closedBy->range('{3},admin{3},{4},{3},admin{2},{10},admin{2},{6}');
$story->reviewedBy->range('admin{3},{7},admin{3},{3},admin,{8},admin{2},{6}');
$story->deleted->range('0');
$story->gen(33);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-33');
$storyspec->version->range('1');
$storyspec->title->range('研需1,研需2,研需3,研需4,研需5,研需6,研需7,研需8,研需9,研需10,用需1,用需2,用需3,用需4,用需5,用需6,用需7,用需8,用需9,用需10,用需11,业需1,业需2,业需3,业需4,业需5,业需6,业需7,业需8,业需9,业需10,业需11,业需12');
$storyspec->gen(33);

$storyreview = zenData('storyreview');
$storyreview->story->range('1-33');
$storyreview->reviewer->range('admin');
$storyreview->result->range('pass{6},{4},pass{3},{7},pass,{5},pass{2},{6}');
$storyreview->gen(33);

$action = zenData('action');
$action->id->range('1-35');
$action->objectType->range('task{4},story{13},bug{3},story{15}');
$action->objectID->range('3,4,5,6,1,2,3,7,8,16,17,18,19,20,21,22,23,3,4,5,1,2,3,11,12,13,22,23,1,2,3,11,12,13,27');
$action->product->range('1');
$action->project->range('1{4},0{13},1{3},0{15}');
$action->execution->range('2{4},0{13},2{3},0{15}');
$action->actor->range('admin');
$action->action->range('assigned{20},submitreview{8},reviewed{7}');
$action->extra->range('{28},Pass{7}');
$action->gen(35);

$tester = new browseTester();
$tester->closeBrowser();

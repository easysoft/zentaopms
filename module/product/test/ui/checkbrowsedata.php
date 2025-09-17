#!/usr/bin/env php
<?php

/**
title=检查产品-业务需求/用户需求/研发需求各tab数据
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/ui/browse.ui.class.php';

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
$tester->login();

$erUrl = [
    'productTD'  => 1,
    'branch'     => '',
    'browseType' => 'unclosed',
    'param'      => '0',
    'storyType'  => 'epic',
];
r($tester->checkData($erUrl, 'ER', 'all','12'))          && p('message,status') && e('业务需求的全部tab下数据正确,SUCCESS');//检查业务需求列表全部tab下的数据
r($tester->checkData($erUrl, 'ER', 'open','10'))         && p('message,status') && e('业务需求的未关闭tab下数据正确,SUCCESS');//检查业务需求列表未关闭tab下的数据
r($tester->checkData($erUrl, 'ER', 'assignedToMe','10')) && p('message,status') && e('业务需求的指给我tab下数据正确,SUCCESS');//检查业务需求列表指给我tab下的数据
r($tester->checkData($erUrl, 'ER', 'createdByMe', '12')) && p('message,status') && e('业务需求的我创建tab下数据正确,SUCCESS');//检查业务需求列表我创建tab下的数据
r($tester->checkData($erUrl, 'ER', 'reviewByMe', '5'))   && p('message,status') && e('业务需求的待我评审tab下数据正确,SUCCESS');//检查业务需求列表待我评审tab下的数据
r($tester->checkData($erUrl, 'ER', 'draft', '2'))        && p('message,status') && e('业务需求的草稿tab下数据正确,SUCCESS');//检查业务需求列表草稿tab下的数据
r($tester->checkData($erUrl, 'ER', 'reviewedByMe', '2')) && p('message,status') && e('业务需求的我评审tab下数据正确,SUCCESS');//检查业务需求列表我评审tab下的数据
r($tester->checkData($erUrl, 'ER', 'assignedByMe', '2')) && p('message,status') && e('业务需求的我指派tab下数据正确,SUCCESS');//检查业务需求列表我指派tab下的数据
r($tester->checkData($erUrl, 'ER', 'closedByMe', '2'))   && p('message,status') && e('业务需求的我关闭tab下数据正确,SUCCESS');//检查业务需求列表我关闭tab下的数据
r($tester->checkData($erUrl, 'ER', 'activated', '1'))    && p('message,status') && e('业务需求的激活tab下数据正确,SUCCESS');//检查业务需求列表激活tab下的数据
r($tester->checkData($erUrl, 'ER', 'changing', '1'))     && p('message,status') && e('业务需求的变更中tab下数据正确,SUCCESS');//检查业务需求列表变更中tab下的数据
r($tester->checkData($erUrl, 'ER', 'reviewing', '6'))    && p('message,status') && e('业务需求的评审中tab下数据正确,SUCCESS');//检查业务需求列表评审中tab下的数据
r($tester->checkData($erUrl, 'ER', 'toBeClosed', '1'))   && p('message,status') && e('业务需求的待关闭tab下数据正确,SUCCESS');//检查业务需求列表待关闭tab下的数据
r($tester->checkData($erUrl, 'ER', 'closed', '2'))       && p('message,status') && e('业务需求的已关闭tab下数据正确,SUCCESS');//检查业务需求列表已关闭tab下的数据

$urUrl = [
    'productTD'  => 1,
    'branch'     => '',
    'browseType' => 'unclosed',
    'param'      => '0',
    'storyType'  => 'requirement',
];
r($tester->checkData($urUrl, 'UR', 'all','11'))          && p('message,status') && e('用户需求的全部tab下数据正确,SUCCESS');//检查用户需求列表全部tab下的数据
r($tester->checkData($urUrl, 'UR', 'open','9'))          && p('message,status') && e('用户需求的未关闭tab下数据正确,SUCCESS');//检查用户需求列表未关闭tab下的数据
r($tester->checkData($urUrl, 'UR', 'assignedToMe','3'))  && p('message,status') && e('用户需求的指给我tab下数据正确,SUCCESS');//检查用户需求列表指给我tab下的数据
r($tester->checkData($urUrl, 'UR', 'createdByMe', '11')) && p('message,status') && e('用户需求的我创建tab下数据正确,SUCCESS');//检查用户需求列表我创建tab下的数据
r($tester->checkData($urUrl, 'UR', 'reviewByMe', '1'))   && p('message,status') && e('用户需求的待我评审tab下数据正确,SUCCESS');//检查用户需求列表待我评审tab下的数据
r($tester->checkData($urUrl, 'UR', 'draft', '1'))        && p('message,status') && e('用户需求的草稿tab下数据正确,SUCCESS');//检查用户需求列表草稿tab下的数据
r($tester->checkData($urUrl, 'UR', 'reviewedByMe', '4')) && p('message,status') && e('用户需求的我评审tab下数据正确,SUCCESS');//检查用户需求列表我评审tab下的数据
r($tester->checkData($urUrl, 'UR', 'assignedByMe', '6')) && p('message,status') && e('用户需求的我指派tab下数据正确,SUCCESS');//检查用户需求列表我指派tab下的数据
r($tester->checkData($urUrl, 'UR', 'closedByMe', '2'))   && p('message,status') && e('用户需求的我关闭tab下数据正确,SUCCESS');//检查用户需求列表我关闭tab下的数据
r($tester->checkData($urUrl, 'UR', 'activated', '4'))    && p('message,status') && e('用户需求的激活tab下数据正确,SUCCESS');//检查用户需求列表激活tab下的数据
r($tester->checkData($urUrl, 'UR', 'changing', '2'))     && p('message,status') && e('用户需求的变更中tab下数据正确,SUCCESS');//检查用户需求列表变更中tab下的数据
r($tester->checkData($urUrl, 'UR', 'reviewing', '2'))    && p('message,status') && e('用户需求的评审中tab下数据正确,SUCCESS');//检查用户需求列表评审中tab下的数据
r($tester->checkData($urUrl, 'UR', 'toBeClosed', '1'))   && p('message,status') && e('用户需求的待关闭tab下数据正确,SUCCESS');//检查用户需求列表待关闭tab下的数据
r($tester->checkData($urUrl, 'UR', 'closed', '2'))       && p('message,status') && e('用户需求的已关闭tab下数据正确,SUCCESS');//检查用户需求列表已关闭tab下的数据

$srUrl['productTD'] = 1;
r($tester->checkData($srUrl, 'SR', 'all','10'))          && p('message,status') && e('研发需求的全部tab下数据正确,SUCCESS');//检查研发需求列表全部tab下的数据
r($tester->checkData($srUrl, 'SR', 'open','7'))          && p('message,status') && e('研发需求的未关闭tab下数据正确,SUCCESS');//检查研发需求列表未关闭tab下的数据
r($tester->checkData($srUrl, 'SR', 'assignedToMe','5'))  && p('message,status') && e('研发需求的指给我tab下数据正确,SUCCESS');//检查研发需求列表指给我tab下的数据
r($tester->checkData($srUrl, 'SR', 'createdByMe', '10')) && p('message,status') && e('研发需求的我创建tab下数据正确,SUCCESS');//检查研发需求列表我创建tab下的数据
r($tester->checkData($srUrl, 'SR', 'reviewByMe', '2'))   && p('message,status') && e('研发需求的待我评审tab下数据正确,SUCCESS');//检查研发需求列表待我评审tab下的数据
r($tester->checkData($srUrl, 'SR', 'draft', '1'))        && p('message,status') && e('研发需求的草稿tab下数据正确,SUCCESS');//检查研发需求列表草稿tab下的数据
r($tester->checkData($srUrl, 'SR', 'reviewedByMe', '3')) && p('message,status') && e('研发需求的我评审tab下数据正确,SUCCESS');//检查研发需求列表我评审tab下的数据
r($tester->checkData($srUrl, 'SR', 'assignedByMe', '5')) && p('message,status') && e('研发需求的我指派tab下数据正确,SUCCESS');//检查研发需求列表我指派tab下的数据
r($tester->checkData($srUrl, 'SR', 'closedByMe', '3'))   && p('message,status') && e('研发需求的我关闭tab下数据正确,SUCCESS');//检查研发需求列表我关闭tab下的数据
r($tester->checkData($srUrl, 'SR', 'activated', '3'))    && p('message,status') && e('研发需求的激活tab下数据正确,SUCCESS');//检查研发需求列表激活tab下的数据
r($tester->checkData($srUrl, 'SR', 'changing', '1'))     && p('message,status') && e('研发需求的变更中tab下数据正确,SUCCESS');//检查研发需求列表变更中tab下的数据
r($tester->checkData($srUrl, 'SR', 'reviewing', '2'))    && p('message,status') && e('研发需求的评审中tab下数据正确,SUCCESS');//检查研发需求列表评审中tab下的数据
r($tester->checkData($srUrl, 'SR', 'toBeClosed', '1'))   && p('message,status') && e('研发需求的待关闭tab下数据正确,SUCCESS');//检查研发需求列表待关闭tab下的数据
r($tester->checkData($srUrl, 'SR', 'closed', '3'))       && p('message,status') && e('研发需求的已关闭tab下数据正确,SUCCESS');//检查研发需求列表已关闭tab下的数据
$tester->closeBrowser();

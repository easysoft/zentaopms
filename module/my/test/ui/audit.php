#!/usr/bin/env php
<?php

/**
title=检查地盘审批数据/在审批列表中进行评审
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/ui/audit.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->PO->range('admin');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);

$story = zenData('story');
$story->id->range('1-11');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-11');
$story->path->range('1-11');
$story->grade->range('1');
$story->product->range('1');
$story->version->range('1');
$story->title->range('业需01,业需02,业需03,业需04,用需01,用需02,用需03,用需04,用需05,研需01,研需02');
$story->type->range('epic{4},requirement{5},story{2}');
$story->status->range('reviewing');
$story->openedBy->range('admin');
$story->assignedTo->range('admin');
$story->reviewedBy->range('{11}');
$story->deleted->range('0');
$story->gen(11);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-11');
$storyspec->version->range('1');
$storyspec->title->range('业需01,业需02,业需03,业需04,用需01,用需02,用需03,用需04,用需05,研需01,研需02');
$storyspec->gen(11);

$storyreview = zenData('storyreview');
$storyreview->story->range('1-11');
$storyreview->reviewer->range('admin');
$storyreview->result->range('{11}');
$storyreview->gen(11);
zendata('case')->loadYaml('case', false, 2)->gen(0);
zendata('casespec')->loadYaml('casespec', false, 2)->gen(0);
zendata('casestep')->loadYaml('casestep', false, 2)->gen(0);

$tester = new auditTester();
$tester->login();

/*检查审批列表中各tab下数据*/
r($tester->checkAudit('all', '11')) && p('message,status') && e('全部tab下数据显示正确,SUCCESS');    //检查审批-[全部]tab下的数据
r($tester->checkAudit('SR', '2'))   && p('message,status') && e('研发需求tab下数据显示正确,SUCCESS');//检查审批-[研发需求]tab下的数据
r($tester->checkAudit('ER', '4'))   && p('message,status') && e('业务需求tab下数据显示正确,SUCCESS');//检查审批-[业务需求]tab下的数据
r($tester->checkAudit('UR', '5'))   && p('message,status') && e('用户需求tab下数据显示正确,SUCCESS');//检查审批-[用户需求]tab下的数据

/*在审批列表中进行评审*/
r($tester->review('SR','1')) && p('message,status') && e('研发需求评审成功,SUCCESS');//在审批列表中评审研发需求
r($tester->review('ER','3')) && p('message,status') && e('业务需求评审成功,SUCCESS');//在审批列表中评审业务需求
r($tester->review('UR','4')) && p('message,status') && e('用户需求评审成功,SUCCESS');//在审批列表中评审用户需求

$tester->closeBrowser();

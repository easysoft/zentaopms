#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/init.php';
/**
[case]
title=测试 gapanalysisModel::getById();
cid=1
pid=1
[group]
  1. 使用ID获取一个存在的能力差距分析   >> `\d,\d+,\w+\d`
  2. 使用ID获取一个不存在的能力差距分析 >> ``
[esac]
*/
$gapanalysis = $tester->loadModel('gapanalysis');

$app->dbh->query("truncate zt_gapanalysis");
$id = rand(1,5);
zdImport(TABLE_GAPANALYSIS, "zendata/gapanalysis.yaml", 5);

$randGapAnalysis = $tester->dao->select('*,rand() as rand')->from(TABLE_GAPANALYSIS)->where('id')->eq($id)->orderBy('rand')->fetch();
if(!$randGapAnalysis) exit("Prepair data error.");
unset($randGapAnalysis->rand);

/* Step 1.*/
run($gapanalysis->getByID($randGapAnalysis->id, 'id')) and expect('id,project,account');

/* Step 2.*/
run($gapanalysis->getByID(null, 'id')) and expect('id');

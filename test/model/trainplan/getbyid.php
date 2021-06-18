#!/usr/bin/env php
<?php
/**
title=测试 trainplanModel::getById();
cid=1
pid=1

获取到的project是21,lecturer是hufangzhou  >> `21,hufangzhou`
*/
include dirname(dirname(dirname(__FILE__))) . '/init.php';

$trainplan = $tester->loadModel('trainplan');

$app->dbh->query("truncate zt_trainplan");
zdImport(TABLE_TRAINPLAN, "zendata/trainplan.yaml", 10);

$randTrainPlan = $tester->dao->select('*,rand() as rand')->from(TABLE_TRAINPLAN)->orderBy('rand')->fetch();
if(!$randTrainPlan) exit("Prepair data error.");
unset($randTrainPlan->rand);

/* Step 1.*/
run($trainplan->getById($randTrainPlan->id)) and expect('project,lecturer');

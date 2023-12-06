#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

/**
title=测试 pivotModel->processFieldSettings();
cid=1
pid=1

field和fieldSettings都为空，不做任何处理                                 >> 1
判断是否生成了正确的sql，如果fieldSetting存在，则判定为正确。            >> 1
sql错误的时候，不做任何处理。                                            >> 1
id为1003的透视表，没有project字段，判断是否通过此方法生成了project字段。 >> 1

*/

global $tester;
$pivotTest = new pivotTest();

$pivotIDList = array(1023, 1003);
$pivotList   = array();

foreach($pivotIDList as $pivotID) $pivotList[] = $tester->dao->select('*')->from(TABLE_PIVOT)->where('id')->eq($pivotID)->fetch();

$pivot1  = $pivotList[0];
$pivot1_  = clone($pivot1);
$pivot1_->fieldSettings = '';
$pivot1_->fields = '';
$pivot1_1 = clone($pivot1_);

$pivotTest->processFieldSettings($pivot1_);
r($pivot1_1->fieldSettings === $pivot1_->fieldSettings) && p('') && e(1);    //field和fieldSettings都为空，不做任何处理

$pivot2_ = clone($pivot1);
$pivotTest->processFieldSettings($pivot2_);
r(isset($pivot2_->fieldSettings)) && p('') && e(1);    //判断是否生成了正确的sql，如果fieldSetting存在，则判定为正确。

$pivot3_ = clone($pivot1);
$pivot3_->sql = 'xxx';
$pivot3_1 = clone($pivot3_);
$pivotTest->processFieldSettings($pivot3_);
r($pivot3_1 == $pivot3_) && p('') && e(1);    //sql错误的时候，不做任何处理。

$pivot2 = $pivotList[1];
$pivotTest->processFieldSettings($pivot2);
r(isset($pivot2->fieldSettings->project)) && p('') && e(1);    //id为1003的透视表，没有project字段，判断是否通过此方法生成了project字段。

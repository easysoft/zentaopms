#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=bugModel->reportCondition();
timeout=0
cid=15404

- 获取 bugQueryCondition id,name 有 bugOnlyCondition 的 reportCondition 值 @SELECT id,name FROM xxxxx

- 获取 bugQueryCondition id,name 无 bugOnlyCondition 的 reportCondition 值 @id in (SELECT t1.id FROM xxxxx)
- 获取 bugQueryCondition * 有 bugOnlyCondition 的 reportCondition 值 @SELECT * FROM xxxxx
- 获取 bugQueryCondition * 无 bugOnlyCondition 的 reportCondition 值 @id in (SELECT t1.id FROM xxxxx)
- 获取 无 bugQueryCondition 有 bugOnlyCondition 的 reportCondition 值 @1=1
- 获取 无 bugQueryCondition 无 bugOnlyCondition 的 reportCondition 值 @1=1

*/

$bugQueryConditionList = array('SELECT id,name FROM xxxxx', 'SELECT * FROM xxxxx', false);
$bugOnlyConditionList  = array(true, false);

$bug = new bugModelTest();
r($bug->reportConditionTest($bugQueryConditionList[0], $bugOnlyConditionList[0])) && p() && e('SELECT id,name FROM xxxxx');       // 获取 bugQueryCondition id,name 有 bugOnlyCondition 的 reportCondition 值
r($bug->reportConditionTest($bugQueryConditionList[0], $bugOnlyConditionList[1])) && p() && e('id in (SELECT t1.id FROM xxxxx)'); // 获取 bugQueryCondition id,name 无 bugOnlyCondition 的 reportCondition 值
r($bug->reportConditionTest($bugQueryConditionList[1], $bugOnlyConditionList[0])) && p() && e('SELECT * FROM xxxxx');             // 获取 bugQueryCondition * 有 bugOnlyCondition 的 reportCondition 值
r($bug->reportConditionTest($bugQueryConditionList[1], $bugOnlyConditionList[1])) && p() && e('id in (SELECT t1.id FROM xxxxx)'); // 获取 bugQueryCondition * 无 bugOnlyCondition 的 reportCondition 值
r($bug->reportConditionTest($bugQueryConditionList[2], $bugOnlyConditionList[0])) && p() && e('1=1');                             // 获取 无 bugQueryCondition 有 bugOnlyCondition 的 reportCondition 值
r($bug->reportConditionTest($bugQueryConditionList[2], $bugOnlyConditionList[1])) && p() && e('1=1');                             // 获取 无 bugQueryCondition 无 bugOnlyCondition 的 reportCondition 值